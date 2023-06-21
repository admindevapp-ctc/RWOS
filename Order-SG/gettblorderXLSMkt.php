<? session_start() ;
?>
<?
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
  
}else{	
header("Location: login.php");
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
xlsWriteLabel(0,0,"Customer Number");              

// mengisi pada cell A2 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,1,"Order Number");              


// mengisi pada cell A3 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,2,"Dealer PO");             
 
// mengisi pada cell A4 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,3,"Order Date");
 
// mengisi pada cell A5 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,4,"Order Status");  
 
// mengisi pada cell A6 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,5,"Part Number");

// mengisi pada cell A7 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,6,"Part Description");

// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,7,"Order QTY");

// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,8,"Price");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,9,"Discount");

// mengisi pada cell A11 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,10,"Total Price");


// -------- menampilkan data --------- //






	/* Database connection information */
	require('db/conn.inc');
	$periode=trim($_GET['periode']);
	$page=trim($_GET['page']);
	$sort=trim($_GET['sort']);
	$namafield=trim($_GET['namafield']);
	
	$sQuery = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
			  " where (orderprd=".$periode. " or SUBSTR(DueDate,1,6)=".$periode. ") ";
			$sQuery = $sQuery . " order by orderhdr.cusno, orderhdr.Corno, partno, orderhdr.orderdate";		   
	  	

		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$cusno=$aRow['cusno'];
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$desc=$aRow['ITDSC'];
			if($corno=="")$corno="-";
			$qtyx=$aRow['qty'];
			$qty=$aRow['qty'];
			$bprice=$aRow['bprice'];
			$disco=(($aRow['disc']+$aRow['dlrdisc'])*$bprice)/100;
			$ttlprice=$qtyx*($bprice-$disco);
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
	
	$qrycus="select Cusnm from cusmas where Cusno='".$cusno."'";
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
		xlsWriteLabel($noBarisCell,1,$orderno);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,2,$corno);
		xlsWriteLabel($noBarisCell,3,$orddate);
		xlsWriteLabel($noBarisCell,4,$ordsts);
 		xlsWriteLabel($noBarisCell,5,$partno);
		xlsWriteLabel($noBarisCell,6,$desc);
		xlsWriteNumber($noBarisCell,7,$qty);
		xlsWriteNumber($noBarisCell,8,$bprice);
		xlsWriteNumber($noBarisCell,9,$disco);
 		xlsWriteNumber($noBarisCell,10,$ttlprice);
		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
