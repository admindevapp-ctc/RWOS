<?php 

	session_start();
	require_once('./../../core/ctc_init.php');

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

$namaFile = "rptdtlorder.xls";
 
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
 xlsWriteLabel(0,0,"Customer");    
// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,1,"Ship To");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,2,"Dealer PO");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,3,"Order Date");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,4,"Order Status");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,5,"Part Number");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,6,"Part Description");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,7,"Order QTY");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,8,"Currency");

// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,9,"Price");


// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,10,"Total");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,11,"Total SGD");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,12,"Due Date");



// -------- menampilkan data --------- //



	/* Database connection information */
	require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
	
	$cusgroup=array();
	$prdgrp==array();
	$sql = "SELECT CusGr, GrpPrd from mktaccess where  usernm='$cusno' group by CusGr, GrpPrd";
	//echo $sql;
	$flg='';
	$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $xresult ) )	{
			$cusgroup[]=$aRow['CusGr'];
			$prdgrp[]=$aRow['GrpPrd'];
	/**	if($flg==''){
			$cusgroup[]=$aRow['CusGr'];
			$prdgrp[]=$aRow['GrpPrd'];
			$flg='1';
		}
		$vCusGr= array_search($aRow['CusGr'],$cusgroup,true);
		if($vCusGr==false){
			$cusgroup[]=$aRow['CusGr'];
		}
		$vPrd= array_search($aRow['GrpPrd'],$prdgrp,true);
		if($vPrd== false){
			$prdgrp[]=$aRow['GrpPrd'];
		}
	**/		
	}
	$vcusgroup=array_unique($cusgroup);
	$vprdgrp=array_unique($prdgrp);
	//print_r($vprdgrp);
	if(count($vcusgroup)>0){
			$cussel="('".implode("','",$vcusgroup). "')";
			$sql = "SELECT Cusno from cusmas where  cusgr in $cussel group by cusno";
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$csgroup[]=$aRow['Cusno'];
	
			}
			$vcussel="('".implode("','",$csgroup). "')";
	}

	
	if(count($vprdgrp)>0){
			$prdsel="('".implode("','",$vprdgrp). "')";	
			$sql = "SELECT Prd from prdgrp where  GrpPrd in $prdsel group by Prd";
			//echo $sql;
			$xresult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
			while ( $aRow = mysqli_fetch_array( $xresult ) )	{	
				$pdgroup[]=$aRow['Prd'];
								 
				
			}
			$vprdsel="('".implode("','",$pdgroup). "')";
	}
	
	
	
//$sQuery = "SELECT orderhdr.CUST3, orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, SGPrice, CurCD,  ordtype,DueDate, qty, bprice from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
//			  " where orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;
			  
$sQuery = "SELECT   orderhdr.CUST3, orderhdr.cusno, partno, orderhdr.orderno, bm008pr.itdsc, orderhdr.Corno, orderhdr.orderdate, SGPrice, CurCD,  ordtype,DueDate, qty, bprice, bm008pr.product, bm008pr.subprod from orderdtl inner join orderhdr on orderhdr.orderno=orderdtl.orderno inner join bm008pr on orderdtl.partno=bm008pr.itnbr".
			  " where orderdtl.Owner_Comp='$comp' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;

if($vcussel!=''){
		$sQuery = $sQuery . " and orderdtl.cusno in " . $vcussel ;
		if($vprdsel!=''){
			$sQuery = $sQuery . " and bm008pr.product in " . $vprdsel ;
		}
	}

			  
if($search!=''){
		// echo $search;
		switch($search){
			case "partno":
				$fld="orderdtl.partno";
				break;
			case "ITDSC":
				$fld="orderdtl.itdsc";
				break;
			case "corno":
				$fld="orderhdr.Corno";
				break;
		}
		switch($choose){
			case "eq":
				$op="=";
				$vdesc=$desc;
				break;
			case "like";
				$op="like";
				$vdesc="%$desc%";
				break;
		}
		$sQuery = $sQuery . " and $fld $op '$vdesc'";	
	 }
//echo $sQuery;
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$cust3=$aRow['CUST3'];
			$shpno=$aRow['cusno'];
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$desc=$aRow['itdsc'];
			if($corno=="")$corno="-";
			$qty=$aRow['qty'];
			$bprice=$aRow['bprice'];
			$SGPrice=$aRow['SGPrice'];
			$curcd=$aRow['CurCD'];
			$total=($qty*$bprice);
			$totalsg=($qty*$bprice*$SGPrice);
			
			$orderdate=$aRow['orderdate'];
			$duedate=$aRow['DueDate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$duedt=substr($duedate,-2)."/".substr($duedate,4,2)."/".substr($duedate,0,4);
			$odrsts=$aRow['ordtype'];
			switch($odrsts){
				case "U":
					$ordsts="Urgent";
					break;
				case "R":
					$ordsts="Regular";
					break;
				case "N":
					$ordsts="Normal";
					break;
				case "C":
					$ordsts="Campaign";
					break;
			}
	
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$cust3);
		xlsWriteLabel($noBarisCell,1,$shpno);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,2,$corno);
		xlsWriteLabel($noBarisCell,3,$orddate);
		xlsWriteLabel($noBarisCell,4,$ordsts);
 		xlsWriteLabel($noBarisCell,5,$partno);
		xlsWriteLabel($noBarisCell,6,$desc);
		xlsWriteNumber($noBarisCell,7,$qty);
		xlsWriteLabel($noBarisCell,8,$curcd);
		xlsWriteNumber($noBarisCell,9,$bprice);
		xlsWriteNumber($noBarisCell,10,$total);
		xlsWriteNumber($noBarisCell,11,$totalsg);
 	 	xlsWriteLabel($noBarisCell,12,$duedt);
 
		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 


?>
