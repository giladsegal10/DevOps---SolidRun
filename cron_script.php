<?php
require_once( '/home/gilad/Documents/php_work/gilad/PrClient.php' ); // priority api client
$config  = include('giladConfig.php');
$prclient = new PrClient($config['odataUrl'],$config['companyName'],$config['uname'],$config['pass'],$config['key']);

$api_type = 'TYPE';
$api_partname = 'PARTNAME';
$api_sonname = 'SONNAME';
$api_sonquant = 'SONQUANT';
$api_zuc_son = 'PARTNAMECH';
$api_zuc_sonqunt = 'RATIO';

$query = 'PART?$since=2023-10-02T01:15:00%2B02:00&$select=PARTNAME,TYPE';
$res = $prclient->get($query);
$res = json_decode($res,true);


if (!empty($res['value'])) {
  $changed_skus = $res['value'];
  foreach ($changed_skus as $data) {
    $father_sku = $data[$api_partname];
    $sons = array();
    if ($data[$api_type] == "P") {
      $sons[] = getAllSons($father_sku, $prclient);
      $query = 'ZUC_FULLPARTTREE?$filter=PARTNAME%20eq%20%27' . $father_sku . '%27&$select=RUNDATE,PARTNAME,PARTNAMECH,RATIO';
      $res = $prclient->get($query);
      $res = json_decode($res,true);
      if (!empty($sons[0]) && !empty($res["value"])) {
        $part_sons = array();
        $part_sons = sumup_duplicate_skus($sons[0], $api_sonname, $api_sonquant);
        $zuc_sons = array();
        $zuc_sons = sumup_duplicate_skus($res["value"], $api_zuc_son, $api_zuc_sonqunt);
        $mismatch_arr = array();
        $mismatch_arr = find_mismatches($part_sons, $zuc_sons, $api_sonquant, $api_zuc_sonqunt);
        echo "########## father sku - $father_sku ##########\n";
        if (!empty($mismatch_arr)) {
          print_r($mismatch_arr);
          echo "\n";
        }
        else
          echo "All sons in ZUC_FULLPARTTREE and PART are identical\n\n";
      }
      elseif (!empty($sons[0]) && empty($res["value"]))
        echo "father sku $father_sku isn't on ZUC_FULLPARTTREE but is on PART\n\n";
    }
  }
} else
  echo "No sku has changed in the last 24 hours\n";

// print_r($p_data);


#################### cheking one father sku ####################

// $sku="SRV2L-EVKHBP-R01";
// $sons[] = getAllSons($sku, $prclient);
// $part_sons = array();
// $part_sons = sumup_duplicate_skus($sons[0], $api_sonname, $api_sonquant);

// ##### function replace #####
// foreach ($sons[0] as $value) {
//   $sonname = $value[$api_sonname];
//   if (!array_key_exists($sonname, $part_sons))
//     $part_sons[$sonname] = $value;
//   else
//     $part_sons[$sonname][$api_sonquant] += $value[$api_sonquant];
// }

// print_r($part_sons);
// print_r(count($part_sons));
// echo "\n";



// $sku = "SRV2L-EVKHBP-R01"; //  SRV2L-EVKHBP-R01 try tommorow!
// $query = 'ZUC_FULLPARTTREE?$filter=PARTNAME%20eq%20%27' . $sku . '%27&$select=RUNDATE,PARTNAME,PARTNAMECH,RATIO';
// $res = $prclient->get($query);
// $res = json_decode($res,true);
// $zuc_sons = array();
// $zuc_sons = sumup_duplicate_skus($res["value"], $api_zuc_son, $api_zuc_sonqunt);

// ##### function replace #####
// foreach ($res["value"] as $value) {
//   $sonname = $value[$api_zuc_son];
//   if (!array_key_exists($sonname, $zuc_sons))
//     $zuc_sons[$sonname] = $value;
//   else
//     $zuc_sons[$sonname][$api_zuc_sonqunt] += $value[$api_zuc_sonqunt];
// }

// print_r($zuc_sons);
// print_r(count($zuc_sons));
// echo "\n";

# $part_sons: array from PART (recursive sons)
# $zuc_sons: array from ZUC_FULLPARTTREE
# checking $part_sons vs $zuc_sons



#################################### functions ####################################

function find_mismatches($part_sons, $zuc_sons, $api_sonquant, $api_zuc_sonqunt) {
  /*  function to find mismatches between sons in PART and sons in ZUC_FULLPARTTREE.
      3 cases:
        1. The sku exist in both arrays but the quantity is not equal.
        2. The sku appears in PART but not in ZUC_FULLPARTTREE.
        3. The sku appears in ZUC_FULLPARTTREE but not in PART.
  */
  $mismatch = array();
  foreach ($part_sons as $son => $data) {
    if (array_key_exists($son, $zuc_sons)) {
      $zuc_sonqunt = $zuc_sons[$son][$api_zuc_sonqunt];
      if ($data[$api_sonquant] != $zuc_sonqunt)
      $mismatch[$son] = "$son quantity in PART is $data[$api_sonquant] and
      quantity in ZUC_FULLPARTTREE is $zuc_sonqunt\n";
    }
    else # insert to mismatch sku's that in PART but not on ZUC_FULLPARTTREE
    $mismatch[$son] = "$son is in PART but not in ZUC_FULLPARTTREE\n";
  }
  $part_sons_keys = array_keys($part_sons);
  $zuc_sons_keys = array_keys($zuc_sons);
  # insert to mismatch sku's that in ZUC_FULLPARTTREE but not on PART
  foreach (array_diff($zuc_sons_keys, $part_sons_keys) as $value)
    $mismatch[$value] = "$value is in ZUC_FULLPARTTREE but not in PART\n";
  return $mismatch;
}


function sumup_duplicate_skus($arr, $name_api, $quan_api) {
  /*  function to sum up any duplicate sku (for cases of som and board).
      If the sku isn't in the new arr it is added.
      If the sku is in the new arr already, we sum up it's quantity
  */
  $new_arr = array();
  foreach ($arr as $value) {
    $sonname = $value[$name_api];
    if (!array_key_exists($sonname, $new_arr))
      $new_arr[$sonname] = $value;
    else
      $new_arr[$sonname][$quan_api] += $value[$quan_api];
  }
  return $new_arr;
}

function getAllSons($father_sku, $prclient) {
  /*  A recursive function that receives a 'P' type sku
      and query all his sons and saves them in a result array.
      the sons are 'R' or 'P' type: no matter the type, the son is
      saved in the array and the 'P' type is also called back with
      the recursive function.
      the stop condition is an empty array after api call - meaning no more sons.
  */

  $api_type = 'TYPE';
  $api_sonname = 'SONNAME';
  $complete_sons_arr = array();

  $query = 'PART(\'' . $father_sku . '\')/PARTARC_SUBFORM?$select=SONNAME,TYPE,SONQUANT';
  $res = $prclient->get($query);
  $res = json_decode($res,true);

  if (empty($res["value"])) // stop if no sons at all
    return;

  $results = $res['value'];
  foreach ($results as $data) {
    $complete_sons_arr[] = $data;
    if ($data[$api_type] == "P") {
      $current_sons = getAllSons($data[$api_sonname], $prclient);
      $complete_sons_arr = array_merge($complete_sons_arr, $current_sons);
    }
  }

  return $complete_sons_arr;
}

?>
