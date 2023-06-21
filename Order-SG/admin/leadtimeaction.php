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
	$setday=trim($_GET['setday']);
	$startdate=trim($_GET['startdate']);
	$enddate=trim($_GET['enddate']);
	$status=trim($_GET['status']);
	$ordtype=trim($_GET['ordtype']);
	$action=trim($_GET['action']);
                    
	// check from customer master database
	
	$sql = "SELECT * from duedate where Owner_Comp='$comp'" ;
	$result = mysqli_query($msqlcon,$sql);
	if (! $aRow = mysqli_fetch_array( $result ) ){
			echo "Error - duedate  not found";
	}else{
		if($action=='add'){
			echo "No add";
		}else{
		    if($action=='edit'){
			    $sql = "update duedate "; 
                if($ordtype == 'U'){
                    $sql = $sql . "set setday='$startdate', endday='$enddate' , menu_sts='$status' ";
                }else{
                    $sql = $sql . "set setday='$setday', endday='$enddate' , menu_sts='$status' ";
                }
                $sql = $sql . " where ordtype ='$ordtype' and Owner_Comp='$comp'";
			    //echo $sql;
				$result = mysqli_query($msqlcon,$sql);
			} //edit
		}  //if action==add
		
	}
	
	
	
?>
