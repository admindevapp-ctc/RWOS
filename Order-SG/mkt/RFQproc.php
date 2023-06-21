<?php session_start() ?>
<?php require_once('./../../core/ctc_init.php'); ?> <!-- add by CTC -->
<?
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
		$comp = ctc_get_session_comp();  // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


 include "../crypt.php";
	   require('../db/conn.inc');
//echo $action;
$var = decode($_SERVER['REQUEST_URI']);
$xrfqno=trim($var['rfqno']);
$action=trim($var['action']);
$prtno=trim($var['prtno']);
$history=trim($var['history']);
$xrfqdt=trim($var['rfqdt']);
$mpage=trim($var['mpage']);
$sortby=trim($var['sortby']);
$srcstatus=trim($var['status']);
$srccust3=trim($var['srccust3']);
$srcpart=trim($var['srcpart']);
$fld='sortby='.$sortby.'&status='.$srcstatus.'&srccust3='.$srccust3.'&srcpart='.$srcpart;


if($history!='Y'){
	$history='N';
}
if($shpno==''){
	$shpno=$cusno;
}

$cyear=date('Y');
$cmonth=date('m');
$ym=$cyear.$cmonth;
$cymd=date('Ymd');
if ($action=='move'){
	$query="update rfqdtl set STSMKT='X' where RFQNO='".$xrfqno."' and PRTNO='". $prtno. "' and Owner_Comp='". $comp. "'";  // update by CTC
	mysqli_query($msqlcon,$query);
	//$link= "mainRFQ.php";
	$link='mainRFQ.php?'.$fld. '&mpage='.$mpage;
}
echo "<script> document.location.href='". $link . "'; </script>";

?>
