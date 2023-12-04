<?php
require_once( 'PrClient.php' ); // priority api client
$config  = include('giladConfig.php');
$prclient = new PrClient($config['odataUrl'],$config['companyName'],$config['uname'],$config['pass'],$config['key']);
$phpScript = "/home/gilad/Documents/working_with_node/getXlData.php";
$xlFile = "/home/gilad/Documents/working_with_node/xlsxFiles/29112023.xlsx";
$data = [];
// problematic skus - contains special chars
$prob_skus = array("TPS0001%2FMCH00202" => "TPS0001/MCH00202"); // is global in mts script

// Execute the second PHP script and capture its output
exec("php $phpScript $xlFile", $data, $returnCode);

/* Check if the execution was successful */
if ($returnCode === 0) {
    $data = json_decode($data[0]);
    // print_r($data);
} else {
    echo "Execution failed with return code: $returnCode\n";
}


// $csvFilePath = "/home/gilad/Documents/php_work/gilad/uploads/13092023_01_xlsx2csv.csv";
// $targetFile = "/home/gilad/Documents/php_work/gilad/excel_files/13092023_01.xlsx";
//
// $command = "xlsx2csv $targetFile $csvFilePath";
// exec($command, $output, $returnCode);
// if ($returnCode !== 0) {
//   echo "conversion with xlsx2csv failed\n"; // moves back to index.html - convert fail
//   die();
// }

# Priority API and related columns names
$partname = "Customer Part Number";
$api_partname = "PARTNAME";
$price = "Unit Price based on Sales Currency"; // this is part of the actual name. The full name is 'Unit Price based on Sales Currency\nVolume 1'
$api_price = "SECONDPRICE";

$cols = findCols_v2($data, $partname, $price);
print_r($cols);

// foreach ($data as $subArr) {
//   $subArr = (array) $subArr;
//   $partname_index = $cols[$partname];
//   $price_index = $cols[$price];
//   if (key_exists($partname_index, $subArr) && key_exists($price_index, $subArr)) {
//     print_r($subArr[$partname_index]);
//     echo " ";
//     print_r($subArr[$price_index]);
//     echo "\n";
//   }
// }


$not_valid_sku_bom = array();
// Initialize a dictionary to hold correct sku names for API
$cols_dic = array();
// insert the non-sku cols
$cols_dic[$partname] = $partname;
$cols_dic[$price] = $price;
foreach ($cols as $columnName => $col_num) {
  // fix each col name that is sku and define in the dictionary
  if ($columnName != $partname && $columnName != $price) {
    $fixedColName = fix_errors_check_valid($columnName, $prclient);
    if ($fixedColName != "BOM: No such SKU" && $fixedColName != "Not checked in part tree") {
      $query = 'ZUC_FULLPARTTREE?$filter=PARTNAME%20eq%20%27' . $fixedColName . '%27';
      $res = $prclient->get($query);
      $res = json_decode($res,true);
      // print_r($res);
      // echo "\n##############\n";
      $skuBomArr = array();
      $skuBomArr["sku_name"] = $fixedColName;
      foreach ($res['value'] as $skuBomData) {
        // print_r($skuBomData);
        if (empty($skuBomArr[$skuBomData['PARTNAMECH']]))
          $skuBomArr[$skuBomData['PARTNAMECH']] = $skuBomData['RATIO'];
        else {
          $skuBomArr[$skuBomData['PARTNAMECH']] += $skuBomData['RATIO'];
        }
      }
      // used in the parsing of excel file with BOM sku's
      $bom_skus_mat[$fixedColName] = $skuBomArr;
      $cols_dic[$columnName] = $fixedColName;
    }
    else // $fixedColName == "BOM: No such SKU" || "Not checked in part tree"
      $not_valid_sku_bom[$columnName] = $fixedColName . ": " . $columnName;
  }
}
// print_r($bom_skus_mat);

// print_r($not_valid_sku_bom);
// die();

$desiredData = array(); // Initialize an array to store the desired data
$foundItem = false;
foreach ($data as $subArr) {
  $duplicate_sku = false;
  $subArr = (array) $subArr;
  // 'Item' is the first header in the headers row
  // Start storing the data after headers row is found
  if (in_array("Item", $subArr))
    $foundItem = true;
  if ($foundItem) {
    $rowData = array();
    $sku_name = $subArr[$cols[$partname]]; // we save and check the current sku $subArr[__EMPTY_2]
    if (array_key_exists($sku_name, $desiredData)) {
      $rowData = $desiredData[$sku_name];
      $duplicate_sku = true;
    }
    foreach ($cols as $columnName => $columnIndex) {
      if (!array_key_exists($columnName, $not_valid_sku_bom)) {
        if (!empty($subArr[$columnIndex])) {
          // the key is the fixed sku name and the data is according to the excel file
          // echo $subArr[$columnIndex] . "\n";
          $rowData[$cols_dic[$columnName]] = $subArr[$columnIndex];
        } elseif (empty($subArr[$columnIndex]) && !$duplicate_sku) {
          $rowData[$cols_dic[$columnName]] = "";
        }
      }
    }
    $desiredData[$sku_name] = $rowData;
  }
}
// print_r($desiredData);

// die();


$resultData = array();
$resultData[] = $not_valid_sku_bom;
// print_r($resultData);
// die();
foreach (array_slice($desiredData, 1) as $sku_excel_array) {
  if ($sku_excel_array[$price] == "")
    $sku_excel_array[$price] = "0";
  # for the case of two or more skus in one cell
  $sku_names = parsePartNumber($sku_excel_array[$partname]);
  foreach ($sku_names as $sku_index => $sku) {
    $skuData = array();
    $sku = trim($sku);
    $sku = urlencode($sku);
    $res = $prclient->get('LOGPART(\'' . $sku . '\')');
    $res = json_decode($res,true);

    if (!empty($res) && array_key_exists($api_partname,$res)) { // exist in priority
      $name_status = "OK";
      if (is_double($res[$api_price]))
        $res[$api_price] = number_format($res[$api_price], 4);
      if (is_double($sku_excel_array[$price]))
        $sku_excel_array[$price] = number_format($sku_excel_array[$price], 4);
      # case 1. There is match between prices
      if ($res[$api_price] == $sku_excel_array[$price])
        $price_status="OK";
        // $price_status = $index+1 . ". part number SKU " . $res[$api_partname] . ": unit price is OK\n";
      # case 2. Different price for the sku in the excel file
      else {
        $price_status = "NOT OK";
        // $price_status = $index+1 . ". part number SKU " . $res[$api_partname] . ": unit price is NOT OK - CSV unit price is: " . $sku_excel_array[$price] . " and Priority unit price is: " . $res[$api_price];
      }
      $price_prio = $res[$api_price];
    }
    // The sku doesn't exist so it's price also doesn't exist
    else {
      $name_status = "NOT OK";
      $price_status = "NOT OK";
      $price_prio = "NA";
    }
    if ($sku_index + 1 < count($sku_names) && ($name_status == "NOT OK" || $price_status == "NOT OK"))
      continue;
    if (array_key_exists($sku, $prob_skus))
      $sku = $prob_skus[$sku];
    $skuData[$partname] = $sku;
    $skuData["Name Status"] = $name_status;
    $skuData["Price - quote"] = $sku_excel_array[$price];
    $skuData["Price - priority"] = $price_prio;
    $skuData["Price Status"] = $price_status;

    if (!empty($bom_skus_mat)) {
      $bom_msg = check_bom_sku($partname, $price, $sku_excel_array, $bom_skus_mat, $sku);
      // arranging the Data for creating CSV file
      foreach ($bom_skus_mat as $bom_sku) {
        $skuData[$bom_sku["sku_name"] . " - quote"] = $bom_msg[$bom_sku["sku_name"] . " - quote"];
        $skuData[$bom_sku["sku_name"] . " - priority"] = $bom_msg[$bom_sku["sku_name"] . " - priority"];
        $skuData[$bom_sku["sku_name"] . " - status"] = $bom_msg[$bom_sku["sku_name"] . " - status"];
      }
    }
    $resultData[] = $skuData;
    if ($name_status == "OK" && $price_status == "OK")
      break;
  }
}

// print_r($resultData);
// die();


$csvFileName = "process_res/output_2211_11:10.csv";
$csvFile = fopen($csvFileName, "w");

foreach ($resultData[0] as $index => $value) {
  $rowData = array($value);
  fputcsv($csvFile, $rowData);
}

// Add an empty row
fputcsv($csvFile, array_fill(0, count($resultData[0]) + 1, ""));

// Extract column headers from the keys in the first row
$headers = array_keys($resultData[1]);
array_unshift($headers, "Number");

// Create a CSV file and write headers
fputcsv($csvFile, $headers);

// Loop through each row and write values to the CSV file
foreach (array_slice($resultData, 1) as $index => $row) {
    $rowData = array_values($row);
    array_unshift($rowData, $index + 1); // Add the row number
    fputcsv($csvFile, $rowData);
}
fclose($csvFile);

echo "CSV file created successfully!\n";



// $num = count($desiredData);
// for ($i=20; $i<30; $i++) {
//   $sku = parsePartNumber($desiredData[$i][$partname]);
//   # for the case of two or more skus in one cell
//   $counter = 0;
//   do {
//     try { //$sku[$counter]
//       $res = $prclient->get('LOGPART(\'' . $sku[$counter] . '\')');
//       $res = json_decode($res,true);
//       # 1. There is match between prices
//       if ($res[$api_price] == $desiredData[$i][$price]) {
//         $price_status = $i+1 . ". SKU " . $res[$api_partname] . ": unit price is OK\n";
//         echo $msg;
//         $resultData[] = array($res[$api_partname], $msg);
//       # 2. No price for the sku in the excel file
//       } else {
//         if ($desiredData[$i][$price] == "") {
//           $desiredData[$i][$price] = "NOT INCLUDED";
//         }
//         $msg = $i+1 . ". SKU " . $res[$api_partname] . ": unit price is NOT OK - CSV unit price is: " . $desiredData[$i][$price] . " and Priority unit price is: " . $res[$api_price] . "\n";
//         echo $msg;
//         $resultData[] = array($res[$api_partname], $msg);
//       }
//     } catch (Exception $e) {
//       $msg = $i+1 . ". SKU " . $desiredData[$i][$partname] . ": Customer Part Number not found in SolidRun Priority\n";
//       echo $msg;
//       $resultData[] = array($desiredData[$i][$partname], $msg);
//     }
//
//     $counter++;
//   } while ($counter < count($sku));
// }
//
// file_put_contents('amit_result.txt', print_r($resultData, true));

################# check single sku ###################

# required fields as written in Priority API
// $api_price = "SECONDPRICE";
// $api_partname = "PARTNAME";
//
// $sku = "'SRT6442W00D01GE008V11I0'";
// $res = $prclient->get('LOGPART(\'' . $sku . '\')');
// $res = $prclient->get('ZUC_FULLPARTTREE(\'' . $sku . '\')');
// $res = $prclient->get('ZUC_FULLPARTTREE?$filter=PARTNAME%20eq%20' . $sku);
// $res = json_decode($res,true);
//echo "\nPart SKU: " . $res[$api_partname] . "; Part Price: " . $res[$api_price] . "\n";

// print_r($res);
// print_r(count($res['value']));
// echo "\n";
//print_r($res['value'][112]['RATIO']);
// $new_res = make_ch_array($res['value']);
// print_r($new_res);

// $partname = "Customer Part Number";
// $price = "Unit Price based on Sales Currency\nVolume 1";
// $parent_sku_1 = "SRHBCTCV12";
// $parent_sku_2 = "SRT6442W00D01GE008V11I0_01";
// $parent_sku_3 = "TCMP8QDW00D01GE008T01I0";
//
//
// $cols_array = array($partname, $price, $parent_sku_1, $parent_sku_2, $parent_sku_3);
//
//
// $variableNames = array(
//     "Customer Part Number",
//     "Unit Price based on Sales Currency\nVolume 1",
//     "SRHBCTCV12",
//     "SRT6442W00D01GE008V11I0_01",
//     "TCMP8QDW00D01GE008T01I0"
// );
//
// $variables = array();
//
// foreach ($variableNames as $name) {
//     $variables[$name] = null;
// }
//
// print_r($variables);


################# check single sku ###################

die();

function findCols_v2($data, $partname, $price) {
  $index_array = array();
  $foundRemarks = false;
  // $partname_col = $price_col = false;
  $foundPartname = $foundPrice = false;
  foreach ($data as $subArr) {
    $subArr = (array) $subArr; // convert object to array
    foreach ($subArr as $key => $value) {
      // If 'Remarks\n' was found, add key-value pairs to the result
      if ($foundRemarks)
        $index_array[$value] = $key;
      // searching "Customer Part Number"
      if ($value === $partname) {
        $index_array[$partname] = $key;
        $foundPartname = true;
      }
      // searching "Unit Price based on Sales Currency" which is a partial name
      if (stripos($value, $price) !== false) {
        $index_array[$price] = $key;
        $foundPrice = true;
      }
      // searching "Remarks" which could be with \n
      if (stripos($value, "Remarks") !== false)
        $foundRemarks = true;
    }
    if ($foundPartname && $foundPrice && $foundRemarks) {
      return $index_array;
    }
  }
  return;
}

function check_bom_sku($partname, $price, $sku_excel_array, $bom_skus_mat, $sku) {
  // $msg = "\tQuantity per BOM for the following SKU's:\n";
  foreach ($sku_excel_array as $bom_excel_sku => $excel_val) {
    if ($bom_excel_sku != $partname && $bom_excel_sku != $price) { // not name or price
      if (!empty($bom_skus_mat[$bom_excel_sku][$sku])) { // if it's on priority it's not empty
        $api_val = $bom_skus_mat[$bom_excel_sku][$sku];
        if ($api_val == $excel_val) // case 1: exist and equal - Status: OK
          $msg = "OK";
        else { // case 2: exist and not equal - Status: NOT OK
          if ($excel_val == "")
            $excel_val = "0";
          $msg = "NOT OK";
        }
      }
      else { // The part number sku not related to bom sku
        $api_val = "Not in priority";
        if ($excel_val == "") { // case 3: not exist in priority and not in excel
          $excel_val = "Not in excel";
          $msg = "OK";
        }
        else // case 4: not exist in priority but there is qty in excel
          $msg = "NOT OK";
      }
      $sku_msgs[$bom_excel_sku . " - quote"] = $excel_val;
      $sku_msgs[$bom_excel_sku . " - priority"] = $api_val;
      $sku_msgs[$bom_excel_sku . " - status"] = $msg;
    }
  }
  return $sku_msgs;
}

function findCols($handle, $partname, $price) {
  // Skip the first 6 rows to get to the row with headers
  for ($i = 1; $i <= 7; $i++) {
    $headerRow = fgetcsv($handle, 100000, ",");
    // print_r(count($headerRow));
  }

  // $headerRow = fgetcsv($handle, 16383, ",");
  $headerIndexes = array_flip($headerRow);
  // print_r($headerIndexes);
  // die();
  $cols = array();

  if (isset($headerIndexes[$partname]) && isset($headerIndexes[$price])) {
    $cols[$partname] = $headerIndexes[$partname];
    $cols[$price] = $headerIndexes[$price];
  }
  else { // if partname col or price call doesn't exist go back
    echo "something wrong with names or locations\n";
    return;
  }

  foreach ($headerRow as $index => $value) {
    // echo $index . " and " . $value . "\n";
    if ($value == "Remarks" || $value == "Remarks\n") {
      $i = $index + 1;
      while (!empty($headerRow[$i])) {
        $cols[$headerRow[$i]] = $i;
        $i++;
      }
      return $cols;
    }
  }
  echo "something wrong with 'Remarks' name or location";
  return;
}

// function findCols($handle) {
//   // find required cols according to Amit
//   // if they are not found something wrong with the excel file
//   $sku_header = "Customer Part Number";
//   $unit_price_header = "Unit Price based on Sales Currency\nVolume 1";
//   $sku_col = null;
//   $unit_price_col = null;
//   while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//     $num = count($data);
//     for ($c=0; $c<$num; $c++) {
//       if ($data[$c] == $sku_header){
//         $sku_col=$c;
//         echo "sku header column number is: " . $sku_col . "<br />\n";
//       }
//       else if ($data[$c] == $unit_price_header){
//         $unit_price_col=$c;
//         echo "unit price header column number is: " . $unit_price_col . "<br />\n";
//       }
//       if (!is_null($sku_col) && !is_null($unit_price_col)) {
//         return array($sku_col, $unit_price_col);
//       }
//     }
//   }
//   return false;
// }

function parsePartNumber($inputString) {
  // Check if the input string contains the delimiter "_x000D_\n"
  // echo $inputString . "\n";
  $delim = "_x000D_\n";
  if (strpos($inputString, $delim) !== false) {
      // Split the input string based on the delimiter
      $substrings = explode($delim, $inputString);
      return $substrings;
  } elseif (strpos($inputString, "\n") !== false) {
    // Split the input string based on "\n"
    $substrings = explode("\n", $inputString);
    return $substrings;
  } else {
      // If there is no delimiter, return the string as a single-element array
      return array($inputString);
  }
}

function fix_errors_check_valid($skuName, $prclient) {
  // this function trims spaces and special chars
  // also it checks if the sku is found in priority
  $new_sku = trim($skuName);
  // Check if the sku name contains "_"
  if (strpos($new_sku, "_") !== false) {
    // Split the input string and check first part
    $substrings = explode("_", $new_sku);
    $new_sku = urlencode($substrings[0]);
  } else
    $new_sku = urlencode($new_sku);

  $res = $prclient->get('PART(\'' . $new_sku . '\')');
  $res = json_decode($res,true);
  $api_parttree = "ZUC_PARTTREE";
  if (array_key_exists("error",$res))
    return "BOM: No such SKU";
  if ($res[$api_parttree] != "Y") // can be "N" or empty
    return "Not checked in part tree";
  return $new_sku;
}

// %20 --- space
// %2B --- +
// %27 --- '
// מחולל מסכים -> עמודת שם מסך איי פי איי לדוגמא לןגפארט
 ?>
