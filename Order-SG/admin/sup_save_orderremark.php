<?php


session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

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
		$imptable=$_SESSION['imptable'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

require('../db/conn.inc');

$orderno = (string) filter_input(INPUT_POST, 'orderno');
$supno = (string) filter_input(INPUT_POST, 'supno');
$owner_comp = (string) filter_input(INPUT_POST, 'owner_comp');
$ponum = (string) filter_input(INPUT_POST, 'corno');
$cusno = (string) filter_input(INPUT_POST, 'cusno');
$remark = (string) filter_input(INPUT_POST, 'remark');

//echo $orderno. $supno.$owner_comp.$ponum.$remark;
$sql = "UPDATE supordernts
SET remark = '".$remark."'
WHERE Owner_Comp = '".$owner_comp."'
    AND supno = '".$supno."'
    AND CUST3 = '".$cusno."'
    AND orderno = '".$orderno."'
    AND Corno = '".$ponum."'

";
//echo $sql;
$result = mysqli_query($msqlcon,$sql);
if($result)
{
    echo "Success";
}
else
{
    echo "Error";

}
    
?>