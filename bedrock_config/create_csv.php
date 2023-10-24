<?php
$allArrays = array();
$allArrays["CPU"] = array(
  "values" => ["Ryzen V3C48 8C/16T/45W", "Ryzen V3C18I 8C/16T/15W"],
  "codes" => array (
    "Ryzen V3C48 8C/16T/45W" => "CPU:V3C48",
    "Ryzen V3C18I 8C/16T/15W" => "CPU:V3C18I"
  )
);

// $allArrays["RAM"] = ["No RAM", "8 GB (1x 8 GB DDR5)", "16 GB (2x 8 GB DDR5)", "32 GB (2x 16 GB DDR5)", "32 GB ECC (2x 16 GB DDR5 ECC)", "64 GB (2x 32 GB DDR5)"];

// $allArrays["Main Storage"] = array(
//   "values" => ["No main storage", "960 GB Micron 7450 with PLP", "1 TB Samsung EVO 980 Pro", "256 GB ADATA 710", "512 GB ADATA 710", "1 TB ADATA 710", "2 TB ADATA 710"],
//   "codes" => array(
//     "No main storage" => "NV0:NO",
//     "960 GB Micron 7450 with PLP" => "NV0:1TPLP",
//     "1 TB Samsung EVO 980 Pro" => "NV0:1T",
//     "256 GB ADATA 710" => "NV0:256GEN3",
//     "512 GB ADATA 710" => "NV0:512GEN3",
//     "1 TB ADATA 710" => "NV0:1TGEN3",
//     "2 TB ADATA 710" => "NV0:2TGEN3"
//   )
// );
// $allArrays["OS"] = array(
//   "values" => ["No OS installed", "Ubuntu Linux", "Windows 11 Pro", "NA"],
//   "codes" => array(
//     "No OS installed" => "OS:NO",
//     "Ubuntu Linux" => "OS:UBU",
//     "Windows 11 IOT" => "OS:WIN11P",
//     "NA" => "OS:NA"
//   )
// );
$allArrays["NIO"] = array(
  "values" => ["NIO V3000 basic", "NIO V3000 minimal"],
  "codes" => array(
    "NIO V3000 basic" => "NIO:V3B",
    "NIO V3000 minimal" => "NIO:V3MIN"
  )
);
$allArrays["SX"] = array(
  "values" => ["No SX", "SX 4M2"],
  "codes" => array(
    "No SX" => "SX:NO",
    "SX 4M2" => "SX:4M2"
  )
);
$allArrays["SX Storage 1"] = array(
  "values" => ["No storage", "1 TB Samsung EVO 980 Pro", "1 TB ADATA 710", "2 TB ADATA 710", "NA"],
  "codes" => array(
    "No storage" => "NV1:NO",
    "1 TB Samsung EVO 980 Pro" => "NV1:1T",
    "1 TB ADATA 710" => "NV1:1TGEN3",
    "2 TB ADATA 710" => "NV1:2TGEN3",
    "NA" => "NV1:NA"
  )
);
$allArrays["SX Storage 2"] = array(
  "values" => ["No storage", "1 TB Samsung EVO 980 Pro", "1 TB ADATA 710", "2 TB ADATA 710", "NA"],
  "codes" => array(
    "No storage" => "NV2:NO",
    "1 TB Samsung EVO 980 Pro" => "NV2:1T",
    "1 TB ADATA 710" => "NV2:1TGEN3",
    "2 TB ADATA 710" => "NV2:2TGEN3",
    "NA" => "NV2:NA"
  )
);
$allArrays["WiFi"] = array(
  "values" => ["No WiFi", "Customer's WiFi", "WiFi", "NA"],
  "codes" => array(
    "No WiFi" => "WIFI:NO",
    "Customer's WiFi" => "WIFI:CUST",
    "WiFi" => "WIFI:AX210",
    "NA" => "WIFI:NA"
  )
);
$allArrays["Modem"] = array(
  "values" => ["No Modem", "Customer's Modem", "LTE cat 4 Quectel EM05G", "LTE cat 12 Quectel EM12G", "5G Quectel RM520N", "NA"],
  "codes" => array(
    "No Modem" => "MODEM:NO",
    "Customer's Modem" => "MODEM:CUST",
    "LTE cat 4 Quectel EM05G" => "MODEM:CAT4",
    "LTE cat 12 Quectel EM12G" => "MODEM:CAT12",
    "5G Quectel RM520N" => "MODEM:5G",
    "NA" => "MODEM:NA"
  )
);

// $allArrays["PM"] = ["No PM", "PM 1260"];
// $allArrays["DCCON"] = ["phoenix terminal"];

$allArrays["Enclosure"] = array(
  "values" => ["With enclosure", "Without enclosure"],
  "codes" => array(
    "With enclosure" => "ENC:YES",
    "Without enclosure" => "ENC:NO"
  )
);
$allArrays["Walls"] = array(
  "values" => ["Tile", "60W", "30W", "NA"],
  "codes" => array(
    "Tile" => "EWALL:TILE",
    "60W" => "EWALL:60W",
    "30W" => "EWALL:30W",
    "NA" => "EWALL:NA"
  )
);
$allArrays["Front Panel"] = array(
  "values" => ["V3000 basic", "NA"], // ["V3000 basic", "V3000 minimal", "NA"],
  "codes" => array(
    "V3000 basic" => "EFRONT:V3BASIC",
    // "V3000 minimal" => "EFRONT:V3MINIMAL", // waiting for Irad to change his mind
    "NA" => "EFRONT:NA"
  )
);
$allArrays["Top Panel"] = array(
  "values" => ["Top panel with antennas", "Generic top panel", "Top with 4x SMA for modem", "NA"],
  "codes" => array(
    "Top panel with antennas" => "ETOP:4ANT",
    "Generic top panel" => "ETOP:GENERIC",
    "Top with 4x SMA for modem" => "ETOP:4XSMA",
    "NA" => "ETOP:NA"
  )
);
$allArrays["Rear Panel"] = array(
  "values" => ["Rear panel with SIM trays", "Generic rear panel", "NA"],
  "codes" => array(
    "Rear panel with SIM trays" => "EREAR:2SIM",
    "Generic rear panel" => "EREAR:GENERIC",
    "NA" => "EREAR:NA"
  )
);
$allArrays["Bottom Panel"] = array(
  "values" => ["Generic bottom panel", "NA"],
  "codes" => array(
    "Generic bottom panel" => "EBOTTOM:GENERIC",
    "NA" => "EBOTTOM:NA"
  )
);

// $allArrays["Temperature"] = ["0C to 70C", "-40C to 85C"];

$keys = array_keys($allArrays);

$headers = [];
foreach ($allArrays as $key => $value) {
    $headers[] = $key;              // For the actual value
    $headers[] = $key . " codes";  // For the code value
}

// Limit for each CSV file
$limitPerFile = 500000;

// Set up initial file and counter
$currentFileIndex = 3;
$currentLineCount = 0;

// Create a CSV file and write headers
$csvFile = fopen("bedrock_v3000_{$currentFileIndex}.csv", "w");
fputcsv($csvFile, $headers);

$resultData = generateCombinations($allArrays, [], $keys);

fclose($csvFile);


function generateCombinations($arrays, $currentCombination = [], $keys = [], $index = 0) {
  global $headers, $csvFile, $currentLineCount, $limitPerFile, $currentFileIndex;

  if ($index === count($keys)) {
  // If we've exceeded the limit for the current file, switch to the next one
    if ($currentLineCount >= $limitPerFile) {
      fclose($csvFile);
      $currentFileIndex++;
      $csvFile = fopen("bedrock_v3000_{$currentFileIndex}.csv", "w");
      fputcsv($csvFile, $headers);
      $currentLineCount = 0;
    }

    fputcsv($csvFile, $currentCombination);
    $currentLineCount++;

    return;
  }

  $currentKey = $keys[$index];

  foreach ($arrays[$currentKey]["values"] as $value) {
    // Get the corresponding code
    $currentCode = $arrays[$currentKey]["codes"][$value];

    // Apply your conditions here using keys
    if ($currentKey === "OS") {
      $main_storage = $currentCombination[array_search("Main Storage", $keys) * 2];
      if ($main_storage === "No main storage" && $value !== "NA") continue;
      if ($main_storage !== "No main storage" && $value === "NA") continue;
    }

    if ($currentKey === "SX Storage 1" || $currentKey === "SX Storage 2" || $currentKey === "WiFi" || $currentKey === "Modem") {
      $sx = $currentCombination[array_search("SX", $keys) * 2];
      if ($sx === "No SX" && $value !== "NA") continue;
      if ($sx !== "No SX" && $value === "NA") continue;
    }

    if ($currentKey === "Walls" || $currentKey === "Front Panel" || $currentKey === "Top Panel" || $currentKey === "Rear Panel" || $currentKey === "Bottom Panel") {
      // // NIO --- Front Panel
      // $nio = $currentCombination[array_search("NIO", $keys) * 2];
      // if ($nio === "NIO V3000 basic" && $value === "V3000 minimal") continue;
      // if ($nio === "NIO V3000 minimal" && $value === "V3000 basic") continue;

      // Enclosure --- Walls, Front Panel, Top Panel, Rear Panel, Bottom Panel
      $enclosure = $currentCombination[array_search("Enclosure", $keys) * 2];
      if ($enclosure === "Without enclosure" && $value !== "NA") continue;
      if ($enclosure !== "Without enclosure" && $value === "NA") continue;

      // Modem and Wifi and SX --- Top Panel
      $modem = $currentCombination[array_search("Modem", $keys) * 2];
      $wifi = $currentCombination[array_search("WiFi", $keys) * 2];
      $sx = $currentCombination[array_search("SX", $keys) * 2];
      if ($modem === "No Modem") {
        if ($wifi === "No WiFi" && ($value === "Top with 4x SMA for modem")) continue;
        if ($wifi !== "No WiFi" && $wifi !== "NA" && ($value === "Generic top panel" || $value === "Top with 4x SMA for modem")) continue;
      }
      if ($modem !== "No Modem" && $modem !== "NA" && ($value === "Generic top panel")) continue;
      if ($sx === "No SX" && ($value === "Top panel with antennas" || $value === "Top with 4x SMA for modem")) continue;

      // Modem and SX --- Rear Panel
      if (($modem === "No Modem" || $sx === "No SX") && ($value === "Rear panel with SIM trays")) continue;
      if ($modem !== "No Modem" && $modem !== "NA" && ($value === "Generic rear panel")) continue;

      // CPU --- Walls
      $cpu = $currentCombination[array_search("CPU", $keys) * 2];
      if ($cpu === "Ryzen V3C48 8C/16T/45W" && $value === "30W") continue;
      if ($cpu === "Ryzen V3C18I 8C/16T/15W" && $value === "60W") continue;
    }

    $newCombination = array_merge($currentCombination, [$value,$currentCode]);
    generateCombinations($arrays, $newCombination, $keys, $index + 1);
  }
}


?>
