<?php 

session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC
include "../../db/conn.inc";

$comp = ctc_get_session_comp(); // add by CTC

if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM awscusmas WHERE Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
    $qa2="Insert into awscusmas (  Owner_Comp ,cusno1 ,ship_to_cd1 ,cusno2 ,ship_to_cd2 ,
    ship_to_adrs1 ,ship_to_adrs2,ship_to_adrs3 , mail_add1 ,mail_add2 ,mail_add3 ) ";
    $qa2.=" SELECT Owner_Comp ,cusno1 ,ship_to_cd1 ,cusno2 ,ship_to_cd2 ,
    ship_to_adrs1 ,ship_to_adrs2,ship_to_adrs3 , mail_add1 ,mail_add2 ,mail_add3  FROM awscusmas_temp WHERE Owner_Comp='$comp' ";
    
    $sqlqa2=mysqli_query($msqlcon,$qa2)OR die(mysqli_error()); 
    $qd="DELETE FROM awscusmas_temp WHERE Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
    $allmsg='Import All Successfully';
    echo "<script> document.location.href='../aws_cusmas.php' </script>";
}else{
    $qd="DELETE FROM awscusmas_temp WHERE Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
    $allmsg='Import All Cancelled';
    echo "<SCRIPT type=text/javascript>document.location.href='../aws_import.php?msg=$allmsg&status=E'</SCRIPT>";
}

?>
