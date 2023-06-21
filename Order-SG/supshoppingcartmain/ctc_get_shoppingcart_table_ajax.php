<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
require_once('../../language/Lang_Lib.php');

ctc_checkuser_permission('../../login.php');

$Shipto = (string) filter_input(INPUT_POST, 'ShipNo');
$result = ctc_get_supshoppingcart_table($Shipto);

//Remapping for datatable
$output = [];
$html = "";
foreach ($result as $r) {
    //Prepare Quantity Column Start


    $price = (double) $r['price'];
    $lotsize = (int) $r['Lotsize'];
    $quantity = $r['qty'];
    $quantityBox = '';
    if ($lotsize === 0) {
        $quantityBox = '<input class="touchspinQuantity form-control input-xs" type="text" value="' . $quantity . '" disabled>'; /*ChangeCode - by CTC Sippavit 30/09/2020 */
    } else {
        $disabled = '';
        if ($price === 0.00) {
            $disabled = 'disabled';
//            $quantity = 0;  // /*ChangeCode-Comment by CTC Sippavit 30/09/2020 */
            $lotsize = $quantity; /*ChangeCode - by CTC Sippavit 30/09/2020 */
            $maxLot = $quantity; /*ChangeCode - by CTC Sippavit 30/09/2020 */
        } else {
            $maxLot = 999 - (999 % $lotsize);
        }
        $quantityBox = '<input class="touchspinQuantity form-control text-right input-xs" id="quantity' . $r['ID'] . '" type="text" value="' . $quantity . '" name="quantity' . $r['ID'] . '"'
                . ' data-price="' . $price . '" data-cur="' . $r['CurCD'] . '" data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '" ' . $disabled . '>';
    }
    $errorMessage = '';
    if ($price === 0.00) {
        $errorMessage = '<span class="text-danger error-shipto">' . str_replace('{shipto}', $Shipto, get_lng($_SESSION["lng"], "E0054"))/* Price not found for part and ship to '{shipto}' */ . '</span>';
    }
    //Prepare Quantity Column End
    $newClass = new stdClass();
    $newClass->Supno = $r['supno'];
    $newClass->Supnm = $r['supname'];
    $newClass->Checkbox = '<input type="checkbox" class="group-check" value="' . $r['ID'] . '" style=""/>';
    $newClass->PartNumber = $r['PartNumber'];
    $newClass->SupplierCode = $r['supno'];
    $newClass->SupplierName = $r['supname'];
    $newClass->ModelCode = $r['ModelCode'];
    $newClass->Currency = $r['curr'];
    $newClass->Price = number_format($price,2);
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
