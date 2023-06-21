<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC


if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusnm'];
	$_SESSION['password'];
	$_SESSION['alias'];
	$_SESSION['tablename'];
	$_SESSION['user'];
	$_SESSION['dealer'];
	$_SESSION['group'];
	$_SESSION['type'];
	$_SESSION['custype'];

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$password=$_SESSION['password'];
	$alias=$_SESSION['alias'];
	$table=$_SESSION['tablename'];
	$type=$_SESSION['type'];
	$custype=$_SESSION['custype'];
	$user=$_SESSION['user'];
	$dealer=$_SESSION['dealer'];
	$group=$_SESSION['group'];
	$comp = ctc_get_session_comp(); // add by CTC
  
}else{	
	header("Location: login.php");
}

$namaFile="ItemMaster.xls"; 
 
// Function penanda awal file (Begin Of File) Excel
 
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
 
function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
}
 
// header file excel
$namaFile="CustomerMaster.xls"; 


header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$namaFile."");
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
xlsWriteLabel(0,0,"Customer No");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Customer Name");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Address 1");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Address 2");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,4,"Address 3");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,5,"Company");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,6,"CUST2");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,7,"CUST3");
// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,8,"alias");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,9,"Type");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,10,"Dealer");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,11,"Customer Group");
// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,12,"Sales Route");


// -------- menampilkan data --------- //






	/* Database connection information */
	require('../db/conn.inc');
	
	$sQuery  = "SELECT Cusno,Cusnm,ESCA1,ESCA2,ESCA3,COY,CUST2,CUST3,alias,Custype,xDealer,CusGr,route from CUSMAS where Owner_Comp='$comp'";		 
		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$cusno=$aRow['Cusno'];
			$cusnm=$aRow['Cusnm'];
			$esca1=$aRow['ESCA1'];
			$esca2=$aRow['ESCA2'];
			$esca3=$aRow['ESCA3'];
			$coy=$aRow['COY'];
			$cust2=$aRow['CUST2'];
			$cust3=$aRow['CUST3'];
			$alias=$aRow['alias'];
			$custype=$aRow['Custype'];
			$xdealer=$aRow['xDealer'];
			$cusgr=$aRow['CusGr'];
			$route=$aRow['route'];
			
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$cusno);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,1,$cusnm);
		xlsWriteLabel($noBarisCell,2,$esca1);
		xlsWriteLabel($noBarisCell,3,$esca2);
 		xlsWriteLabel($noBarisCell,4,$esca3);
		xlsWriteLabel($noBarisCell,5,$coy);
		xlsWriteLabel($noBarisCell,6,$cust2);
		xlsWriteLabel($noBarisCell,7,$cust3);
		xlsWriteLabel($noBarisCell,8,$alias);
		xlsWriteLabel($noBarisCell,9,$custype);
		xlsWriteLabel($noBarisCell,10,$xdealer);
		xlsWriteLabel($noBarisCell,11,$cusgr);
		xlsWriteLabel($noBarisCell,12,$route);
		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
