<?php


session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{   
	if($_SESSION['redir']=='Order-SG'){
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
		if($type!='a')header("Location:../main.php");
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	

	header("Location:../login.php");
}
require('../db/conn.inc');
$vsupno = $_GET["supno"];
//check data on supref
$query="Select * from  supref  where  supno='$vsupno' and Owner_Comp='$comp'";
//echo $query;
$sql=mysqli_query($msqlcon,$query);
if($hsl = mysqli_fetch_array ($sql)){
    $errorx="Supplier code exist in 'Supplier & Customer Reference Master'. Please delete 'Supplier & Customer Reference Master' at first";
}
else{
    $errorx="yes";
}
echo $errorx;
?>