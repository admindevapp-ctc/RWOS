<?php

session_start();

require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');
require_once('../../../language/Lang_Lib.php');

ctc_checkuser_permission('../../../login.php');

// Edit by ctc start
$cusno = ctc_get_session_cusno();
$comp = ctc_get_session_comp();
$catMaker = (string) filter_input(INPUT_GET, 'CatMaker');
$modelName = (string) filter_input(INPUT_GET, 'ModelName');
$modelCode = (string) filter_input(INPUT_GET, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_GET, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_GET, 'SubModelName');
$Brand = (string) filter_input(INPUT_GET, 'Brand');
$Supplier = (string) filter_input(INPUT_GET, 'Supplier');
$SearchTable = (string) filter_input(INPUT_GET, 'SearchTable');

$datas = [];

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
//echo $sql ;
if ($hasil) {
    $cusno1 = strtoupper($hasil['cusno1']);
} else {
    $cusno1 = $cusno ;
}

$result = ctc_get_supcatalogue_forcus($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName, $Brand, $Supplier,$cusno1,$SearchTable );

// Header
$header = ['Car Maker','Model Name', 'VIN Code','Model Code','Engine Code','CC.','Start Date','End Date','Genuine Part No.'
, 'Supplier Code','Supplier Genuine Part No.','Part Name','Picture Name','Product Brand','Order Part No.', 'Standard Price', 'Lot Size'
, 'Stock', 'Stock/Non Stock Item','Remark'];

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
    $stock = $aRow['StockQty'];
    $price = $aRow['stdprice'];
    $BrandName = $aRow['brand'];
    $mto = $aRow['MTO'];
    $prtpic = $aRow['PrtPic'];

    $data= [$CarMkr,$MdlName,$VinCode,$MdlCode,$EngCode,$cc,$strdate,$enddate,$Cptn,
        $supcd,$dnPrt,$Prtnam,$prtpic,$BrandName,$Ordprt,$price,$lotsize,$stock,$mto,$Remrk];

    array_push($datas,$data);
}

$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('SupPartCatalogue.xlsx');