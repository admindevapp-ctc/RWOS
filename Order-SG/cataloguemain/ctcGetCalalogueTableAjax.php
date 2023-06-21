<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
require_once('../../language/Lang_Lib.php');

ctc_checkuser_permission('../../login.php');

$catMaker = (string) filter_input(INPUT_POST, 'CatMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$modelCode = (string) filter_input(INPUT_POST, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_POST, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_POST, 'SubModelName');
$subGroup = (string) filter_input(INPUT_POST, 'SubGroup');
$search = (string) filter_input(INPUT_POST, 'SearchTable');
$subbrandName = (string) filter_input(INPUT_POST, 'Brand');
$order = (int) $_REQUEST['order'][0]['column'];
$orderType = (string) $_REQUEST['order'][0]['dir'];
$start = (int) filter_input(INPUT_POST, 'start');
$length = (int) filter_input(INPUT_POST, 'length');
$suppliercode = (string) filter_input(INPUT_POST, 'Supplier');

$result = ctc_get_catalogue_table_cus($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length,$subbrandName, $suppliercode, 'c');
$erp = ctc_get_session_erp();
$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();

//Remapping for datatable
$output = [];
foreach ($result->data as $r) {
    //Prepare Quantity Column Start
    $orderno = $r['ordprtno'];
    $lotsize = (int) ctc_get_lotsize($orderno)[0]['Lotsize'];
    //$sellprice = (double) $r['SellPrice'];
    //$lotsize = (int) $r['Stock'];
    $sellprice = (double) $r['stdprice'];
    
    $quantityBox = '';
    $quantityButtonCart = '';
	require('../db/conn.inc');
    $qry1="select * from availablestock where prtno='".$orderno."' and Owner_Comp='$comp' ";  // edit by CTC
	$qry1Result=mysqli_query($msqlcon,$qry1);
	if($stockArray = mysqli_fetch_array ($qry1Result)){
	    $stockqty=$stockArray['qty'];
		if($stockqty >= $qty){
			$msg= get_lng($_SESSION["lng"], "L0288")/*Yes"*/;
		}
		else{
			$msg= get_lng($_SESSION["lng"], "L0289")/*"No"*/;
		}
	}
	else{
		$qry2="select * from hd100pr where prtno='".$orderno."' and (l1awqy+l2awqy)>0 ";
		$qry2Result = mysqli_query($msqlcon,$qry2);
		$count2 = mysqli_num_rows($qry2Result);
		if($count2>0){
			$msg= get_lng($_SESSION["lng"], "L0288")/*"Yes"*/;
		} else{
			$msg= get_lng($_SESSION["lng"], "L0289")/*"No"*/;
		}
			
	}

    ////TODO Test query logic
    if ($erp == '0') {
        //check qbm008pr
        if (ctc_get_available_qbm008pr_count($orderno) > 0) {
            if ($lotsize === 0 || $sellprice === 0.00) {
                $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" type="text" value="0" disabled>';
                $quantityButtonCart = '<button type="button" class="btn btn-xs" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '" disabled style="background-color: gray">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
            } else {
                $quantityButtonCart = '<button type="button" class="btn btn-success btn-xs buttonAddToCart" data-id="' . $r['ID'] . '" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
                $maxLot = 999 - (999 % $lotsize);
                $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" id="quantity' . $r['ID'] . '" type="text" value="0" name="quantity' . $r['ID'] . '"'
                        . ' data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '">';
            }
        } else {
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" type="text" value="0" disabled>';
            $quantityButtonCart = '<button type="button" class="btn btn-xs" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '" disabled style="background-color: gray">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
        }
    } else {
        //not check qbm008pr
        if ($lotsize === 0 || $sellprice === 0.00) {
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" type="text" value="0" disabled>';
            $quantityButtonCart = '<button type="button" class="btn btn-xs" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '" disabled style="background-color: gray">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
        } else {
            $quantityButtonCart = '<button type="button" class="btn btn-success btn-xs buttonAddToCart" data-id="' . $r['ID'] . '" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
            $maxLot = 999 - (999 % $lotsize);
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" id="quantity' . $r['ID'] . '" type="text" value="0" name="quantity' . $r['ID'] . '"'
                    . ' data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '">';
        }
    }
    //Prepare Quantity Column End

    $newThing = new stdClass();
    $newThing->CheckBox = '<input type="checkbox" />';
    $newThing->ID = $r['ID'];
    $newThing->CarMaker = $r['CarMaker'];
    $newThing->BrandName = $r['Brand'];
    $newThing->ModelName = $r['ModelName'];
    $newThing->VinCode = $r['Vincode'];
    $newThing->ModelCode = $r['ModelCode'];
    $newThing->EngineCode = $r['EngineCode'];
    $newThing->GenuinePartNo = $r['Cprtn'];
    $newThing->PartNo = $r['Prtno'];
    $newThing->CGPartNo = $r['Cgprtno'];
    $newThing->OrderPartNo = $r['ordprtno'];
    $newThing->PartName = $r['Prtnm'];
    $newThing->LotSize = $lotsize;
    $newThing->StockQty = $msg;
    $newThing->STDPrice = $r['stdprice'];
    $newThing->Picture = '<a href="user_images/' . $r['PrtPic'] . '" class="img-rounded" data-lightbox="image-' . $r['ID'] . '" data-title="' . $newThing->OrderPartNo . '"><img onerror="this.style.display=\'none\'" src="user_images/' . $r['PrtPic'] . '" class="img-rounded" width="25px" height="25px"></a>';
    $newThing->Detail = '<button type="button" class="btn btn-link btn-sm button-datail" data-lot-size="' . $lotsize . '" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0430")/* Details */ . '"><span class="glyphicon glyphicon-list-alt"></span></button>';
    
    $newThing->Quantity = '<div class="input-group">'
            . $quantityBox
            . '<div class="input-group-btn">'
            . $quantityButtonCart
            . '</div>'
            . '</div>';
    $output[] = $newThing;
}

$result->data = $output;

echo json_encode($result);

