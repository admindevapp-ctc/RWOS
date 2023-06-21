<?php
session_start();
include "../../db/conn.inc";
require_once('./../../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

if(isset($_POST['yesbtn'])){
	if($_POST['replace']='editall'){
    	$qd="DELETE FROM slsplan where Owner_Comp='$comp'";
    	mysqli_query($msqlcon,$qd);
	}
		$qa2="Replace INTO slsplan(PROD, SUBPROD, BIZTYP, CUSNM, CUST3, YYYYMM, QTY, AMOUNT, Owner_Comp) ";
		$qa2=$qa2." SELECT PROD, SUBPROD, BIZTYP, CUSNM, CUST3, YYYYMM, QTY, AMOUNT, '$comp' FROM slsplantmp ";

    	mysqli_query($msqlcon,$qa2) OR die($msqlcon->error); 
   
    	$qd="DELETE FROM slsplantmp where Owner_Comp='$comp'";
    	mysqli_query($msqlcon,$qd);
 }else{
    $qd="DELETE FROM slsplantmp where Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
}
echo "<SCRIPT type=text/javascript>document.location.href='../mainSlsPlnAdm.php'</SCRIPT>";
?>
