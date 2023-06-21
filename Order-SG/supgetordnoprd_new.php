<?php 
session_start() ;
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']!='denso-sg'){
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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

	require('db/conn.inc');

	$query="delete from ".$table. " where cusno ='".$cusno. "' and Owner_Comp='$comp' "; // edit by CTC
	//echo $query;
	mysqli_query($msqlcon,$query);
	$table1=str_replace("regimp_supplier",'ordtmp',$table);
	$query2="delete from ".$table1. " where cusno ='".$cusno. "' and Owner_Comp='$comp' "; // edit by CTC
	//echo $query2;
	mysqli_query($msqlcon,$query2);

	$cyear=date('Y');
	$cmonth=date('m');
	$cdate=date('d');
	$cymd=date('Ymd');

	$xmonth=str_pad((int) $cmonth,2,"0",STR_PAD_LEFT);
	$experiode=$cyear.$xmonth;
	$xperiode=substr($experiode,-4);	
	
    //check datenow and lastorderdate
    //check datenow and lastorderdate
			$today = date("ymd");
			$cyear=date('y');
			$cmonth=date('m');
			$cdate=date('d');
			//$cusno='SDML';
			$query="select max(ROrdno) as ROrdno from suptc000pr where Owner_Comp='$comp' and Lastdate = DATE_FORMAT(now(), '%Y%m%d')"; // edit by CTC
			$sql=mysqli_query($msqlcon,$query);		
			if( ! mysqli_num_rows($sql) ) {
				$vorder = (int)1;
				$length = 7;
				$vordno = substr(str_repeat(0, $length).$vorder, - $length);
			}
			else{
				$hasil = mysqli_fetch_array ($sql);
				$lastorderno=$hasil['ROrdno'];
				$vorder = (int)$lastorderno + 1;
				$length = 7;
				$vordno = substr(str_repeat(0, $length).$vorder, - $length);
			}
			//echo $query."<br/>";
			//echo "lastorder".$lastorderno."<br/>";
			//echo "lastorderdate".$lastorderdate."<br/>";
			
		
			$ordertype=trim($_GET['ordertype']);
			$xordno=$today.$vordno.substr($ordertype,0,1);
    echo $cyear."||".$xmonth."||".$xordno."||".$ordertype;
?>
