<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
require_once('../../language/Lang_Lib.php');

ctc_checkuser_permission('../../login.php');

$Shipto = (string) filter_input(INPUT_POST, 'ShipNo');
//$result = ctc_get_supshoppingcart_table($Shipto);
$result = ctc_get_supshoppingcart('');
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



$htmlval = "";
$dataarray = [];

$supcheck = "";
$bodytable = "";
$header = " <div class='row'>"
    ."  <div class='col-md-12'>"
    ."      <table id='tablecatalog'  class='table table-bordered table-hover table-striped display' style='overflow-x: auto; width: 100%;' cellspacing='0'>"
    ."          <thead>"
    ."              <tr class='bg-maroon'>"
    ."                  <th class='text-center' style='width: 5%;'></th>"
    ."                  <th class='text-center' style='width: 5%;'>" .get_lng($_SESSION["lng"], "L0442") ."</th>"/*Supplier No*/ 
    ."                  <th class='text-center' style='width: 5%;'>" .get_lng($_SESSION["lng"], "L0452") ."</th>"/*Supplier Name*/ 
    ."                  <th class='text-center' style='width: 20%;'>" .get_lng($_SESSION["lng"], "L0378") ."</th>"/* Part Number */
    . "                  <th class='text-center' style='width: 10%;'>" .get_lng($_SESSION["lng"], "L0437") ."</th>"/* Model Code */ 
    ."                  <th class='text-center' style='width: 5%;'>" .get_lng($_SESSION["lng"], "L0379") ."</th>" /* Curr */
    ."                  <th class='text-center' style='width: 10%;'>" .get_lng($_SESSION["lng"], "L0380") ."</th>"/* Price */  
    ."                  <th class='text-center' style='width: 8%;'>" .get_lng($_SESSION["lng"], "L0381") ."</th>"/* Qty */  
    ."                  <th class='text-center' style='width: 10%;'>" .get_lng($_SESSION["lng"], "L0382") ."</th>"/* Amount */ 
    ."                  <th class='text-center' style='width: 32%;'>" .get_lng($_SESSION["lng"], "L0422") ."</th>" /* Error Message */ 
    ."               </tr>"
    ."           </thead>"
    ."           <tbody>";

    foreach($output as $value)
    {

            if ($supcheck != $value->Supno ){
                if($supcheck !=  ""){
                     $htmlval ."  </tbody></table><br/><br/>";
                }
                 //alert(duedate);
                 $htmlval . "<div class='col-md-12'><div class='col-md-3 arial11redbold'>Supplier : ". $value->Supno . "</div>"
                 .  "<div class='col-md-9'><div class='col-md-3 arial11redbold'>: </div>" 
                 .  "<div class='col-md-3'>"
                 . "<input name='requestDate" .$value->Supno  ."' class='form-control input-xs' id='requestDate" . $value->Supno ."' type='text' size='12' maxlength='12' style='width: 100%; background-color: white !important;' readonly>"
                 . "<input name='requestDate" .$value->Supno ."2' id='requestDate" .$value->Supno ."2' type='hidden'></div></div></div><br/><br/>";
                 
                 $bodytable = "<tr>"
                 . "<td>". $value->Checkbox  . "</td>"
                 . "<td>". $value->Supno  . "</td>"
                 . "<td>". $value->Supnm . "</td>"
                 . "<td>". $value->PartNumber . "</td>"
                 . "<td>". $value->ModelCode ."</td>"
                 . "<td><center>". $value->Currency. "</center></td>"
                 . "<td><center>". $value->Price . "</center></td>"
                 . "<td>". $value->Quantity. "</td>"
                 . "<td><center>". $value->Amount. "</center></td>"
                 . "<td>". $value->Error. "</td>"
                 . "</tr>";
                 $bodytable =  $header .$bodytable;


                 $supcheck = $value->Supno;
            }
            else{
                $bodytable = "<tr>"
                . "<td>". $value->Checkbox  . "</td>"
                . "<td>". $value->Supno  . "</td>"
                . "<td>". $value->Supnm . "</td>"
                . "<td>". $value->PartNumber . "</td>"
                . "<td>". $value->ModelCode ."</td>"
                . "<td><center>". $value->Currency. "</center></td>"
                . "<td><center>". $value->Price . "</center></td>"
                . "<td>". $value->Quantity. "</td>"
                . "<td><center>". $value->Amount. "</center></td>"
                . "<td>". $value->Error . "</td>"
                . "</tr>";
            }
            $htmlval =  $htmlval . $bodytable;

        
    }
    $htmlval = $htmlval ."   </div> </div>";

    $htmlval =$htmlval  . "</div> </div>";

echo $htmlval;
