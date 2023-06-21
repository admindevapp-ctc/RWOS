<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

require_once('../../../language/Lang_Lib.php');
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		$type=$_SESSION['type'];
		$user=$_SESSION['user'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../../login.php");
}
?>
<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../../css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<script type="text/javascript" language="javascript" src="../../lib/jquery-1.4.2.js"></script>


</head>
<body>

		<?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
         
        <?php 
			  	$_GET['step']="2";
				include("../supnavhoriz.php");
			?>
			
			
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
        	<div class="hmenu">
           	 <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="supmainSlsAdm";
				include("supnavAdm.php");
			  ?>
              </div>
        </div>
            
        <div id="twocolRight">
<?php
include "../../db/conn.inc";
$comp = ctc_get_session_comp(); // add by CTC
$supno=$_SESSION['supno'];

if(isset($_POST['yesbtn'])){
	if($_POST['replace']=='editall'){
    	$qd="DELETE FROM supstock where Owner_Comp='$comp' and supno = '$supno'";
    	mysqli_query($msqlcon,$qd);
	}

		$qa2="Replace INTO supstock(Owner_comp, supno, partno, StockQty)";
		$qa2=$qa2." SELECT '$comp', '$supno'
			, CASE WHEN `Item Number` = '' THEN NULL ELSE `Item Number` END 'partno'
			, CASE WHEN `Qty` = '' THEN NULL ELSE `Qty` END 'stockqty'
			FROM supstocktemp ";


$result = mysqli_query($msqlcon,$qa2) ; 
if (!$result)  {   
	$error_msg = $msqlcon->error;
?>
<form id="myForm" action="../supimstock.php" method="post">
<input type="submit" id="submitButton" value="<?php echo get_lng($_SESSION["lng"], "L0521") ?>" style="margin-bottom:20px;"/><br/>

</form>
<?
	echo '<table class="tbl1" cellspacing="0" cellpadding="0">';
	echo ' <tr class="arial11grey" bgcolor="#AD1D36" >';
    echo '<th width="15%" scope="col">'.get_lng($_SESSION["lng"], "L0526").'</th>';
    echo '<th width="15%" scope="col">'.get_lng($_SESSION["lng"], "L0451").'</th>';
    echo '<th width="20% scope="col">'.get_lng($_SESSION["lng"], "L0520").'</th>';
    echo '<th width="20%" scope="col">'.get_lng($_SESSION["lng"], "L0519").'</th>';
		
		$qa2="SELECT `Item Number` as partno, Qty as stockqty  FROM supstocktemp ";
		$sqlqa2=mysqli_query($msqlcon,$qa2);
		//echo $qa2;
		while($arrqa2=mysqli_fetch_array($sqlqa2)){
			$partno=$arrqa2['partno'];
			$qty=$arrqa2['stockqty'];

			echo "<tr class=\"arial11black\">
					<td>$comp</td>
					<td>$supno</td>
					<td>$partno</td>
					<td>$qty</td>
				</tr>";
		}
		echo "</table>"; 
		echo "<br/><span class='arial21redbold' width='200px'>$error_msg,  ". get_lng($_SESSION["lng"], "E0075") ."</span><br/>";
		echo "<br/><span class='arial21redbold' width='200px'> ". get_lng($_SESSION["lng"], "E0070") ."/span>";

		}
		else{
			$qd="DELETE FROM supstocktemp where Owner_Comp='$comp'  and supno = '$supno'";
			mysqli_query($msqlcon,$qd);
			echo "<SCRIPT type=text/javascript>document.location.href='../supstock_mainadm.php'</SCRIPT>";
		}
 }else{
    $qd="DELETE FROM supstocktemp where Owner_Comp='$comp' and supno = '$supno'";
    mysqli_query($msqlcon,$qd);
	echo "<SCRIPT type=text/javascript>document.location.href='../supstock_mainadm.php'</SCRIPT>";
}
?>
