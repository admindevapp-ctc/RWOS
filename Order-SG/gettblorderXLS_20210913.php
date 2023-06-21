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
 
// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,0,"Ship To");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Dealer PO");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Order Date");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Order Status");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,4,"Part Number");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,5,"Part Description");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,6,"Order QTY");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,7,"Currency");

// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,8,"Price");


// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,9,"Total");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
//xlsWriteLabel(0,10,"Total SGD");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,10,"Due Date");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,11,"Ship Date"); // Zia Added for Order Progress

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,12,"Ship Qty"); // Zia Added for Order Progress

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,13,"Invoice NO"); // Zia Added for Order Progress

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,14,"Status"); // Zia Added for Order Progress



// -------- menampilkan data --------- //



	/* Database connection information */
	require('db/conn.inc');
	$datefrom=trim($_GET['datefrom']);
	$dateto=trim($_GET['dateto']);
	$search=trim($_GET['search']);
	$choose=trim($_GET['choose']);
	$desc=trim($_GET['desc']);
	
//$sQuery = "SELECT orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, SGPrice, CurCD,  ordtype,DueDate, qty, bprice from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
//			  " where orderhdr.cust3='$cusno' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto'" ;

$sQuery = "SELECT  orderhdr.cusno, partno, orderhdr.orderno, itdsc, orderhdr.Corno, orderhdr.orderdate, SGPrice, CurCD,  ordtype,DueDate, qty, bprice,shipto,vt_cst_ordr_prog.SHPD_YMD,vt_cst_ordr_prog.SHPD_QTY,vt_cst_ordr_prog.CST_ORDR_LN_CMPLT_FLG,vt_cst_ordr_prog.INV_NO,vt_cst_ordr_prog.CST_ORDR_QTY  from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp".
				" left join vt_cst_ordr_prog on orderdtl.cusno=vt_cst_ordr_prog.CST_CD and orderdtl.Corno=vt_cst_ordr_prog.CST_PO_NO  and orderdtl.partno =vt_cst_ordr_prog.CST_ORDR_INPT_ITEM_NO and orderdtl.owner_comp=vt_cst_ordr_prog.owner_comp".	
			  " where orderhdr.cust3='$cusno' and orderhdr.orderdate>='$datefrom' and orderhdr.orderdate<='$dateto' and orderhdr.Owner_Comp='$owner_comp' " ;//12/20/2018 P.Pawan CTC add get shipto //25/11/2019 Zia added order progress



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

		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon,$sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$shpno=$aRow['cusno'];
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$desc=$aRow['itdsc'];
			if($corno=="")$corno="-";
			$qty=$aRow['qty'];
			$bprice=$aRow['bprice'];
			//$SGPrice=$aRow['SGPrice'];
			$curcd=$aRow['CurCD'];
			$total=($qty*$bprice);
			//$totalsg=($qty*$bprice*$SGPrice);
			$orderdate=$aRow['orderdate'];
			$duedate=$aRow['DueDate'];
			$shipdate=$aRow['SHPD_YMD']; // Zia Added for Order Progress
			$slsqty=$aRow['SHPD_QTY']; // Zia Added for Order Progress
			$invno=$aRow['INV_NO']; // Zia Added for Order Progress
			$cstqty=$aRow['CST_ORDR_QTY']; // Zia Added for Order Progress
			$ordrflg=number_format($aRow['CST_ORDR_LN_CMPLT_FLG']); // Zia Added for Order Progress
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$duedt=substr($duedate,-2)."/".substr($duedate,4,2)."/".substr($duedate,0,4);
			
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
	
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$shpno);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,1,$corno);
		xlsWriteLabel($noBarisCell,2,$orddate);
		xlsWriteLabel($noBarisCell,3,$ordsts);
 		xlsWriteLabel($noBarisCell,4,$partno);
		xlsWriteLabel($noBarisCell,5,$desc);
		xlsWriteNumber($noBarisCell,6,$qty);
		xlsWriteLabel($noBarisCell,7,$curcd);
		xlsWriteNumber($noBarisCell,8,$bprice);
		xlsWriteNumber($noBarisCell,9,$total);
		//xlsWriteNumber($noBarisCell,10,$totalsg);
 	 	xlsWriteLabel($noBarisCell,10,$duedt);
 	 	xlsWriteLabel($noBarisCell,11,$shpdate);  // Zia Added for Order Progress
 	 	xlsWriteLabel($noBarisCell,12,$slsqty);	  // Zia Added for Order Progress
		xlsWriteLabel($noBarisCell,13,$invno);  // Zia Added for Order Progress	
 	 	xlsWriteLabel($noBarisCell,14,$ordrflg);  // Zia Added for Order Progress	         		
		$noBarisCell++;

	
	
	}

 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
