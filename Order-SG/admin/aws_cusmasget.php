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
	$curcd1=trim($_GET['curcd1']);
	
    $sql = "SELECT * from shiptoma where Cusno='$curcd1' and Owner_Comp='$comp'" ;  // select ship to cus 1
	$result = mysqli_query($msqlcon,$sql);
    $cus1_shiptocd = "";
    while($Row = mysqli_fetch_array ($result)){
        $shiptocd=$Row['ship_to_cd'];
        $cus1_shiptocd = $cus1_shiptocd . $shiptocd . ":";
    }

	$query = "SELECT distinct Cusno,Cusnm  from cusmas where Custype='A' and xDealer='$curcd1' and Owner_Comp='$comp'" ;  // select cusno2
	$result = mysqli_query($msqlcon,$query);
    while($Row = mysqli_fetch_array ($result)){
        $ycusno=$Row['Cusno'];
        $ycusnm=$Row['Cusnm'];
        $cuscd2 = $cuscd2 . $ycusno . " - " . $ycusnm . ":";
    }

    echo "cus1_shpto=$cus1_shiptocd||cus2_cd=$cuscd2";
	
?>
