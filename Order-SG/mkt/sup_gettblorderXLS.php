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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}
/*
$namaFile = "rptdtlordersup.xls";
 
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
$namaFile="reportordersup.xls"; 


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
xlsWriteLabel(0,0,"Company");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Part no");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Part Description");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Core no");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,4,"Order date");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,5,"Due date");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,6,"Customer no");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,7,"Supplier no");


// mengisi pada cell A8 (baris ke-0, kolom ke-6)
//xlsWriteLabel(0,8,"Status");


// -------- menampilkan data --------- //



/* Database connection information */
require('../db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
$datas = [];
	$sQuery = "select suporderdtl.slsprice, qty ,suporderhdr.supno, suporderhdr.CUST3,suporderhdr.cusno, partno, suporderhdr.orderno, itdsc, suporderhdr.Corno, suporderhdr.orderdate, suporderhdr.ordtype, DueDate , supordernts.remark ".
	" from suporderhdr inner join suporderdtl on suporderhdr.orderno=suporderdtl.orderno and suporderhdr.Owner_Comp=suporderdtl.Owner_Comp  and suporderhdr.Corno = suporderdtl.Corno ".
	" and suporderhdr.CUST3 = suporderdtl.CUST3 and suporderhdr.supno=suporderdtl.supno  ".
	" inner join supordernts on suporderhdr.orderno=supordernts.orderno and suporderhdr.Owner_Comp=supordernts.Owner_Comp  and suporderhdr.Corno = supordernts.Corno ".
	" and suporderhdr.CUST3 = supordernts.CUST3 and suporderhdr.supno=supordernts.supno  ".
	" where suporderhdr.orderdate>='$datefrom' and suporderhdr.orderdate<='$dateto' and suporderhdr.Owner_Comp='$owner_comp' " ;

	
// Header
$header = ['PO number','PO Date','Part Number','Part Name','Due Date','QTY','Price','Amount','Supplier Code','Remark'];//edit by CTC Pasakorn 03/11/2022


$datas[0] = $header;

if($search!=''){
	   // echo $search;
	   switch($search){
		   case "partno":
			   $fld="suporderdtl.partno";
			   break;
		   case "ITDSC":
			   $fld="suporderdtl.itdsc";
			   break;
		   case "corno":
			   $fld="suporderhdr.Corno";
			   break;
		   case "cuscode":
			   $fld="suporderhdr.cusno";
			   break;
		   case "supcode":
			   $fld="suporderhdr.supno";
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
   

	//echo 	$sQuery ;
		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$supno=$aRow['supno'];
		$cusno=$aRow['cusno'];
		$vcust3=$aRow['CUST3'];
		$partno=$aRow['partno'];
		$partdes=$aRow['itdsc'];
		$corno=$aRow['Corno'];
		$duedt=$aRow['DueDate'];
		$slsprice =$aRow['slsprice'];
		$slsamt = $aRow['slsprice'] * $aRow['qty'];
		if($corno=="")$corno="-";
		$qty=number_format($aRow['qty']);
		$orderdate=$aRow['orderdate'];
		$remark=$aRow['remark'];
		$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
		$duedate=substr($duedt,-2)."/".substr($duedt,4,2)."/".substr($duedt,0,4);

//Zia Added for Order Progress > Start	
	
			if(empty($shipdate))
			{
			// it's empty!
			$shpdate=$shipdate;
			}
			else
			{
			$shpdate=substr($shipdate,-2)."/".substr($shipdate,5,2)."/".substr($shipdate,0,4);
			}
			
		//echo "<script type='text/javascript'>alert(\"$shpdate\");</script>";

//Zia Added for Order Progress	> End
			
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
			if($cstqty=="0"){ //Zia Added for Order Progress check//
				$ordrflg = "Cancelled"; 
			}else{
				switch($ordrflg){   
					case "1":
						$ordrflg="Completed";
					break;
					case "0":
						$ordrflg=" ";
					break;
				}
			}
			// $data= [$corno,$orddate,$partno,$partdes,$duedt,$qty,$slsprice,$slsamt,$supno,$remark];
		$data= [$corno,$orddate,$partno,$partdes,$duedt,number_format($qty,0),strval(number_format($slsprice,2,'.',',')),strval(number_format($slsamt,2,'.',',')),$supno,$remark];

			array_push($datas,$data);
		}
	/*
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$owner_comp);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,1,$partno);
		xlsWriteLabel($noBarisCell,2,$partdes);
		xlsWriteLabel($noBarisCell,3,$corno);
 		xlsWriteLabel($noBarisCell,4,$orddate);
		xlsWriteLabel($noBarisCell,5,$duedate);
		xlsWriteNumber($noBarisCell,6,$cusno);
		xlsWriteLabel($noBarisCell,7,$supno);   
	//	xlsWriteLabel($noBarisCell,8,$ordrflg);       		
		$noBarisCell++;

	
	
	}

 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 */

$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('reportordersup.xlsx');
	

?>
