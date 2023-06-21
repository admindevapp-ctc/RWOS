<?php 
//putenv("NLS_LANG=AMERICAN_AMERICA.TH8TISASCII");

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
header("Location: ../../login.php");
}

/*
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

$namaFile="Supplier.xls"; 
header('Content-type: application/force-download');
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;filename=".$namaFile."");
header("Pragma: no-cache"); 
header("Expires: 0"); 
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");


xlsBOF();
 
// ------ membuat kolom pada excel --- //
 
// mengisi pada cell A1 (baris ke-0, kolom ke-0)
xlsWriteLabel(0,0,"Supplier No");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Supplier Name");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Address 1");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Address 2");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,4,"Address 3");
xlsWriteLabel(0,5,"Email 1");
xlsWriteLabel(0,6,"Email 2");
xlsWriteLabel(0,7,"Website");
xlsWriteLabel(0,8,"Duedate");


// -------- menampilkan data --------- //



/* Database connection information */
require('../db/conn.inc');

$sQuery  = "SELECT * from supmas ";
$criteria =  " where Owner_Comp = '$comp'";

$xsupno=$_GET["supno"];
if(isset($xsupno)){
	if(trim($xsupno)!='' && trim($xsupno)!= NULL && trim($xsupno)!= 'null'){
		 $criteria .= ' and supno="'.$xsupno.'"';        
	}
}
// Header
$datas = [];
$header = ['Supplier Number	','Supplier Name', 'Address1',
'Address2','Address3','Email 1','Email 2','Website','Duedate'];

$datas[0] = $header;

$sQuery= $sQuery . $criteria;

$noBarisCell = 1;
$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
while ( $aRow = mysqli_fetch_array( $rResult ) )
{
	$supno=$aRow['supno'];
	$supnm=$aRow['supnm'];
	$add1= $aRow['add1'];
	$add2= $aRow['add2'];
	$add3= $aRow['add3'];
	$email1=$aRow['email1'];
	$email2=$aRow['email2'];
	$website=$aRow['website'];
	$duedate=$aRow['duedate'];
	$data= [$supno,$supnm,$add1,$add2,$add3,$email1,$email2,$website,$duedate];
	
array_push($datas,$data);
	/*	
	// menampilkan no. urut data
	xlsWriteLabel($noBarisCell,0,$supno);

	// menampilkan data nim
	//xlsWriteLabel($noBarisCell,1,$supnm);
	xlsWriteLabel($noBarisCell,1,mb_convert_encoding($supnm, "UTF-8", "TIS-620"));
	xlsWriteLabel($noBarisCell,2,iconv('UTF-8', 'TIS-620',$add1));
	xlsWriteLabel($noBarisCell,3,iconv('UTF-8', 'TIS-620',$add2));
	xlsWriteLabel($noBarisCell,4,iconv('UTF-8', 'TIS-620',$add3));
	xlsWriteLabel($noBarisCell,5,$email1);
	xlsWriteLabel($noBarisCell,6,$email2);
	xlsWriteLabel($noBarisCell,7,$website);
	xlsWriteLabel($noBarisCell,8,$duedate);
	$noBarisCell++;
*/
}

/*
// memanggil function penanda akhir file excel
xlsEOF();

exit();
 

*/

$xlsx = SimpleXLSXGen::fromArray( $datas );
$xlsx->downloadAs('Supplier.xlsx');
?>
