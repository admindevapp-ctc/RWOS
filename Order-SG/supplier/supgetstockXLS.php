<?php

session_start();

// Edit by ctc start
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
$supno=$_SESSION['supno'];

ctc_checkadmin_permission('../../login.php');
// Edit by ctc end

$comp = ctc_get_session_comp(); 

/// Function penanda awal file (Begin Of File) Excel
/*
function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
}

// Function penanda akhir file (End Of File) Excel

function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

// Function untuk menulis data (angka) ke cell excel

function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
}

// Function untuk menulis data (text) ke cell excel

function xlsWriteLabel($Row, $Col, $Value) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
}

// header file excel
$namaFile = "SubStock.xlsx";


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=" . $namaFile . "");
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Transfer-Encoding: binary ");

xlsBOF();

// ------ membuat kolom pada excel --- //
// mengisi pada cell A1 (baris ke-0, kolom ke-0)
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0, 0, "Item Number");
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0, 1, "Qty");
// -------- menampilkan data --------- //



	/* Database connection information */
	require('../db/conn.inc');
	
    $criteria=" where Owner_Comp='$comp' and supno='$supno' ";
    $xprtno=$_GET["partno"];
	if(isset($xprtno)){
		if(trim($xprtno)!=''&& $xprtno!='null'){
			$criteria .= ' and partno="'.$xprtno.'"';
		}
	}

	$query="select * from supstock ". $criteria;
	
	$noBarisCell = 1;
	// Header
$datas = [];
$header = ['Item Number	','Qty'];

$datas[0] = $header;
	//echo $query;
	$rResult = mysqli_query($msqlcon, $query ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
        $vowner=$aRow['Owner_Comp'];
		$vsupno=$aRow['supno'];
		$vpartno=$aRow['partno'];
		$vqty=$aRow['StockQty'];
	
		
	// menampilkan no. urut data
		//xlsWriteLabel($noBarisCell,0,$vowner);
 /*
// menampilkan data nim
		//xlsWriteLabel($noBarisCell,1,$vsupno);
		xlsWriteLabel($noBarisCell,0,$vpartno);
		xlsWriteLabel($noBarisCell,1,$vqty);
 		$noBarisCell++;
*/
$data= [$vpartno,$vqty];
	
array_push($datas,$data);
    }
// Edit by ctc end
// memanggil function penanda akhir file excel
/*
xlsEOF();
exit();
*/
$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('SubStock.xlsx');