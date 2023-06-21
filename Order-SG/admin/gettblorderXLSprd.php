<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
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
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

$namaFile = "report.xls";
 
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
$namaFile="report.xls"; 


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
xlsWriteLabel(0,0,"Product");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Sub Product");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"PO Number");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Order Date");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,4,"Qty ");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,5,"Amount");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
//xlsWriteLabel(0,6,"amount SG");



// -------- menampilkan data --------- //



	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
$sQuery = " SELECT  Product, Subprod, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno 
FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr and orderdtl.Owner_Comp=bm008pr.Owner_Comp".
" where  orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto' and orderdtl.Owner_Comp='$comp' GROUP BY Product, subprod, orderdate, corno ORDER BY Product, subprod, orderdate, corno";

		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			
			$orderno=$aRow['orderno'];
			$product=$aRow['Product'];
			$subprod=$aRow['Subprod'];
			$corno=$aRow['Corno'];
			$shpno=$aRow['cusno'];
			if($corno=="")$corno="-";
			$ttlqty=$aRow['ttlqty'];
			$ttlamount=$aRow['ttlamount'];
			//$ttlamountsg=$aRow['ttlamountsg'];
		
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			
	// menampilkan no. urut data
	//	xlsWriteLabel($noBarisCell,0,$shpno);
 
		xlsWriteLabel($noBarisCell,0,$product);
		xlsWriteLabel($noBarisCell,1,$subprod);
		xlsWriteLabel($noBarisCell,2,$corno);
 		xlsWriteLabel($noBarisCell,3,$orddate);
		xlsWriteNumber($noBarisCell,4,$ttlqty);
		xlsWriteNumber($noBarisCell,5,$ttlamount);
		//xlsWriteNumber($noBarisCell,6,$ttlamountsg);
		
 
		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
