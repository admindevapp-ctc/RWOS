<?php 

 require 'supgetDueDate.php';

 $supno = (string) filter_input(INPUT_POST, 'Supno');

 $returnArr=GetSupplierDueDate($supno);

 $returnStr=$returnArr[0].",";
 $returnStr.=$returnArr[1].",";
 $returnStr.=$returnArr[2].",";
 $returnStr.=$returnArr[3].",";
 $returnStr.=$returnArr[4];

 echo $returnStr;
 
//echo $returnArr;
?>