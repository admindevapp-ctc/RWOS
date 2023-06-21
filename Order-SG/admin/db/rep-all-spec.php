<?php

session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

include "../../db/conn.inc";
if(isset($_POST['yesbtn'])){
    $qd="DELETE FROM specialprice WHERE Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);
    
    $qa2="SELECT Cusno, Itnbr, Price, CurCD, CUST2, CUST3 FROM specialpricetmp WHERE StatusItem !='H' AND Owner_Comp='$comp' ";
    $sqlqa2=mysqli_query($msqlcon,$qa2);
    while($arrqa2=mysqli_fetch_array($sqlqa2)){
        $search=array("'","Ã");
        $replace=array("\\'","A");
        
        $acusno=$arrqa2['Cusno'];
        $aitnbr=$arrqa2['Itnbr'];
        $aprice=$arrqa2['Price'];
        $acurcd=$arrqa2['CurCD'];
        $acust2=$arrqa2['CUST2'];
        $acust3=$arrqa2['CUST3'];
    
        $qi2="INSERT INTO specialprice(Cusno, Itnbr, Price, CurCD, CUST2, CUST3,Owner_Comp)
                VALUES('$acusno','$aitnbr','$aprice','$acurcd','$acust2','$acust3','$comp')";
        mysqli_query($msqlcon,$qi2) OR die(mysqli_error()); 
    }
    $qd="DELETE FROM specialpricetmp WHERE Owner_Comp='$comp' ";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Successfully';
}else{
    $qd="DELETE FROM specialpricetmp WHERE Owner_Comp='$comp' ";
    mysqli_query($msqlcon,$qd);
    $allmsg='Replace All Cancelled';
}
echo "<SCRIPT type=text/javascript>document.location.href='../imspecial.php?msg=$allmsg'</SCRIPT>";
?>
