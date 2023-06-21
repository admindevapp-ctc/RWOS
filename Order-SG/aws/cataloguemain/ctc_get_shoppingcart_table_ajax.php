<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');
require_once('../../../language/Lang_Lib.php');

ctc_checkuser_permission('../../../login.php');

$Shipto = (string) filter_input(INPUT_POST, 'ShipNo');
$result = ctc_get_shoppingcart_cusno2($Shipto);
$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();
//Remapping for datatable
$output = [];
foreach ($result as $r) {
    //Prepare Quantity Column Start
    $price = (double) $r['Price'];
    $lotsize = (int) $r['Lotsize'];
    $quantity = $r['QTY'];
    $quantityBox = '';
	require('../../db/conn.inc');
     //check price
    // if($comp=="IN0"){
        // $qryprice="select Price as 'price' from sellprice 
        // where trim(Itnbr) = '".$r['PartNo']."'  and cusno= '$cusno' 
        // and Owner_Comp='$comp' limit 1 ";
    // }
    // else{
     $qryprice=" select * 
     from awsexc 
         join awscusmas on awsexc.Owner_Comp = awscusmas.Owner_Comp and awsexc.cusno1 = awscusmas.cusno1 and  awscusmas.cusgrp = awsexc.cusgrp 
     where itnbr='".$r['PartNo']."'  and awsexc.Owner_Comp='$comp'AND awscusmas.cusno2 = '$cusno'";
    // }
     $qrypriceResult = mysqli_query($msqlcon,$qryprice);
     $countprice = mysqli_num_rows($qrypriceResult);
     if($countprice>0){
        $priceArray = mysqli_fetch_array ($qrypriceResult);
        if($priceArray['price'] != NULL  ){
            $sellprice=number_format($priceArray['price'],2);
        }else{
            $sellprice = "";
         }

     } else{
        $sellprice = "";
     }

    if ($lotsize === 0) {
        $quantityBox = '<input class="touchspinQuantity form-control input-xs" type="text" value="' . $quantity . '" disabled>'; /*ChangeCode - by CTC Sippavit 30/09/2020 */
    } else {
        $disabled = '';
/*        if ($sellprice === "") {
            $disabled = 'disabled';
//            $quantity = 0;  // 
            $lotsize = $quantity; 
            $maxLot = $quantity; 
        } else {*/
            $maxLot = 999 - (999 % $lotsize);
      //  }
        $quantityBox = '<input class="touchspinQuantity form-control text-right input-xs" id="quantity' . $r['ID'] . '" type="text" value="' . $quantity . '" name="quantity' . $r['ID'] . '"'
                . ' data-price="' . $price . '" data-cur="' . $r['CurCD'] . '" data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '" ' . $disabled . '>';
    }
    $errorMessage = '';
    // if ($sellprice ===  "") {
    //    $errorMessage = '<span class="text-danger error-shipto">' . str_replace('{shipto}', $Shipto, get_lng($_SESSION["lng"], "E0054"))/* Price not found for part and ship to '{shipto}' */ . '</span>';
    //}
    //Prepare Quantity Column End
    $newClass = new stdClass();
    $newClass->Checkbox = '<input type="checkbox" class="group-check" value="' . $r['ID'] . '"/>';
    $newClass->PartNumber =$r['PartNumber'];
    $newClass->ModelCode = $r['ModelCode'];
    $newClass->Currency = $r['CurCD'];
    $newClass->Price =  $sellprice;
    $newClass->Quantity = '<div class="input-group">'
            . $quantityBox
            . '</div>';
    $newClass->Amount = '<span class="amount" id="amount' . $r['ID'] . '"></span>';
    $newClass->Error = $errorMessage;
    $output[] = $newClass;
}

$resultClass = new stdClass();
$resultClass->data = $output;

echo json_encode($resultClass);
