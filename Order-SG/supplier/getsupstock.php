<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');


if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['supno'];
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
		$supno=	$_SESSION['supno'];
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
	$itnbr=strtoupper(trim($_GET['itnbr']));
	$qty=strtoupper(trim($_GET['stockqty']));
	$action=trim($_GET['action']);
	
	$sql = "SELECT * from supstock where partno='$itnbr' and Owner_Comp='$comp' and supno = '$supno'" ;
   // echo $sql ;
	$result = mysqli_query($msqlcon,$sql);
	if (!$aRow = mysqli_fetch_array( $result ) ){
        
		if($action=='add'){
			$sql = "insert into supstock(Owner_comp, supno, partno, StockQty) 
            values('$comp', '$supno', '$itnbr', '$qty')" ;
			//echo $sql;
            $result = mysqli_query($msqlcon,$sql);	
		}else{
			 echo get_lng($_SESSION["lng"], "E0066");//" Error - supstock  not found";
		}
        
	}else{
		if($action=='add'){

			echo get_lng($_SESSION["lng"], "E0067");// " Error - Order Part No already found";
            /*
			$sql = "insert into supstock(Owner_comp, supno, partno, StockQty) 
            values('$comp', '$supno', '$itnbr', '$qty')" ;
			//echo $sql;
            $result = mysqli_query($msqlcon,$sql);	
            */
		}else{		
            if($action=='edit'){
				$sql = "update supstock set StockQty= '$qty' where partno='$itnbr' and Owner_Comp='$comp' and supno = '$supno' " ;
				$result = mysqli_query($msqlcon,$sql);
			}else{
				if($action=='delete'){
                    $sql="delete from supstock where partno='$itnbr' and Owner_Comp='$comp' and supno = '$supno' " ;
					//echo $sql;
					$result = mysqli_query($msqlcon,$sql);
					echo "<script> document.location.href='supstock_mainadm.php'; </script>";
				}
			}  //edit
		}  //if action==add
        
    }
		
	
	
	
?>
