<?php

session_start();

require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');



$catMaker = (string) filter_input(INPUT_GET, 'CatMaker');
$modelName = (string) filter_input(INPUT_GET, 'ModelName');
$modelCode = (string) filter_input(INPUT_GET, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_GET, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_GET, 'SubModelName');
$Brand = (string) filter_input(INPUT_GET, 'Brand');
$Supplier = (string) filter_input(INPUT_GET, 'Supplier');
$search = (string) filter_input(INPUT_GET, 'SearchTable');

$datas = [];
$result = ctc_get_supcatalogue($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName, $Brand, $Supplier, $search);

// Header
$header = ['Car Maker','Model Name', 'VIN Code','Model Code','Engine Code','CC.','Start Date','End Date','Genuine Part No.',
'Supplier Code','Supplier Genuine Part No.','Part Name','Picture Name','Product Brand','Order Part No.','Standard Price', 'Lot Size', 'Stock'
    , 'Stock/Non Stock Item','Remark'];

$datas[0] = $header;

// data
foreach ($result as $aRow) {
    $CarMkr = $aRow['CarMaker'];
    $MdlName = $aRow['ModelName'];
    $VinCode = $aRow['vincode'];
    $MdlCode = $aRow['ModelCode'];
    $EngCode = $aRow['EngineCode'];
    $cc = $aRow['Cc'];
    $strdate = $aRow['Start'];
    $enddate = $aRow['End'];
    $Cptn = $aRow['Cprtn'];/* Genuine P/NO */
    $dnPrt = $aRow['Prtno'];/*Supplier Genuine P/NO */
   $Prtnam = $aRow['Prtnm'];
    $Remrk = $aRow['Remark'];
    $Ordprt = $aRow['ordprtno'];
    $supcd = $aRow['supno'];
    $lotsize = $aRow['Lotsize'];
    $BrandName = $aRow['brand'];
    $mto = $aRow['MTO'];
    $stock = $aRow['StockQty'];
    $stdprice = $aRow['stdprice'];
    $prtpic = $aRow['PrtPic'];
 
    $data= [$CarMkr,$MdlName,$VinCode,$MdlCode,$EngCode,$cc,$strdate,$enddate,$Cptn,$supcd,
    $dnPrt,$Prtnam,$prtpic,$BrandName,$Ordprt,$stdprice,$lotsize,$stock,$mto,$Remrk];
 
    array_push($datas,$data);
}


$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('SupplierCatalogue.xlsx');
