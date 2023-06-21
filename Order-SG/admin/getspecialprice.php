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
	$shpno=trim($_GET['shpno']);
	$itnbr=trim($_GET['itnbr']);
	$curcd=trim($_GET['curcd']);
	$price=trim($_GET['price']);
	$action=trim($_GET['action']);
	
	// check from customer master database
	
	$sql = "SELECT * from cusmas where cusno='$shpno' and Owner_Comp='$comp'" ;  // edit by CTC
	$result = mysqli_query($msqlcon,$sql);
	if (! $aRow = mysqli_fetch_array( $result ) ){
			echo "Error -customer  not found";
	}else{
		$cust2=$aRow['CUST2'];
		 $cust3=$aRow['CUST3'];
		$sql = "SELECT * from bm008pr where itnbr='$itnbr' and Owner_Comp='$comp'" ;  // edit by CTC
		$result = mysqli_query($msqlcon,$sql);
		if (! $aRow = mysqli_fetch_array( $result ) ){
			echo "Error -Item Number  not found";
		}else{
			$sql = "SELECT * from specialprice where itnbr='$itnbr'and cusno='$shpno' and Owner_Comp='$comp' ";  // edit by CTC
			$result = mysqli_query($msqlcon,$sql);
			if (! $aRow = mysqli_fetch_array( $result ) ){
				if($action=='add'){
				   $sql = "insert into specialprice(`Cusno`, `Itnbr`, `Price`, `CurCD`, `CUST2`, `CUST3`, `Owner_Comp`) values('$shpno', '$itnbr', $price, '$curcd', '$cust2', '$cust3','$comp')";  // edit by CTC
				   $result = mysqli_query($msqlcon,$sql);	
				}else{
				  echo "Error - specialprice  not found";
				}
			}else{
				if($action=='add'){
					  echo "Error - specialprice found";
				}else{
					if($action=='edit'){
						$sql = "update specialprice set Price= $price,  curcd='$curcd'  where cusno='$shpno' and Itnbr='$itnbr' and Owner_Comp='$comp' ";  // edit by CTC
						echo $sql;
						$result = mysqli_query($msqlcon,$sql);
					}else{
						if($action=='delete'){
							$sql="delete from  specialprice  where cusno='$shpno' and Itnbr='$itnbr' and Owner_Comp='$comp' ";  // edit by CTC
							$result = mysqli_query($msqlcon,$sql);
							echo "<script> document.location.href='mainSpecialAdm.php'; </script>";
						}
					}  //edit
				}  //if action==add
		
			}  //specialprice
		}
	}
	
	
	
?>
