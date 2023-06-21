<?php

include "../../db/conn.inc";
if(isset($_POST['yesbtn'])){
	if($_POST['replace']='editall'){
    	$qd="DELETE FROM slsplan";
    	mysqli_query($msqlcon,$qd);
	}
		$qa2="Replace INTO slsplan";
    	$qa2=$qa2." SELECT PROD, SUBPROD, BIZTYP, CUSNM, CUST3, YYYYMM, QTY, AMOUNT FROM slsplantmp ";
    	mysqli_query($msqlcon,$qa2) OR die(mysqli_error()); 
   
    	$qd="DELETE FROM slsplantmp";
    	mysqli_query($msqlcon,$qd);
 }else{
    $qd="DELETE FROM slsplantmp";
    mysqli_query($msqlcon,$qd);
}
echo "<SCRIPT type=text/javascript>document.location.href='../mainSlsPlnAdm.php'</SCRIPT>";
?>
