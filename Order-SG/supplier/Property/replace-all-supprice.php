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
session_start();
include "../../db/conn.inc";
require_once('./../../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC
$supno=$_SESSION['supno'];

if(isset($_POST['yesbtn'])){
	if($_POST['replace']=='editall'){
    	$qd="DELETE FROM supprice where Owner_Comp='$comp' and supno = '$supno'";
    	mysqli_query($msqlcon,$qd);
	}

		$qa2="Replace INTO supprice(Owner_comp, supno, Cusno, partno, curr, price, shipto)";
		$qa2=$qa2." SELECT '$comp',  '$supno'
			, CASE WHEN  `Cusno` = '' THEN NULL ELSE `Cusno` END 'Cusno'
			, CASE WHEN `partno` = '' THEN NULL ELSE `partno` END 'partno'
			, CASE WHEN `curr` = '' THEN NULL ELSE `curr` END 'curr'
			, CASE WHEN `price` = '' THEN NULL ELSE  `price` END 'price'
			, CASE WHEN  replace(`shipto`,'\r','') = '' THEN NULL ELSE replace(`shipto`,'\r','')  END 'shipto'
			FROM suppricetemp ";



			$result = mysqli_query($msqlcon,$qa2) ; 
			if (!$result)  {   
				$error_msg = $msqlcon->error;
			?>
			<form id="myForm" action="../supimslsprice.php" method="post">
			<input type="submit" id="submitButton" value="<?php echo get_lng($_SESSION["lng"], "L0521") ?>" style="margin-bottom:20px;"/>
			
			</form>
			<?
		echo '<table class="tbl1" cellspacing="0" cellpadding="0">';
		echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
		echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0522").'</th><th width="20%" align="center">'. get_lng($_SESSION["lng"], "L0520").'</th>';
		echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0523").'</th><th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0524").'</th>';
		echo '<th width="10%" align="center">'. get_lng($_SESSION["lng"], "L0525").'</th> </tr>';
		
		$qa2="SELECT * FROM suppricetemp  LIMIT 10";
		$sqlqa2=mysqli_query($msqlcon,$qa2);
		while($arrqa2=mysqli_fetch_array($sqlqa2)){
			$cusno=$arrqa2['Cusno'];
			$partno=$arrqa2['partno'];
			$curr=$arrqa2['curr'];
			$price=$arrqa2['price'];
			$shipto=$arrqa2['shipto'];

			echo "<tr class=\"arial11black\">
					<td>$cusno</td>
					<td>$partno</td>
					<td>$curr</td>
					<td>$price</td>
					<td>$shipto</td>
				</tr>";
		}
		echo "</table>"; 
		echo "<br/><span class='arial21redbold' width='200px'>$error_msg, ". get_lng($_SESSION["lng"], "E0075")."</span><br/>";
		echo "<br/><span class='arial21redbold' width='200px'>". get_lng($_SESSION["lng"], "E0070")."</span>";
		

		}
		else{
   
			$qd="DELETE FROM suppricetemp where Owner_Comp='$comp'  and supno = '$supno'";
			mysqli_query($msqlcon,$qd);
			echo "<SCRIPT type=text/javascript>document.location.href='../sup_mainSlsAdm.php'</SCRIPT>";
		}
 }else{
    $qd="DELETE FROM suppricetemp where Owner_Comp='$comp' and supno = '$supno'";
    mysqli_query($msqlcon,$qd);
	echo "<SCRIPT type=text/javascript>document.location.href='../sup_mainSlsAdm.php'</SCRIPT>";
}
?>


</body>
</html>