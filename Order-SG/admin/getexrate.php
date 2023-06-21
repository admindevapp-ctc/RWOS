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
	$curcd=trim($_GET['curcd']);
	$exrate=trim($_GET['exrate']);
	$efdate=trim($_GET['efdate']);

	$action=trim($_GET['action']);
	$skr=date('Ymd');
	
	$tgl=substr($efdate,-4).substr($efdate,0,2).substr($efdate,3,2);

	//check di database
	$sql = "SELECT * from excrate where curcd='$curcd' and efDateFr='$tgl' and Owner_Comp='$comp'" ;
	$result = mysqli_query($msqlcon,$sql);
	if ( $aRow = mysqli_fetch_array( $result ) ){
		if($action=='add'){
			echo "Error -currency code and effective date not found";
		}else{
			if($action=='edit'){
				$sql = "update excrate set Rate= $exrate where curcd='$curcd' and efDateFr='$tgl' and Owner_Comp='$comp'" ;
				echo $sql;
				$result = mysqli_query($msqlcon,$sql);
			}else{
				if($action=='delete'){
					$sql = "delete from  excrate where curcd='$curcd' and efDateFr='$tgl' and Owner_Comp='$comp'" ;
					$result = mysqli_query($msqlcon,$sql);
					echo "<script> document.location.href='mainExcAdm.php'; </script>";
				}
			}
		}
	}else{
		if($action=='edit' || $action=='delete' ){
			echo $sql;
			echo "Error - Order Period can't be found";
		}else{
			$sql = "insert into ExcRate(CurCD, Rate, EfDateFr, uptime,Owner_Comp) values('$curcd', '$exrate', '$tgl', '$skr','$comp')" ;

			$result = mysqli_query($msqlcon,$sql);	
		}
		
	}
	
?>
