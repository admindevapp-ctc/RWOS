<?php

session_start();

// Edit by ctc start
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');
// Edit by ctc end

$namaFile = "PartCatalogue.xlsx";


$datas = [];
// Header
$header = ['Car Maker','Model Name','Vin Code','Model Code','Engine Code','Genuine Part No.'
, 'Customer P/NO','DENSO P/NO','BrandName', 'PartName'
, 'Order P/NO'];

$datas[0] = $header;
// -------- menampilkan data --------- //

$catMaker = (string) filter_input(INPUT_GET, 'CatMaker');
$modelName = (string) filter_input(INPUT_GET, 'ModelName');
$modelCode = (string) filter_input(INPUT_GET, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_GET, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_GET, 'SubModelName');
$search = (string) filter_input(INPUT_GET, 'SearchTable');
$subGroup = (string) filter_input(INPUT_GET, 'SubGroup');
$subbrandName = (string) filter_input(INPUT_GET, 'Brand');
$order = (int) $_REQUEST['order'][0]['column'];
$orderType = (string) $_REQUEST['order'][0]['dir'];
$start = (int) filter_input(INPUT_GET, 'start');
$length = (int) filter_input(INPUT_GET, 'length');
$suppliercode = (string) filter_input(INPUT_GET, 'Supplier');

$result = ctc_get_catalogue_table_excus($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName
, $search, $subGroup, $order, $orderType, $start, $length,$subbrandName, $suppliercode, 'c');

foreach ($result as $aRow) {

    $CarMkr = $aRow['CarMaker'];
    $MdlName = $aRow['ModelName'];
    $VinCode = $aRow['VinCode'];
    $MdlCode = $aRow['ModelCode'];
    $EngCode = $aRow['EngineCode'];
    $GenuinePartNo = $aRow['Cprtn'];
    $PartNo = $aRow['Prtno'];
    $CGPartNo = $aRow['Cgprtno'];
    $BrandName = $aRow['Brand'];
    $PartName = $aRow['Prtnm'];
    $OrderPartNo = $aRow['ordprtno'];

    $data= [$CarMkr,$MdlName,$VinCode,$MdlCode,$EngCode,$GenuinePartNo,$PartNo
    ,$CGPartNo, $BrandName,$PartName,$OrderPartNo];

    array_push($datas,$data);
    
}

$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs($namaFile);