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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
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
	if($action=='check'){
        $sql = "SELECT * from supsc003pr where Owner_Comp='$owner_comp'" ;
        $result = mysqli_query($msqlcon,$sql);
        if (!$aRow = mysqli_fetch_array( $result ) ){
            echo "Error : Calendar not found. Please contact Denso PIC";
        }else{
			echo "Continous";
        }
    }else{
        if($action=='edit'){
			$date_check = Date('Ym' , strtotime(Date().' + '.$setday.' days'));
			$sql = "SELECT * from supsc003pr where Owner_Comp='$owner_comp' AND yrmon = '$date_check'" ;
			$result = mysqli_query($msqlcon,$sql);
			if (!$aRow = mysqli_fetch_array( $result ) ){
				echo "Error : Calendar not found. Please contact Denso PIC";
			}else{
				$sql = "update awsduedate "
					 . "set setday='$setday' , holiday_st='$status'"
					 . " where ordtype ='$ordtype' and Owner_Comp='$owner_comp' and cusno = '$cusno'";
				$result = mysqli_query($msqlcon,$sql);
			}
			
			
			
			
			
			
			
			
			
			
            
        } //edit
		if($action=='add'){
			$date_check = Date('Ym' , strtotime(Date().' + '.$setday.' days'));
			$sql = "SELECT * from supsc003pr where Owner_Comp='$owner_comp' AND yrmon = '$date_check'" ;
			$result = mysqli_query($msqlcon,$sql);
			if (!$aRow = mysqli_fetch_array( $result ) ){
				echo "Error : Calendar not found. Please contact Denso PIC";
			}else{
				$sql = "
				INSERT INTO `awsduedate`(
					`Owner_Comp`,
					`cusno`,
					`ordtype`,
					`setday`,
					`holiday_st`
				)
				VALUES(
					'$owner_comp',
					'$cusno',
					'R',
					'$setday',
					'$status'
				)
				";
				$result = mysqli_query($msqlcon,$sql);
			}
			

		}
    }

?>
