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
$xid=trim($_GET['id']);
$xaction=trim($_GET['action']);
//echo $xid . $xaction;
$vcusno=trim($_POST['vcusno']);
$vcusnm=trim($_POST['vcusnm']);
$vcurcd=trim($_POST['vcurcd']);
$vremark=trim($_POST['vremark']);
if(isset($_POST['vaction'])){
	$vaction=trim($_POST['vaction']);
}else{
	$vaction=trim($_GET['action']);
	
}

require('../db/conn.inc');
if($vremark==''){
	$errorx='remark';
}
// echo 'action=' . $vaction;
if($vaction=='add'){
	if($errorx==''){	
		$query="insert into cusrem(cusno, curcd, remark,Owner_Comp) values('$vcusno', '$vcurcd', '$vremark','$comp')";
	 	mysqli_query($msqlcon,$query);
		}
}else{
	if($vaction=='edit'){
			if($errorx==''){	
	 			$query="update cusrem set curcd='$vcurcd', remark='$vremark' where trim(Cusno) = '$vcusno' and Owner_Comp = '$comp'";
	  			mysqli_query($msqlcon,$query);
			}
	}else{
		if($vaction=='delete'){
			$query="delete from Cusrem where trim(cusno) = '$xid' and Owner_Comp='$comp'";
	  			mysqli_query($msqlcon,$query);
				$errorx='';
		}

	}
		
		
}
//echo $query;
if($errorx!=''){	
	echo $errorx . ' should be filled';
}else{
	//echo 'ok';
	echo "<script> document.location.href='mainCusRemAdm.php'; </script>";
}





?>
