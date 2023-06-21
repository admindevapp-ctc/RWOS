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
$namaFile="SalesPLAN.xls"; 


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
xlsWriteLabel(0,0,"PRODUCT");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Sub Product");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Biz Type");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Customer Name");  

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,4,"CUST3");  

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,5,"Month");  

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,6,"QTY");  

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,7,"AMOUNT");  



// -------- menampilkan data --------- //


	/* Database connection information */
	require('../db/conn.inc');
	
		
	$sQuery  = "SELECT * FROM slsplan where Owner_Comp='$comp'";
	//$sQuery = $sQuery . " order by Cusno, itnbr";		 

	  	

		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$product=$aRow['PROD'];
			$subprod=$aRow['SUBPROD'];
			$biztyp=$aRow['BIZTYP'];
			$cusnm=$aRow['CUSNM'];
			$cust3=$aRow['CUST3'];
			$yyyymm=$aRow['YYYYMM'];
			$qty=$aRow['QTY'];
			$amount=$aRow['AMOUNT'];
			
			
		// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$product);
		xlsWriteLabel($noBarisCell,1,$subprod);
		xlsWriteLabel($noBarisCell,2,$biztyp);
 
		// menampilkan data nim
		xlsWriteLabel($noBarisCell,3,$cusnm);
		xlsWriteLabel($noBarisCell,4,$cust3);
		xlsWriteLabel($noBarisCell,5,$yyyymm);		
		xlsWriteNumber($noBarisCell,6,$qty);
		xlsWriteNumber($noBarisCell,7,$amount);
	
 		$noBarisCell++;
	
	}


 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
