<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC
require_once('./../language/Lang_Lib.php'); /*Page : mylogon.php*/

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

include('chklogin.php');
include "crypt.php";
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">
<!--

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}

#pagination1 a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination1 a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination1 a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}


-->
 </style>



 <script src="lib/jquery-1.4.2.js"></script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
	<script src="lib/jquery.bgiframe-2.1.2.js"></script>
	<script src="lib/jquery.ui.core.js"></script>
	<script src="lib/jquery.ui.widget.js"></script>
	<script src="lib/jquery.ui.mouse.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.draggable.js"></script>
	<script src="lib/jquery.ui.position.js"></script>
	<script src="lib/jquery.ui.resizable.js"></script>
	<script src="lib/jquery.ui.dialog.js"></script>
	<script src="lib/jquery.effects.core.js"></script>
    <script src="lib/jquery.ui.datepicker.js"></script>

	<link rel="stylesheet" href="css/demos.css">   
	</head>
	<body >
    <?php ctc_get_logo() ?> <!-- add by CTC -->
<div id="mainNav">
<?
	$_GET['selection']="main";
	include("navhoriz.php");
			
?>
</div> 
<div id="isi">
        
    <div id="twocolLeft">
       	<?
		  	$_GET['current']="aws_groupma";
			include("navUser.php");
		?>
    </div>
        <div id="twocolRight">
        <?
			require('../db/conn.inc');
            $vcusno=trim($_GET['cusno1']);
            $vcusno2=trim($_GET['cusno2']);
            $vcusshp1=trim($_GET['shp1']);
            $vcusshp2=trim($_GET['shp2']);
            
            $query="select awscusmas.* ,cusmas.Cusnm from awscusmas join cusmas on awscusmas.cusno2 = cusmas.Cusno AND awscusmas.Owner_Comp = cusmas.Owner_Comp 
            where cusno1='". $vcusno."' and cusno2 = '". $vcusno2."' and ship_to_cd1 = '". $vcusshp1."' 
            and ship_to_cd2 = '". $vcusshp2."' and awscusmas.Owner_Comp='".$comp."'";
            // echo $query;
            $sql=mysqli_query($msqlcon,$query);	
            if($hasil = mysqli_fetch_array ($sql)){
              $cusno1=$hasil['cusno1'];
              $shiptocd1=$hasil['ship_to_cd1'];
              $cusno2=$hasil['cusno2'];
              $cusnm2=$hasil['Cusnm'];
              $shiptocd2=$hasil['ship_to_cd2'];
              $cusgrp=$hasil['cusgrp'];
              $shipadrs1=$hasil['ship_to_adrs1'];
              $shipadrs2=$hasil['ship_to_adrs2'];
              $shipadrs3=$hasil['ship_to_adrs3'];
              $cusmail1=$hasil['mail_add1'];
              $cusmail2=$hasil['mail_add2'];
              $cusmail3=$hasil['mail_add3'];
              $vcusno1=trim($_POST['vcusno1']);
              $inpcusno1="<input type=\"hidden\" name=\"vcusno1\" id=\"vcusno1\" value =".$cusno1." >";
              $inpshp1 = "<input type=\"hidden\" name=\"ssl_shpto1\" id=\"ssl_shpto1\" value =".$shiptocd1." >";
              $inpshp2 = "<input type=\"hidden\" name=\"ssl_cusno2\" id=\"ssl_cusno2\" value =".$cusno2." >";
              $inpcusshpto2 = "<input type=\"hidden\" name=\"vcusshpto2\" value=".$shiptocd2." ></input>";
              $inpgroup="<input type=\"text\"  name=\"vgroup\" class=\"arial11black\" maxlength=\"3\" size=\"3\" value='".$cusgrp."'></input>";
              $inpcusnm2=$cusnm2;
              $inpaddr1=$shipadrs1;
              $inpaddr2=$shipadrs2;
              $inpaddr3=$shipadrs3;
              $inpemail1=$cusmail1;
              $inpemail2=$cusmail2;
              $inpemail3=$cusmail3;

              $inpaction="<input type=\"hidden\" name=\"vaction\" value=\"edit\"></input>";			
              
            }
		
		    ?>
<form id="frmupdawscusmas" name="frmupdawscusmas" method="post" action="aws_updgroupma.php?action=edit">
            
<table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td width="2%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="15%"></td>
    <td width="15%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="15%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="5" class="arial21redbold">2 <sup>nd</sup> Customer MA</td>
    </tr>
    <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><?php echo get_lng($_SESSION["lng"], "L0592"); ?><!--1st customer code--></td>
    <td>:</td>
    <td> <? echo $cusno1 ?></td>
    <td><?php echo get_lng($_SESSION["lng"], "L0593").'1'; ?><!--Ship to address1--></td>
    <td>:</td>
    <td><? echo $inpaddr1 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><?php echo get_lng($_SESSION["lng"], "L0594"); ?><!--1 stCustomer Ship to Code--></td>
    <td>:</td>
    <td><?php echo $shiptocd1 ?></td>
    <td><?php echo get_lng($_SESSION["lng"], "L0593").'2'; ?><!--Ship to address1--></td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr2 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><?php echo get_lng($_SESSION["lng"], "L0588"); ?><!--2nd customer code--></td>
    <td>:</td>
    <td><?php echo $cusno2 ?></td>
    <td><?php echo get_lng($_SESSION["lng"], "L0593").'3'; ?><!--Ship to address1--></td>
    <td>:</td>
    <td colspan="4"><? echo $inpaddr3 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><?php echo get_lng($_SESSION["lng"], "L0595"); ?><!--2ndCustomer Ship to Code--></td>
    <td>:</td>
    <td> <? echo $shiptocd2 ?></td>
    <td><?php echo get_lng($_SESSION["lng"], "L0323").'1'; ?><!--Email 1--> </td>
    <td>:</td>
    <td> <? echo $inpemail1 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><?php echo get_lng($_SESSION["lng"], "L0589"); ?><!--Customer Group--></td>
    <td>:</td>
    <td> <? echo $inpgroup ?></td>
    <td><?php echo get_lng($_SESSION["lng"], "L0323").'2'; ?><!--Email 2--></td>
    <td>:</td>
    <td> <? echo $inpemail2 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><?php echo get_lng($_SESSION["lng"], "L0596"); ?><!--2nd Customer Name--></td>
    <td>:</td>
    <td> <? echo $inpcusnm2 ?></td>
    <td><?php echo get_lng($_SESSION["lng"], "L0323").'3'; ?><!--Email 3--></td>
    <td>:</td>
    <td> <? echo $inpemail3 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><? echo $inpaction . "" .$inpcusno1 . "" .$inpshp1 . "" .$inpshp2 . "" .$inpcusshpto2; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" class="arial11blackbold" id="Submit" value="Save Change"></td>
  </tr>
</table>

<!-- edit action -->
<?php


require('../db/conn.inc');
$vcusno1=trim($_POST['vcusno1']);
$xaction=trim($_POST['vaction']);
$vshpto1=trim($_POST['ssl_shpto1']);
$vcusno2=trim($_POST['ssl_cusno2']);
$vshpto2=trim($_POST['vcusshpto2']);
$vamsgroup=trim($_POST['vgroup']);
if($xaction=='edit'){
    $query="update awscusmas set cusgrp='$vamsgroup'
        where cusno1 = '$vcusno1' and ship_to_cd1 = '$vshpto1' and cusno2 = '$vcusno2' and ship_to_cd2 = '$vshpto2' and Owner_Comp='$comp' ";
    $result =  mysqli_query($msqlcon,$query);
    if($result == '1'){
       // echo "<script>document.location.href='updgroupma.php?cusno1=".$vcusno1."&cusno2=".$vcusno2."&shp1=".$vshpto1."&shp2=".$vshpto2."';</script>";
       echo "<script>document.location.href='aws_groupma.php';</script>";
    }
    else{
        echo '<p><div id="result">Update Error</div></p>';
    }
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col"></th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right">
      </th>
  </tr>
</table>
</form>   	
         
<div id="footerMain1">
	<ul>
      <!--
     
     -->
      </ul>
		
    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved    
	
  </div>
</div>
</div>

	</body>
</html>
