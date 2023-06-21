<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

if (trim($_GET['partno']) == '') {
	$error = 'Error : Part No  should be filled';
}


if ($error) {
	echo $error;
} else {
	$mpartno = explode(',',$_GET['partno']);
	
	//$partno=trim($_GET['partno']);
	$shpno=trim($_GET['shpno']);
	$orderno=trim($_GET['orderno']);
	require('db/conn.inc');
	for($i=0;$i<count($mpartno);$i++){
		$partno=$mpartno[$i];
		$query="delete from ".$table. " where trim(orderno)='".$orderno."' and trim(partno) ='".$partno. "' and trim(Owner_Comp) ='".$comp. "'";
		//echo $query;
		mysqli_query($msqlcon,$query);
	}
	
	}

?>
