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
		$comp = ctc_get_session_comp();
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

$namaFile = "rptrfq.xls";
 
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
//$namaFile="report.xls"; 


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
xlsWriteLabel(0,1,"RFQ Date");              
 
// mengisi pada cell A2 (baris ke-0, kolom ke-1)
xlsWriteLabel(0,2,"RFQ No");             
 
// mengisi pada cell A3 (baris ke-0, kolom ke-2)
xlsWriteLabel(0,3,"Part No");
 
// mengisi pada cell A4 (baris ke-0, kolom ke-3)
xlsWriteLabel(0,4,"Reply Date");  
 
// mengisi pada cell A5 (baris ke-0, kolom ke-4)
xlsWriteLabel(0,5,"Dias Remark");

// mengisi pada cell A6 (baris ke-0, kolom ke-5)
xlsWriteLabel(0,6,"Dias Answer");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,7,"Subtitute Part No");

// mengisi pada cell A7 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,8,"Update By");


// mengisi pada cell A8 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,9,"Internal Dias Remark");

// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,10,"Internal Note");


// mengisi pada cell A9 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,11,"Remark Date");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,12,"Remark By");

// mengisi pada cell A10 (baris ke-0, kolom ke-6)
xlsWriteLabel(0,13,"Status");



// -------- menampilkan data --------- //



	/* Database connection information */
	require('../db/conn.inc');
	//$datefrom=trim($_GET['datefrom']);
	//$dateto=trim($_GET['dateto']);
	//$search=trim($_GET['search']);
	//$choose=trim($_GET['choose']);
	//$desc=trim($_GET['desc']);
	$noBarisCell = 1;
	$sql = "SELECT  r.*,m.* FROM rfqdtl r 
	left join rfqrmk m on r.intremark=m.RmkKey and r.Owner_Comp=m.Owner_Comp 
	where r.Owner_Comp='$comp' ";
	//echo $sql;
	$rResult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$cust3=$aRow['CUST3'];
			$rfqdate=$aRow['RFQDT'];
			$rfqno=$aRow['RFQNO'];
			$prtno=$aRow['PRTNO'];
			$desc=$aRow['ITDSC'];
			$rpldt=$aRow['RPLDT'];
			$diasrmk=$aRow['DIASRMK'];
			$diasans=$aRow['DIASANS'];
			$subtitute=$aRow['SUBTITUTE'];
			$updby=$aRow['UpdBy'];
			$intremark=$aRow['RmkDes'];
			$remark=$aRow['Remark'];
			$rmkdate=$aRow['RmkDate'];
			$rmkby=$aRow['RmkBy'];
			
			
			$rfqdt=substr($rfqdate,-2)."/".substr($rfqdate,4,2)."/".substr($rfqdate,0,4);
			if($rpldt!=""){
				$replydt=substr($rpldt,-2)."/".substr($rpldt,4,2)."/".substr($rpldt,0,4);
			}else{
				$replydt="";
			}
			
			if($rmkdate!=""){
				$rmkdt=substr($rmkdate,-2)."/".substr($rmkdate,4,2)."/".substr($rmkdate,0,4);
			}else{
				$rmkdt="";
			}
			
			$sts=$aRow['STS'];
			switch($sts){
				case "C":
					$rfqsts="Closed";
					break;
				case "P":
					$rfqsts="Pending";
					break;
				case "N":
					$rfqsts="Normal";
					break;
				}
	
	// menampilkan no. urut data
		xlsWriteLabel($noBarisCell,0,$cust3);
		xlsWriteLabel($noBarisCell,1,$rfqdt);
 
// menampilkan data nim
		xlsWriteLabel($noBarisCell,2,$rfqno);
		xlsWriteLabel($noBarisCell,3,$prtno);
		xlsWriteLabel($noBarisCell,4,$rfqdt);
 		xlsWriteLabel($noBarisCell,5,$diasrmk);
		xlsWriteLabel($noBarisCell,6,$diasans);
		xlsWriteLabel($noBarisCell,7,$subtitute);
		xlsWriteLabel($noBarisCell,8,$updby);
		xlsWriteLabel($noBarisCell,9,$intremark);
		xlsWriteLabel($noBarisCell,10,$remark);
		xlsWriteLabel($noBarisCell,11,$rmkdt);
		xlsWriteLabel($noBarisCell,12,$rmkby);
 	 	xlsWriteLabel($noBarisCell,13,$rfqsts);
 
		$noBarisCell++;

	
	
	}



 
// memanggil function penanda akhir file excel
xlsEOF();
exit();
 


?>
