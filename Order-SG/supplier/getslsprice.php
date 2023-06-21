<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');

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
		$_SESSION['supno'];
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
		$supno=$_SESSION['supno'];
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
	$shipto=trim($_GET['shipto']);
	$action=trim($_GET['action']);
	
	// check from customer master database
	
	$sql = "SELECT * from cusmas where cusno='$shpno' and Owner_Comp='$comp'" ;
  //echo $sql;
	$result = mysqli_query($msqlcon,$sql);
	if (! $aRow = mysqli_fetch_array( $result ) ){
			echo get_lng($_SESSION["lng"], "E0076");//"Error -customer  not found";
	}else{
			$sql = "SELECT * from supprice where partno='$itnbr'and Cusno='$shpno' and Owner_Comp='$comp' and supno='$supno' and shipto='$shipto'"  ;
			$result = mysqli_query($msqlcon,$sql);
			if (! $aRow = mysqli_fetch_array( $result ) ){
				if($action=='add'){
				   $sql = "insert into supprice(Owner_comp, Cusno, supno, partno, curr, price, shipto) values('$comp','$shpno', '$supno', '$itnbr', '$curcd', '$price', '$shipto')" ;
				   //echo $sql;
                   $result = mysqli_query($msqlcon,$sql);	
				}else{
					echo get_lng($_SESSION["lng"], "E0064");//"Error - supprice not found";
				}
			}else{
				if($action=='add'){
					echo get_lng($_SESSION["lng"], "E0065");// "Error - supprice found";
				}else{
					if($action=='edit'){
						$sql = "update supprice set price= $price,  curr='$curcd', shipto='$shipto'   where Cusno='$shpno' and partno='$itnbr' and Owner_Comp='$comp' and supno='$supno'" ;
						//echo $sql;
						$result = mysqli_query($msqlcon,$sql);
					}else{
						if($action=='delete'){
							$sql="delete from  supprice  where Cusno='$shpno'  and partno='$itnbr' and Owner_Comp='$comp' and shipto='$shipto' and supno='$supno'" ;
							//echo $sql;
							$result = mysqli_query($msqlcon,$sql);
							echo "<script> document.location.href='sup_mainSlsAdm.php'; </script>";
						}
					}  //edit
				}  //if action==add
		
			}  
	}
	
	
	
?>
