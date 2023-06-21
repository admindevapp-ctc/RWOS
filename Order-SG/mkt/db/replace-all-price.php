<?php

include "../../db/conn.inc";
if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM sellprice";
    mysqli_query($msqlcon,$qd);
    
	$qa2="INSERT INTO sellprice";
    $qa2=$qa2." SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM sellpricetmp WHERE StatusItem !='H' ";
    mysqli_query($msqlcon,$qa2) OR die(mysqli_error()); 
   
    $qd="DELETE FROM sellpricetmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Successfully';
}else{
    $qd="DELETE FROM sellpricetmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Cancelled';
}
echo "<SCRIPT type=text/javascript>document.location.href='../imsales.php?msg=$allmsg'</SCRIPT>";
?>
