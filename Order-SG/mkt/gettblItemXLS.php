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
$namaFile="ItemMaster.xls"; 


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
xlsWriteLabel(0,0,"Part Number");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Assy CD");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Description");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Class");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,4,"Planner");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,5,"Product");

xlsWriteLabel(0,6,"Sub Product");

xlsWriteLabel(0,7,"LotSize");
xlsWriteLabel(0,8,"IT Category");
xlsWriteLabel(0,9,"IT Type");


// -------- menampilkan data --------- //






	/* Database connection information */
	require('../db/conn.inc');
	
	$sQuery  = "SELECT  * FROM bm008pr " ;
	$sQuery = $sQuery . " order by Bm008pr.ITNBR";		 
	  	

		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$itnbr=$aRow['ITNBR'];
			$assycd=$aRow['ASSYCD'];
			$itdsc=$aRow['ITDSC'];
			$class=$aRow['ITCLS'];
			$plann=$aRow['PLANN'];
			$product=$aRow['Product'];
			$sub=$aRow['SubProd'];
			$lot=$aRow['Lotsize'];
			$itcat=$aRow['ITCAT'];
			$ittyp=$aRow['ITTYP'];
			
			
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$itnbr);
 		xlsWriteLabel($noBarisCell,1,$assycd);	
		xlsWriteLabel($noBarisCell,2,$itdsc);
		xlsWriteLabel($noBarisCell,3,$class);
		xlsWriteLabel($noBarisCell,4,$plann);
		xlsWriteLabel($noBarisCell,5,$product);
		xlsWriteLabel($noBarisCell,6,$sub);
 		xlsWriteNumber($noBarisCell,7,$lot);
		xlsWriteLabel($noBarisCell,8,$itcat);
 		xlsWriteLabel($noBarisCell,9,$ittyp);
 		
	
		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
