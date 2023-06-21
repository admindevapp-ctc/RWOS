<?php 

session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC
include "../../db/conn.inc";

$comp = ctc_get_session_comp(); // add by CTC

if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM specialpriceaws WHERE Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
    $qa2="Insert into specialpriceaws";
    $qa2.=" SELECT Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD FROM specialpriceawstmp WHERE StatusItem !='H' AND Owner_Comp='$comp'";
    $sqlqa2=mysqli_query($msqlcon,$qa2)OR die(mysqli_error()); 
    $qd="DELETE FROM specialpriceawstmp WHERE Owner_Comp='$comp' ";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Successfully';
}else{
    $qd="DELETE FROM specialpriceawstmp WHERE Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Cancelled';
}
echo "<SCRIPT type=text/javascript>document.location.href='../imspecialaws.php?msg=$allmsg'</SCRIPT>";
?>
