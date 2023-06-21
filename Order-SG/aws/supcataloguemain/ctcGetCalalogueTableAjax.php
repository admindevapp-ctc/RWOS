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
$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();
$erp = ctc_get_session_erp();


//find cusno1 who sell for cusno2(customer login)

$ctcdb = new ctcdb();
$sql = "
SELECT distinct awscusmas.cusno1 
FROM awscusmas 
    join supawsexc on awscusmas.cusno1 = supawsexc.cusno1 and awscusmas.cusgrp = supawsexc.cusgrp 
        and awscusmas.Owner_Comp = supawsexc.Owner_Comp 
WHERE awscusmas.cusno2 = '$cusno' and awscusmas.Owner_Comp='$comp'";
$sth = $ctcdb->db->prepare($sql);
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$hasil = $result[0];

if ($hasil) {
    $cusno1 = strtoupper($hasil['cusno1']);
} else {
    $cusno1 = $cusno ;
}




$result = ctc_get_supcatalogue_table_forcus($catMaker, $modelName, $brandName, $modelCode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $suppliercode, $cusno1 );
// echo $result;

//Remapping for datatable
$output = [];
foreach ($result->data as $r) {
    //Prepare Quantity Column Start
    $orderno = $r['ordprtno'];
    $sell=0;
   // $lotsize = (int) ctc_get_sup_lotsize($orderno,$suppliercode)[0]['Lotsize'];
    $lotsize = $r['Lotsize'];
    if($lotsize == '-'){
        $lotsize = 0;
    }
    //$sellprice = (double) $r['SellPrice'];
    $sellprice = $r['stdprice'];
    if($sellprice == '-'){
        $sellprice = 0.00;
    }
    else{
        $sellprice = (double)$sellprice;
    }
    $quantityBox = '';
    $quantityButtonCart = '';
    // check price
    require('../../db/conn.inc');
    

    $qry1=" select * from supawsexc 
    join awscusmas on supawsexc.Owner_Comp = awscusmas.Owner_Comp and 
    supawsexc.cusno1 = awscusmas.cusno1 and  awscusmas.cusgrp = supawsexc.cusgrp 
    where supawsexc.prtno='". $r['ordprtno']."' and supawsexc.Owner_Comp='$comp' 
    and supawsexc.supcode = '". $r['supno']."'  and  awscusmas.cusno2 = '$cusno'";  // edit by CTC
	$qry1Result=mysqli_query($msqlcon,$qry1);
	if($stockArray = mysqli_fetch_array ($qry1Result)){
		$sell=$stockArray['sell'];
        if($stockArray['price'] != NULL)
        {
            $stdprice = $stockArray['price'];
            
        }
        else{
            $stdprice = '';
        }
    }
    else{
        $stdprice = '';
    }
   

    ////TODO Test query logic
        //not check qbm008pr
		// echo $lotsize.'<br>';
		// echo $r['StockQty'].'<br>';
		 // echo $sell.'<br>';
		// print_r($stockArray);
        if ((int)$lotsize === 0 || $sell == 0) {
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" type="text" value="0" disabled>';
            $quantityButtonCart = '<button type="button" class="btn btn-xs" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '" disabled style="background-color: gray">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
        } else {
            $quantityButtonCart = '<button type="button" class="btn btn-success btn-xs buttonAddToCart" data-id="' . $r['ID'] .  '|' . $r['supno'] . '"  value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
            $maxLot = 999 - (999 % $lotsize);
            $quantityBox = '<input class="touchspinQuantity form-control input-xs text-center" id="quantity' . $r['ID'] . '" type="text" value="0" name="quantity' . $r['ID'] . '"'
                    . ' data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '">';
        }
    //Prepare Quantity Column End
 // $stdprice = (double) ctc_get_stdprice($orderno)[0]['price'];
    $newThing = new stdClass();
    $newThing->CheckBox = '<input type="checkbox" />';
    $newThing->ID=$r['ID'];
    $newThing->CarMaker =  $r['CarMaker'];
    $newThing->BrandName =  $r['brand'];
    $newThing->ModelName = $r['ModelName'];
    $newThing->VinCode = $r['vincode'];
    $newThing->ModelCode = $r['ModelCode'];
    $newThing->EngineCode = $r['EngineCode'];
    $newThing->GenuinePartNo = $r['Cprtn'];
    $newThing->PartNo = $r['Prtno'];
    $newThing->CGPartNo = $r['Cgprtno'];
    $newThing->OrderPartNo = $r['ordprtno'];
    $newThing->PartName = $r['Prtnm'];
    $newThing->LotSize = (int)$lotsize;
    $newThing->StockQty = $r['StockQty'];
    $newThing->STDPrice = number_format($stdprice,2);
    $newThing->Picture = '<a href="../sup_catalogue/' . $r['PrtPic'] . '" class="img-rounded" data-lightbox="image-' . $r['ID'] . '" data-title="' . $newThing->OrderPartNo . '"><img onerror="this.style.display=\'none\'" src="sup_catalogue/' . $r['PrtPic'] . '" class="img-rounded" width="25px" height="25px"></a>';
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
//echo $result;
