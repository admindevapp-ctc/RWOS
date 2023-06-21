<?php 
session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp();
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
       	  <h3>Supplier Upload</h3>
			 <?php


include "../../db/conn.inc";
//error_reporting( ~E_NOTICE ); // avoid notice
	
if(isset($_POST['yesbtn'])){
	if($_POST['replace']=='editall'){
    	$qd="DELETE FROM supmas where Owner_Comp='$comp'";
    	mysqli_query($msqlcon,$qd);
	}
		$qa2="Replace INTO supmas(Owner_comp, supno, supnm, add1, add2, add3, email1, email2, logo, duedate, holidayck, website)";
		$qa2=$qa2." SELECT '$comp'
		, CASE WHEN `SupplierNo` = '' THEN NULL ELSE `SupplierNo` END 'SupplierNo'
		, SupplierName
		, Address1, Address2, Address3, Email1, Email2, logo, Duedate, convert(Holidayck, int) as Holidayck, Website FROM supmastemp ";

    	$result = mysqli_query($msqlcon,$qa2); //OR die($msqlcon->error); 
		if(!$result){

		 	$error_msg = $msqlcon->error;
		?>
		<form id="myForm" action="../sup_import.php" method="post">
			<input type="submit" id="submitButton" value="Upload again" style="margin-bottom:20px;"/><br/>
			
		</form>

		<?php 
			echo '<table class="tbl1" >';
			echo '<tr class="arial11grey" bgcolor="#AD1D36" >';
			echo '<th width="10%" align="center">SupplierNo</th><th width="10%" align="center">SupplierName</th>';
			echo '<th  width="10%" align="center">Address1</th><th width="10%" align="center">Address2</th>';
			echo '<th width="10%" align="center">Address3</th> <th width="10%" align="center">Email1</th>';
			echo '<th width="10%" align="center">Email2</th><th width="10%" align="center">Website</th>';
			echo '<th width="10%" align="center">logo</th><th width="5%" align="center">Duedate</th>';
			echo '<th width="5%" align="center">Holidayck</th></tr>';
			$supmastemp="SELECT * FROM supmastemp  LIMIT 10";
			$sqlsupmastemp=mysqli_query($msqlcon,$supmastemp);
			while($arrqa2=mysqli_fetch_array($sqlsupmastemp)){
				$SupplierNo=trim($arrqa2['SupplierNo']);
				$SupplierName=trim($arrqa2['SupplierName']);
				$Address1=trim($arrqa2['Address1']);
				$Address2=trim($arrqa2['Address2']);
				$Address3=trim($arrqa2['Address3']);
				$Email1=trim($arrqa2['Email1']);
				$Email2=trim($arrqa2['Email2']);
				$Website=trim($arrqa2['Website']);
				$logo=trim($arrqa2['logo']);
				$Duedate=trim($arrqa2['Duedate']);
				$holichk=trim($arrqa2['Holidayck']);
				echo "<tr class=\"arial11black\">
						<td>$SupplierNo</td>
						<td>$SupplierName</td>
						<td>$Address1</td>
						<td>$Address2</td>
						<td>$Address3</td>
						<td>$Email1</td>
						<td>$Email2</td>
						<td>$Website</td>
						<td>$logo</td>
						<td>$Duedate</td>
						<td>$holichk</td>
					</tr>";
			}
			echo "</table>"; 
			echo "<br/><span class='arial21redbold' width='200px'>$error_msg, Please Recheck again</span><br/>";
			echo "<br/><span class='arial21redbold' width='200px'>Note : Data not upload to system</span>";
			

		}
		else{
			//echo $qa2;
			$qd="DELETE FROM supmastemp ";
			mysqli_query($msqlcon,$qd);
			echo "<SCRIPT type=text/javascript>document.location.href='../sup_mainadm.php'</SCRIPT>";
		}
 }else{
    $qd="DELETE FROM supmastemp ";
    mysqli_query($msqlcon,$qd);
	echo "<SCRIPT type=text/javascript>document.location.href='../sup_mainadm.php'</SCRIPT>";
}

?>

	</body>
</html>