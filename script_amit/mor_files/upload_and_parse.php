<?php

require_once( '../lib/prclient/PrClient.php' ); // priority api client
$config = include( '../lib/config.php' );
$targetDir = __DIR__ . "/results";
$nodeScript = $config["xlReaderPath"];
$expectedKey = $config["BIkey"];
// problematic skus - contains special chars
$prob_skus = array("TPS0001%2FMCH00202" => "TPS0001/MCH00202");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
  // Check if the key was provided in the form submission
  if (!empty($_POST["key"])) {
    $providedKey = $_POST["key"];
    // Verify if the provided key matches the expected key
    if ($providedKey !== $expectedKey) {
      // moves back to index.html - missing key
      header_transfer("wrongKey", null);
    }
  } else {
    // moves back to index.html - missing key
    header_transfer("missingKey", null);
  }

  $prclient = new PrClient($config['odataUrl'],$config['companyName'],$config['uname'],$config['pass'],$config['key']);
  $targetFileName = "uploaded_file.xlsx";
  $targetFile = $targetDir . '/' . $targetFileName;
  $uploadOk = true;
  $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

  // Check if the file is an Excel file
  if ($fileType != "xlsx" && $fileType != "xls") {
    // moves back to index.html - not an excel file
    header_transfer("notExcelFile", null);
  }

  // Check if the file was uploaded successfully
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
    // result name and location
    $timestamp = date("Y-m-d_H:i");
    $result_file = "amit_result_" . $timestamp;
    $full_path = $targetDir . '/' . $result_file . '.csv';
    $data = extractExcelData($nodeScript, $targetFile);

    $parsed_data = parseExcelData($data, $prclient);
    create_csv($parsed_data, $full_path);
    header_transfer("success", $result_file); // moves back to index.html - success
  } else
    header_transfer("uploadFail", null); // moves back to index.html - upload fail
} else
  header_transfer("missingFile", null); // moves back to index.html - missing file

die();

##################### functions #########################
function extractExcelData($nodeScript, $xlFile) {
  // Execute the second PHP script and capture its output
  exec("node $nodeScript $xlFile", $data, $returnCode);

  /* Check if the execution was successful */
  if ($returnCode === 0) {
      $data = json_decode($data[0]);
      return $data;
  } else {
      header_transfer("phpExecFail", null);
  }
}

function parseExcelData($data, $prclient) {
  global $prob_skus;
  # Priority API and related columns names
  $partname = "Customer Part Number";
  $api_partname = "PARTNAME";
  $price = "Unit Price based on Sales Currency"; // this is part of the actual name. The full name is 'Unit Price based on Sales Currency\nVolume 1'
  $api_price = "SECONDPRICE";

  $cols = findCols_v2($data, $partname, $price);
  if (empty($cols)) {
    header_transfer("noColumnsFound", null); // moves back to index.html - convert fail
  }

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
        $skuBomArr = array();
        $skuBomArr["sku_name"] = $fixedColName;
        foreach ($res['value'] as $skuBomData) {
          if (empty($skuBomArr[$skuBomData['PARTNAMECH']]))
            $skuBomArr[$skuBomData['PARTNAMECH']] = $skuBomData['RATIO'];
          else
            $skuBomArr[$skuBomData['PARTNAMECH']] += $skuBomData['RATIO'];
        }
        // used in the parsing of excel file with BOM sku's
        $bom_skus_mat[$fixedColName] = $skuBomArr;
        $cols_dic[$columnName] = $fixedColName;
      }
      else // $fixedColName == "BOM: No such SKU" || "Not checked in part tree"
        $not_valid_sku_bom[] = $fixedColName . ": " . $columnName;
    }
  }

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
            $rowData[$cols_dic[$columnName]] = $subArr[$columnIndex];
          } elseif (empty($subArr[$columnIndex]) && !$duplicate_sku) {
            $rowData[$cols_dic[$columnName]] = "";
          }
        }
      }
      $desiredData[$sku_name] = $rowData;
    }
  }

  $resultData = array();
  $resultData[] = $not_valid_sku_bom;
  foreach (array_slice($desiredData, 1) as $sku_excel_array) {
    if (empty($sku_excel_array[$price]))
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
        # case 2. Different price for the sku in the excel file
        else
          $price_status = "NOT OK";
        $price_prio = $res[$api_price];
      }
      // case 3. The sku doesn't exist so it's price also doesn't exist
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
  return $resultData;
}

function findCols_v2($data, $partname, $price) {
  $index_array = array();
  $foundRemarks = false;
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

function parsePartNumber($multi_sku) {
  // Check if the input string contains the delimiter "_x000D_\n"
  $delim = "_x000D_\n";
  if (strpos($multi_sku, $delim) !== false) {
    // Split the input string based on the delimiter
    $substrings = explode($delim, $multi_sku);
    return $substrings;
  } elseif (strpos($multi_sku, "\n") !== false) {
    // Split the input string based on "\n"
    $substrings = explode("\n", $multi_sku);
    return $substrings;
  } else // If there is no delimiter, return the string as a single-element array
    return array($multi_sku);
}

function check_bom_sku($partname, $price, $sku_excel_array, $bom_skus_mat, $sku) {
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

function create_csv($resultData, $csv_path) {
  // $serverPath = "/var/www/gilad_segal/excel_parsing/uploads";
  // Create a CSV file
  $csvFile = fopen($csv_path, "w");

  foreach ($resultData[0] as $index => $value) {
    $rowData = array($value);
    fputcsv($csvFile, $rowData);
  }

  // Add an empty row
  fputcsv($csvFile, array_fill(0, count($resultData[0]) + 1, ""));

  // create csv file with comparison to priority
  // Extract column headers from the keys in the first row
  $headers = array_keys($resultData[1]);
  array_unshift($headers, "Number");

  // write headers
  fputcsv($csvFile, $headers);

  // Loop through each row and write values to the CSV file
  foreach (array_slice($resultData, 1) as $index => $row) {
      $rowData = array_values($row);
      array_unshift($rowData, $index + 1); // Add the row number
      fputcsv($csvFile, $rowData);
  }
  fclose($csvFile);
  // exec("cp $csv_path $serverPath");
}

function header_transfer($state, $csvName) {
  // moves back to index.html according to state
  $location = "Location: https://solidrundev.com/wcapi/updateDoc/excel_parse.html?state=" . $state . "&csvName=" . $csvName;
  header($location, true, 301);
  exit();
}
?>
