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
$namaFile="Ship To MA.xls"; 


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
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,1,"Ship To");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,2,"Ship to Name");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,3,"Company Email");  

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,4,"Email-1"); 

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,5,"Email-2"); 

// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,6,"Email-3"); 
 



// -------- menampilkan data --------- //






	/* Database connection information */
	require('../db/conn.inc');
	
		
	$sQuery  = "SELECT * FROM shiptoma where Owner_Comp='$comp'  order by Cusno, ship_to_cd";
	//$sQuery = $sQuery . " order by Cusno, itnbr";		 

		
	$noBarisCell = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$xcusno=$aRow['Cusno'];
			$xship_to_cd=$aRow['ship_to_cd'];
			$xship_to_nm=$aRow['ship_to_nm'];
			$xcomp_mail_add=$aRow['comp_mail_add'];
			$xprsn_mail_add1=$aRow['prsn_mail_add1'];
			$xprsn_mail_add2=$aRow['prsn_mail_add2'];
			$xprsn_mail_add3=$aRow['prsn_mail_add3'];
			
			
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$xcusno);
		xlsWriteLabel($noBarisCell,1,$xship_to_cd);
		xlsWriteLabel($noBarisCell,2,$xship_to_nm);
		xlsWriteLabel($noBarisCell,3,$xcomp_mail_add);
		xlsWriteLabel($noBarisCell,4,$xprsn_mail_add1);
		xlsWriteLabel($noBarisCell,5,$xprsn_mail_add2);
		xlsWriteLabel($noBarisCell,6,$xprsn_mail_add3);
 		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 

	

?>
