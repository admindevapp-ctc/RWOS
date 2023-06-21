<?php

include "../../db/conn.inc";
if(isset($_POST['yesbtn'])){
    
    $qd="DELETE FROM bm008pr";
    mysqli_query($msqlcon,$qd);
    
	$qa2="INSERT INTO  bm008pr  ";
    $qa2= $qa2 . "SELECT ITNBR, ASSYCD, ITDSC, ITCLS, PLANN, Product, SubProd, Lotsize, ITCAT, ITTYP FROM bm008prtmp WHERE StatusItem !='H' ";
    mysqli_query($msqlcon,$qa2) OR die(mysqli_error()); 
    $qd="DELETE FROM bm008prtmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Successfully';
    
}else{
    $qd="DELETE FROM bm008prtmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Cancelled';
}

echo "<SCRIPT type=text/javascript>document.location.href='../imbm008pr.php?msg=$allmsg'</SCRIPT>";

?>
