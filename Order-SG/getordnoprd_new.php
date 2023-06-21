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
	echo $query;
	mysqli_query($msqlcon,$query);
	$table1=str_replace("regimp",'ordtmp',$table);
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
	
	//$cusno='SDML';
	$query="select * from tc000pr where trim(cusno) = '$cusno' and Owner_Comp='$comp'"; // edit by CTC
	$sql=mysqli_query($msqlcon,$query);		
	$hasil = mysqli_fetch_array ($sql);
	$order=$hasil['ROrdno'];
	if(strlen(trim($order))!=7){
		$vordno=$xperiode."001";
	}else{
		$ordprd=substr($order,0,4);
		if($ordprd!=$xperiode){
				$vordno=$xperiode."001";
		}else{
			$ordval=(int)substr($order,-3);
			$ordval1=$ordval+1;
			$strordval=str_pad((int) $ordval1,3,"0",STR_PAD_LEFT);
			$vordno=$xperiode.$strordval;
		}
	}
		
	$ordertype=trim($_GET['ordertype']);
	$xordno=$alias.$vordno.substr($ordertype,0,1);
	
	$query="select * from orderhdr where orderno='".$xordno."' and Owner_Comp='$comp' ";  // edit by CTC
	//echo $query;
    $sql=mysqli_query($msqlcon,$query);
   	$hasil = mysqli_fetch_array ($sql);
    if(!$hasil){
		//echo "ora ketemu";
		$xordno=$alias.$vordno.substr($ordertype,0,1);
		echo $cyear."||".$xmonth."||".$xordno."||".$ordertype;
	}else{
		//echo "ketemu";
		$query="select orderno from orderhdr where CUST3='".$cusno."' and ordtype = '".substr($ordertype,0,1)."' and Owner_Comp='$comp' order by orderno desc limit 1";  // edit by CTC
    	$sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
		if($hasil){
			$orderno=$hasil['orderno'];
			$sqx=substr(substr($orderno,0,9),-3);
			$prdx=substr(substr($orderno,0,6),-4);
			//echo "sequence=".$sqx . "<br>";
			if($prdx!=$xperiode){
				$vordno=$xperiode."001";
				//echo "periode=".$prdx . "<br>";
				//echo "System=".$xperiode . "<br>";
				//echo "sequence=".$sqx . "<br>";
			}else{
				$ordval=(int)$sqx;
				$ordvall=$ordval+1;
				//echo "int=".$ordvall. "<br>";
				$strordval=str_pad($ordvall,3,"0",STR_PAD_LEFT);
				$vordno=$xperiode.$strordval;
		
			}
				$xordno=$alias.$vordno.substr($ordertype,0,1);
				echo $cyear."||".$xmonth."||".$xordno."||".$ordertype;
		}
	}

?>
