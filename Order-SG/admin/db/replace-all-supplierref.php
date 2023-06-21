<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC
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
         
			<ul>  
  				<li id="current"><a href="../maincusadm.php" target="_self">Administration</a></li>
				<li><a href="../../Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	
			
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
        	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
           	 <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="sup_mainadm";
				include("navAdm.php");
			  ?>
              </div>
            
        <div id="twocolRight">
		<?php


include "../../db/conn.inc";
//error_reporting( ~E_NOTICE ); // avoid notice


if(isset($_POST['yesbtn'])){
	if($_POST['replace']=='editall'){
    	$qd="DELETE FROM supref where Owner_Comp='$comp'";
    	mysqli_query($msqlcon,$qd);
	}
		$qa2="Replace INTO supref (Owner_comp, supno, Cusno, shipto)";
		$qa2=$qa2." SELECT '$comp'
		, CASE WHEN `SupplierNo` = '' THEN NULL ELSE `SupplierNo` END 'SupplierNo'
		, CASE WHEN `Customer Number` = '' THEN NULL ELSE `Customer Number` END 'CustomerNumber'
		, CASE WHEN replace(`Shipto`,'\r','') = '' THEN NULL ELSE replace(`Shipto`,'\r','') END 'Shipto'
		 FROM supreftemp ";
		$result = mysqli_query($msqlcon,$qa2);
		if(!$result){

			$error_msg = $msqlcon->error;
	   ?>
	   <form id="myForm" action="../supref_import.php" method="post">
		   <input type="submit" id="submitButton" value="Upload again" style="margin-bottom:20px;"/><br/>
		   
	   </form>

	   <?php 

				echo '<br>';
                        
                echo '<table class="tbl1" >';
                echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
                echo '<th width="20%" align="center">SupplierNo</th>';
                echo '<th width="20%" align="center">CustomerNo</th>';
                echo '<th  width="20%" align="center">Shipto</th></tr>';
                $qa2="SELECT `Customer Number` as CustomerNo ,SupplierNo, Shipto FROM supreftemp  LIMIT 10";
                //echo $qa2;
                $sqlqa2=mysqli_query($msqlcon,$qa2);
				while($arrqa2=mysqli_fetch_array($sqlqa2)){
					$SupplierNo=$arrqa2['SupplierNo'];
					$CustomerNo=$arrqa2['CustomerNo'];
					$Shipto=$arrqa2['Shipto'];
					echo "<tr class=\"arial11black\">
							<td>$SupplierNo</td>
							<td>$CustomerNo</td>
							<td>$Shipto</td>
						</tr>";
				}
				echo "</table>"; 
				echo "<br/><span class='arial21redbold' width='200px'>$error_msg, Please Recheck again</span><br/>";
				echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
		}
		else{
			$qd="DELETE FROM supreftemp where Owner_Comp='$comp'";
			mysqli_query($msqlcon,$qd);
			echo "<SCRIPT type=text/javascript>document.location.href='../sup_mainref.php'</SCRIPT>";

		}
 }else{
    $qd="DELETE FROM supreftemp where Owner_Comp='$comp'";
    mysqli_query($msqlcon,$qd);

	echo "<SCRIPT type=text/javascript>document.location.href='../sup_mainref.php'</SCRIPT>";
}
?>
