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

/*
  PART 1 - handling with the BOM sku's

  Since the cols array holds BOM skus names, and they can be wrriten wrong,
  the cols_dic will hold the correct names of those BOM skus (using fix_errors_check_valid function).
  If a BOM sku not exist, we insert it to $not_valid_sku_bom array.

  We go in a loop on all the columns names which are BOM sku and we fix them and check if they are valid.
  If they are valid, we GET their all sons hierarchy tree (from ZUC_FULLPARTTREE in priority) with
  their quantities. We save each BOM sku sons tree in $skuBomArr and each $skuBomArr insert to $bom_skus_mat
*/

// for not-existing BOM sku's
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
      $skuBomArr = array(); // array for each BOM sku with his sons and their respective quantities
      $skuBomArr["sku_name"] = $fixedColName;
      foreach ($res['value'] as $skuBomData) {
        // print_r($skuBomData);
        // the son can appear more then once so we add all the quantities
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


/*
  PART 2 - extracting relevant data from sku rows

  Going through the data of the excel (row after row) until we find the headres row (usually row 7)
  When we found it we mark it with a flag (foundItem) and we store the relevant cells according
  to the cols indexesץ

  We store every row data (sku data) inside $desiredData array.
*/
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



/*
  PART 3 - Preparing every row (sku data) in organized format

  In this step we check if the written sku names, prices and BOM quantities
  that are wrriten in the excecl are equal to the names prices and BOM quantities
  that are written in priority.

  We organize the data so each property has also 'status' of OK or NOT OK
*/
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
      // check if BOM quantities in excel are equal to those from priority ($bom_skus_mat)
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

die();
################# functions ###################


function findCols_v2($data, $partname, $price) {
  /*
    This function searches for the headers row (where "Customer Part Number" is)
    The BOM sku's changing from excel to excel, but their respective location is
    always after 'Remarks' header.

    So if we found 'Remarks' we mark it with a flag and save the header
    (which must be an BOM sku)

    We return the index_array so the key is the column name and
    the value is the index.
  */
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
  /*
    In this function we check, per sku row, if the BOM quantities that is written
    in his row are equal to those on priority ($bom_skus_mat).

    We arrange the data in array that fits the format to enter later to the csv.
    We return this array.
  */
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
      else { // The part number sku not related to bom sku - this sku is not a son to BOM sku
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

function parsePartNumber($inputString) {
  /*
    Check if this entry (excel cell) includes more them one sku name.
    It doing so by checking if the input string contains the delimiter "_x000D_\n".

    If so, it split it to the sku names and returns them as array.
    If not, then it is one sku name - it returns as array
  */
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
  /*
    this function trims spaces and special chars
    also it checks if the sku is found in priority
  */
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
?>
