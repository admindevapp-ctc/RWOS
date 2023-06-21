<?php 

 require('getRequestDueDate.php');
 
 $returnArr=getRequestedDueDate();
 
 $returnStr=$returnArr[0].",";
 $returnStr.=$returnArr[1].",";
 $returnStr.=$returnArr[2].",";
 $returnStr.=$returnArr[3].",";
 $returnStr.=$returnArr[4];

 echo $returnStr;
?>