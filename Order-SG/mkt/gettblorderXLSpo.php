<?php 
	session_start() ;
	require_once('../../core/ctc_init.php'); // add by CTC

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

$namaFile = "OrderbyPo.xls";
 
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
xlsWriteLabel(0,0,"PO Number");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Ship To");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Order Type");
 
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
	
	/** ini **/
	
	$cusgroup=array();
	$prdgrp==array();
	$sql = "SELECT CusGr, GrpPrd from mktaccess where  usernm='$cusno' and Owner_Comp='$comp' group by CusGr, GrpPrd";
	$flg='';
	$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $xresult ) )	{
			$cusgroup[]=$aRow['CusGr'];
			$prdgrp[]=$aRow['GrpPrd'];
	}
	$vcusgroup=array_unique($cusgroup);
	$vprdgrp=array_unique($prdgrp);
	if(count($vcusgroup)>0){
			$cussel="('".implode("','",$vcusgroup). "')";
			$sql = "SELECT Cusno from cusmas where  cusgr in $cussel and Owner_Comp='$comp' group by cusno";
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$csgroup[]=$aRow['Cusno'];
			}
			$vcussel="('".implode("','",$csgroup). "')";
	}

	
	if(count($vprdgrp)>0){
			$prdsel="('".implode("','",$vprdgrp). "')";	
			$sql = "SELECT Prd from prdgrp where  GrpPrd in $prdsel and Owner_Comp='$comp' group by Prd";
			//echo $sql;
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$pdgroup[]=$aRow['Prd'];
								 
				
			}
			$vprdsel="('".implode("','",$pdgroup). "')";
	}
	
	
	
	
	
	
	
	
	
	
$sQuery = " SELECT  cusno, orderno, orderdate , Corno , sum( qty ) as ttlqty , curcd, sum( bprice * qty ) as ttlamount , sum( bprice * qty * SGPrice ) as ttlamountsg, orderno 
FROM orderdtl INNER JOIN bm008pr ON orderdtl.partno = bm008pr.itnbr AND orderdtl.Owner_Comp = bm008pr.Owner_Comp ".
" where orderdtl.Owner_Comp='$comp' and orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto'";

if($vcussel!=''){
		$sQuery = $sQuery . " and orderdtl.cusno in " . $vcussel ;
		if($vprdsel!=''){
			$sQuery = $sQuery . " and bm008pr.product in " . $vprdsel ;
		}
	}
$sQuery = $sQuery . " GROUP BY Corno, cusno, orderdate, orderno ORDER BY Corno, cusno, orderdate, orderno";

//echo $sQuery;
		
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
			
			$ordtype=substr( $orderno, -1);
			
			switch( $ordtype){
				case 'R':
					$ordsts='Regular';
					break;
					
				case 'U':
					$ordsts='Urgent';
					break;	
				case 'C':
					$ordsts='Campaign';
					break;	
				case 'S':
					$ordsts='Special';
					break;
				
				}
		
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			
	// menampilkan no. urut data
	//	xlsWriteLabel($noBarisCell,0,$shpno);
 
		xlsWriteLabel($noBarisCell,0,$corno);
		xlsWriteLabel($noBarisCell,1,$shpno);
		xlsWriteLabel($noBarisCell,2,$ordtype);
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
