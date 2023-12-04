<?php
/*
   Script to activate nodejs script to read content of XLSX file and return it.
   In order to run the script, please provide FULL PATH of xlsx file as argument
   e.g: php getXlData.php /home/solidrun/public_html/wcapi/nodeScripts/exame.xlsx
*/
if($argc !== 2){
  echo "Missing params\n";
  die();
}

$nodeScript = __DIR__ . "/xlReader.js";
$xlFile = $argv[1];//"example.xlsx"

if (!file_exists($xlFile) || !is_file($xlFile)) {
    echo "The file $xlFile does not exists - abort\n";
    die();
}

// Run the Node.js script using shell_exec
$output = shell_exec("node $nodeScript $xlFile");
print_r($output);
