<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

 
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
$namaFile = "orderaws.xls";

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
xlsWriteLabel(0,0,"Customer Number");              
xlsWriteLabel(0,1,"Customer Name");  

// mengisi pada cell A2 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,2,"Order Number");              


// mengisi pada cell A3 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,3,"Dealer PO");             
 
// mengisi pada cell A4 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,4,"Order Date");
 
// mengisi pada cell A5 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,5,"Order Status");  
 
// mengisi pada cell A6 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,6,"Part Number");

// mengisi pada cell A7 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,7,"Part Description");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,8,"Order QTY");

// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,9,"Curr CD");

// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,10,"Price");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,11,"Amount");

// mengisi pada cell A11 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,12,"Amount SG");


// -------- menampilkan data --------- //





	/* Database connection information */
	require('db/conn.inc');
    $datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	
	$sQuery = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp ".
			  " where orderhdr.Dealer='$cusno' and orderhdr.cusno<>orderhdr.Dealer and ( orderdtl.orderdate>='$datefrom' and orderdtl.orderdate<='$dateto') and orderhdr.Owner_Comp='$owner_comp' ";
			$sQuery = $sQuery . " order by orderhdr.cusno, orderhdr.Corno, partno, orderhdr.orderdate";		   
	  	
		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$shpno=$aRow['cusno'];
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$desc=$aRow['itdsc'];
			if($corno=="")$corno="-";
			$qtyx=$aRow['qty'];
			$qty=$aRow['qty'];
			$bprice=$aRow['bprice'];
			$sgprice=$aRow['SGPrice'];
			$ttlprice=$bprice*$qty;
			$ttlpricesg=$ttlprice*$sgprice;
			
			$curcd=$aRow['CurCD'];
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
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
	
	$qrycus="select Cusnm from cusmas where Cusno='".$shpno."'";
			$sqlcus=mysqli_query($msqlcon,$qrycus);		
			if($hasilx = mysqli_fetch_array ($sqlcus)){
				$cusnm=$hasilx['Cusnm'];
			}
			
			//prtdes from Bm008PR
			
			$qryPart="select ITDSC from bm008pr where ITNBR='".$partno."'";
			$sqlprt=mysqli_query($msqlcon,$qryPart);		
			if($hasilx = mysqli_fetch_array ($sqlprt)){
				$desc=$hasilx['ITDSC'];
			}
	
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$cusno);
		xlsWriteLabel($noBarisCell,1,$cusnm);
		xlsWriteLabel($noBarisCell,2,$orderno);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,3,$corno);
		xlsWriteLabel($noBarisCell,4,$orddate);
		xlsWriteLabel($noBarisCell,5,$ordsts);
 		xlsWriteLabel($noBarisCell,6,$partno);
		xlsWriteLabel($noBarisCell,7,$desc);
		xlsWriteNumber($noBarisCell,8,$qty);
		xlsWriteLabel($noBarisCell,9,$curcd);
		xlsWriteNumber($noBarisCell,10,$bprice);
 		xlsWriteNumber($noBarisCell,11,$ttlprice);
		xlsWriteNumber($noBarisCell,12,$ttlpricesg);

		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 
	
?>
