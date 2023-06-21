<?php

include "../../db/conn.inc";
if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM specialpriceaws";
    mysqli_query($msqlcon,$qd);
    $qa2="Insert into specialpriceaws";
    $qa2.=" SELECT Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD FROM specialpriceawstmp WHERE StatusItem !='H' ";
    $sqlqa2=mysqli_query($msqlcon,$qa2)OR die(mysqli_error()); 
    $qd="DELETE FROM specialpriceawstmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Successfully';
}else{
    $qd="DELETE FROM specialpriceawstmp";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Cancelled';
}
echo "<SCRIPT type=text/javascript>document.location.href='../imspecialaws.php?msg=$allmsg'</SCRIPT>";
?>
