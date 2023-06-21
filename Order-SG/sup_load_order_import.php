<?php

session_start();
require_once('../core/ctc_init.php');
require_once('../core/ctc_permission.php');

ctc_checkuser_permission('../login.php');

$comp = ctc_get_session_comp(); // add by CTC
//$cusno = (string) filter_input(INPUT_POST, 'cusno');
$imptable = $_SESSION['imptable'];
$cusno = ctc_get_session_cusno();

$result = sup_get_ordertempimp($cusno, $imptable);
//Remapping for datatable

$output = [];	
foreach ($result as $r) {
    //Prepare Quantity Column Start
    $price = (double) $r['bprice'];
    $lotsize = (int) $r['LotSize'];
    $quantity = (int)  $r['qty'];
    $amount = (double)($price * $quantity );
   
    $newClass = new stdClass();
    $newClass->Supno = $r["supno"];
    $newClass->Supnm = $r["supname"];
    $newClass->Checkbox = '<input type="checkbox" class="group-check" value="' . $r['ID'] . '"/>';
    $newClass->PartNumber = $r['partno'];
    //$newClass->ModelCode = $r['ModelCode'];
    $newClass->Currency = $r['CurCD'];
    $newClass->Price = number_format($price,2);
    $newClass->Quantity = $quantity;
    $newClass->Amount = $amount;
    $newClass->Error = $r["partdes"];
    $output[] = $newClass;
}

$resultClass = new stdClass();
$resultClass->data = $output;

echo json_encode($resultClass);
