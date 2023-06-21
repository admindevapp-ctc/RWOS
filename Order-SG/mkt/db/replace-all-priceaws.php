<?php

include "../../db/conn.inc";
if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM sellpriceaws";
    mysqli_query($msqlcon,$qd);
    $qa2="Insert into sellpriceaws";
    $qa2.=" SELECT Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD FROM sellpriceawstmp WHERE StatusItem !='H' ";
    $sqlqa2=mysqli_query($msqlcon,$qa2)OR die(mysqli_error()); 
    $qd="DELETE FROM sellpriceawstmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Successfully';
}else{
    $qd="DELETE FROM sellpriceawstmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Cancelled';
}
echo "<SCRIPT type=text/javascript>document.location.href='../imsalesaws.php?msg=$allmsg'</SCRIPT>";
?>
