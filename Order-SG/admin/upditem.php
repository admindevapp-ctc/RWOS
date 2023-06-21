<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
		if($type!='a'){
			header("Location:../main.php");
		}
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}
//echo $_POST['prtno'];
$vprtno=trim($_POST['prtno']);
$vprtdes=trim($_POST['prtdes']);
$vproduct=trim($_POST['product']);
$vsubproduct=trim($_POST['subproduct']);
$vlotsize=trim($_POST['lotsize']);
$vitcat=trim($_POST['itcat']);
$vittyp=trim($_POST['ittyp']);

require('../db/conn.inc');
if($vprtdes==''){
	$errorx='Part Name';
}

if($vproduct==''){
	if($errorx==""){
		$errorx='product ';
	}else{
		$errorx=$errorx.', product ';
	}
}

if($vsubproduct==''){
	if($errorx==""){
		$errorx='Sub Product ';
	}else{
		$errorx=$errorx.', Sub Product ';
	}
}

if($vlotsize==''){
	if($errorx==""){
		$errorx='Lot Size ';
	}else{
		$errorx=$errorx.', Lot Size ';
	}
}

/*
if($vitcat==''){
	if($errorx==""){
		$errorx='Item Category ';
	}else{
		$errorx=$errorx.', Item Category ';
	}
}
*/
if($vittyp==''){
	if($errorx==""){
		$errorx='Item Type ';
	}else{
		$errorx=$errorx.', Item Type ';
	}
}


if($errorx==''){	
	$query="update bm008pr set ITDSC='$vprtdes', Product='$vproduct',SubProd='$vsubproduct', Lotsize=$vlotsize, ITTYP='$vittyp' where trim(ITNBR) = '$vprtno' and Owner_Comp = '$comp'";
	mysqli_query($msqlcon,$query);
}

if($errorx!=''){	
	echo $errorx . ' should be filled';
}else{
	//echo 'ok';
	echo "<script> document.location.href='mainitem.php'; </script>";
}


?>
