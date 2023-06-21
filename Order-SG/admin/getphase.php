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
		$comp = ctc_get_session_comp(); // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}



	
	/* Database connection information */
	require('../db/conn.inc');
	//$periode=trim($_GET['periode']);
	$itnbr=trim($_GET['itnbr']);
	$subtitude=trim($_GET['subtitude']);
	$desc=trim($_GET['desc']);
	$action=trim($_GET['action']);
	
	// check from customer master database
	
	$sql = "SELECT ITNBR, SUBITNBR, ITDSC FROM phaseout where  ITNBR='$itnbr' and SUBITNBR='$subtitude' and Owner_Comp='$comp'" ;
	//echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	if (! $aRow = mysqli_fetch_array( $result ) ){
		if($action=='add'){
			   $sql = "insert into phaseout(Owner_Comp, ITNBR, SUBITNBR, ITDSC) values('$comp', '$itnbr',  '$subtitude', '$desc')" ;

			   $result = mysqli_query($msqlcon,$sql);	
			}else{
				  echo "Error - PhaseOut  not found";
		}
	}else{
			if($action=='add'){
				  echo "Error - Phase Out found";
			}else{
				if($action=='edit'){
					$sql = "update phaseout set ITDSC= '$desc' where ITNBR='$itnbr' and SUBITNBR='$subtitude' and Owner_Comp='$comp'" ;
					echo $sql;
					$result = mysqli_query($msqlcon,$sql);
				}else{
					if($action=='delete'){
						$sql="delete from  phaseout  where ITNBR='$itnbr' and SUBITNBR='$subtitude' and Owner_Comp='$comp'" ;
						$result = mysqli_query($msqlcon,$sql);
						echo "<script> document.location.href='mainPhaseAdm.php'; </script>";
					}
				 }  //edit
			}  //if action==add
		
	}  //sellprice
	
	
	
?>
