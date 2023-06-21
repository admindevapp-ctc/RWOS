<?php 

session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC
include "../../db/conn.inc";
$cusno=	$_SESSION['cusno'];

$comp = ctc_get_session_comp(); // add by CTC

if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM awsexc WHERE Owner_Comp='$comp' and cusno1='$cusno'";
    mysqli_query($msqlcon,$qd);
    $qa2="Insert into awsexc (  Owner_Comp,cusno1 ,itnbr , sell  , cusgrp ,price ,curr) ";
    $qa2.=" SELECT  Owner_Comp,cusno1 ,itnbr , sell  , cusgrp ,price ,curr
      FROM awsexc_temp WHERE Owner_Comp='$comp' and cusno1='$cusno'";
    //echo $qa2;
    
    $sqlqa2=mysqli_query($msqlcon,$qa2)OR die(mysqli_error()); 
    $qd="DELETE FROM awsexc_temp WHERE Owner_Comp='$comp' and cusno1='$cusno'";
    mysqli_query($msqlcon,$qd);
    $allmsg='Import All Successfully';
    echo "<script> document.location.href='../../aws_saledenso.php' </script>";
   
}
else if($_POST['action']=='delete'){
    $qd="DELETE FROM awsexc_temp WHERE Owner_Comp='$comp' and cusno1='$cusno' ";
    mysqli_query($msqlcon,$qd);
    echo "success";
}
else{
    $qd="DELETE FROM awsexc_temp WHERE Owner_Comp='$comp' and cusno1='$cusno' ";
    mysqli_query($msqlcon,$qd);
    $allmsg='Import All Cancelled';
    echo "<SCRIPT type=text/javascript>document.location.href='../../aws_saledenso_import.php?msg=$allmsg&status=X'</SCRIPT>";
}

?>
