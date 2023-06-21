<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');
require_once('../../../language/Lang_Lib.php');

ctc_checkuser_permission('../../../login.php');

$catMaker = (string) filter_input(INPUT_POST, 'CatMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$brandName = (string) filter_input(INPUT_POST, 'BrandName');
$modelCode = (string) filter_input(INPUT_POST, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_POST, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_POST, 'SubModelName');
$subGroup = (string) filter_input(INPUT_POST, 'SubGroup');
$search = (string) filter_input(INPUT_POST, 'SearchTable');
$order = (int) $_REQUEST['order'][0]['column'];
$orderType = (string) $_REQUEST['order'][0]['dir'];
$start = (int) filter_input(INPUT_POST, 'start');
$length = (int) filter_input(INPUT_POST, 'length');
$suppliercode = (string) filter_input(INPUT_POST, 'Supplier');

$result = ctc_get_supcatalogue_table($catMaker, $modelName, $brandName, $modelCode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $suppliercode);
$erp = ctc_get_session_erp();
$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();

//Remapping for datatable
$output = [];
foreach ($result->data as $r) {
    //Prepare Quantity Column Start
    $orderno = $r['ordprtno'];
    //$lotsize = (int) ctc_get_sup_lotsize($orderno,$suppliercode)[0]['Lotsize'];
    $lotsize = (int)$r['Lotsize'];
    $stdprice = (double) $r['stdprice'];
    $quantityBox = '';
    $quantityButtonCart = '';
    
        //not check qbm008pr
        if ($lotsize === 0 || $stdprice === 0.00) {
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" type="text" value="0" disabled>';
            $quantityButtonCart = '<button type="button" class="btn btn-xs" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '" disabled style="background-color: gray">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
        } else {
            $quantityButtonCart = '<button type="button" class="btn btn-success btn-xs buttonAddToCart" data-id="' . $r['ID'] . '" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
            $maxLot = 999 - (999 % $lotsize);
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" id="quantity' . $r['ID'] . '" type="text" value="0" name="quantity' . $r['ID'] . '"'
                    . ' data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '">';
        }
    //Prepare Quantity Column End

   // $stdprice = (double) ctc_get_stdprice($orderno)[0]['price'];
    $newThing = new stdClass();
    $newThing->ID = $r['ID']."|". $r['Cusno'];
    $newThing->CheckBox = '<input type="checkbox" />';
    $newThing->CarMaker = $r['CarMaker'];
    $newThing->BrandName = $r['brand'];
    $newThing->ModelName = $r['ModelName'];
    $newThing->VinCode = $r['vincode'];
    $newThing->ModelCode = $r['ModelCode'];
    $newThing->EngineCode = $r['EngineCode'];
    $newThing->GenuinePartNo = $r['Cprtn'];
    $newThing->PartNo = $r['Prtno'];
    $newThing->CGPartNo = $r['Cgprtno'];
    $newThing->OrderPartNo = $r['ordprtno'];
    $newThing->PartName = $r['Prtnm'];
    $newThing->LotSize = $lotsize;
    $newThing->StockQty = $r['StockQty'];
    $newThing->STDPrice ="<span class='arial11'>" . $r['stdprice']  . "</span><br/><span style='font-size: 9px;'>" .$r['Cusno'] . "</span>";
    $newThing->Picture = '<a href="../sup_catalogue/' . $r['PrtPic'] . '" class="img-rounded" data-lightbox="image-' . $r['ID'] . '" data-title="' . $newThing->OrderPartNo . '"><img onerror="this.style.display=\'none\'" src="../sup_catalogue/' . $r['PrtPic'] . '" class="img-rounded" width="25px" height="25px"></a>';
    $newThing->Detail = '<button type="button" class="btn btn-link btn-sm button-datail"  data-cusno="'. $r['Cusno'] .'" data-lot-size="' . $lotsize . '" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0430")/* Details */ . '"><span class="glyphicon glyphicon-list-alt"></span></button>';
    
    $output[] = $newThing;
}

$result->data = $output;

echo json_encode($result);

