<?php
ini_set("memory_limit","-1");
session_start();

require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
require_once('../../language/Lang_Lib.php');

ctc_checkadmin_permission('../../login.php');

$catMaker = (string) filter_input(INPUT_GET, 'CatMaker');
$modelName = (string) filter_input(INPUT_GET, 'ModelName');
$modelCode = (string) filter_input(INPUT_GET, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_GET, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_GET, 'SubModelName');
$Brand = (string) filter_input(INPUT_GET, 'Brand');
$search = (string) filter_input(INPUT_GET, 'SearchTable');
$comp = ctc_get_session_comp();

require('../db/conn.inc');
$datas = [];
$time_start = time();
$result = ctc_get_catalogue($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName, $Brand, $search);
$time_end = time();
print('Query time :: '.($time_end - $time_start).'<br />');

// Header
$header = ['Car Maker','Model Name', 'VIN Code','Model Code','Engine Code','CC.','Start Date','End Date',
'Genuine Part No.','DENSO Part No.','CG Part No.','Part Name','Product Brand','Order Part No.',
'Standard Price', 'Lot Size', 'Stock' ,'Remark','Part Pic'];

$datas[0] = $header;

// data
$time_start = time();
foreach ($result as $aRow) {
    $CarMkr = $aRow['CarMaker'];
    $MdlName = $aRow['ModelName'];
    $MdlCode = $aRow['ModelCode'];
    $EngCode = $aRow['EngineCode'];
    $cc = $aRow['Cc'];
    $strdate = $aRow['Start'];
    $enddate = $aRow['End'];
    $Custprt = $aRow['Custprthis'];
    $Cptn = $aRow['Cprtn'];
    $Prthis = $aRow['Prtnohis'];
    $dnPrt = $aRow['Prtno'];
    $cgprthis = $aRow['cgprtnohis'];
    $vBrand = $aRow['Brand'];
    $vStock = $aRow['Stock'];
    $vVincode= $aRow['Vincode'];
    $Cgprt = $aRow['Cgprtno'];
    $Ordprt = $aRow['ordprtno'];
    $Prtnam = $aRow['Prtnm'];
    $Remrk = $aRow['Remark'];
    $vstdprice = $aRow['stdprice'];
    $vPrtPic = $aRow['PrtPic'];
    //$lotsize = (int) ctc_get_lotsize($aRow['ordprtno'])[0]['Lotsize'];
    $lotsize = $aRow['Lotsize'];
	
	if($vStock != '-'){
		if($vStock >= $qty){
			//Yes
			$msg= get_lng($_SESSION["lng"], "L0288");
		}else{
			//No
			$msg= get_lng($_SESSION["lng"], "L0289");
		}
	}else{
		if($aRow['hd100pr_amount']>0){
			//Yes
			$msg= get_lng($_SESSION["lng"], "L0288");
		} else{
			//No
			$msg= get_lng($_SESSION["lng"], "L0289");
		}
	}
    // Tuning Performance
/*    $qry1="select * from availablestock where prtno='".$aRow['ordprtno']."' and Owner_Comp='$comp' ";  // edit by CTC
	$qry1Result=mysqli_query($msqlcon,$qry1);
    
	if($stockArray = mysqli_fetch_array ($qry1Result)){
	    $stockqty=$stockArray['qty'];
       // echo $stockqty;
		if($stockqty >= $qty){
			//Yes
			$msg= get_lng($_SESSION["lng"], "L0288");
		}
		else{
			//No
			$msg= get_lng($_SESSION["lng"], "L0289");
		}
	}
	else{
		$qry2="select * from hd100pr where prtno='".$aRow['ordprtno']."' and (l1awqy+l2awqy)>0 ";
		$qry2Result = mysqli_query($msqlcon,$qry2);
		$count2 = mysqli_num_rows($qry2Result);
       // echo $qry2;
		if($count2>0){
			//Yes
			$msg= get_lng($_SESSION["lng"], "L0288");
		} else{
			//No
			$msg= get_lng($_SESSION["lng"], "L0289");
		}
			
	} */

    $data= [$CarMkr,$MdlName,$vVincode,$MdlCode,$EngCode,$cc,$strdate,$enddate,
            $Cptn,$dnPrt,$Cgprt,$Prtnam,$vBrand,$Ordprt,
            $vstdprice,$lotsize,$msg,$Remrk,$vPrtPic];
	// $data= [
		// $CarMkr,
		// $MdlName,
		// $vVincode,
		// $MdlCode,
		// $EngCode,
		// $cc,
		// $strdate,
		// $enddate,
		// $Cptn,
		// $dnPrt,
		// $Cgprt,
		// $Prtnam,
		// $vBrand,
		// $Ordprt,
		// $vstdprice,
		// $lotsize,
		// $msg,
		// $Remrk,
		// $vPrtPic];
		
	
    array_push($datas,$data);
}
$time_end = time();
print('Oject time :: '.($time_end - $time_start).'<br />');


$time_start = time();
$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('PartCatalogue.xlsx');
$time_end = time();
print('Excel time :: '.($time_end - $time_start).'<br />');