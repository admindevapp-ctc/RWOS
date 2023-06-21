<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');
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
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='a'){
			header("Location: ../main.php");
		}
		}else{
			echo "<script> document.location.href='../../".$redir."'; </script>";
		}
	}else{	
		header("Location:../../login.php");
	}

?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
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


<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
	/*
	function changecuscode1() {
		var customer1 = $("#vcusno1").val();
		Array_custome1 = customer1.split("-");
		$("#cusname1").val(Array_custome1[1]);
	}

	function changecuscode1() {
		var customer2 = $("#vcusno2").val();
		Array_custome2 = customer2.split("-");
		//alert(Array_custome2[1]);
		$("#cusname2").val(Array_custome2[1]);
	}
	*/
	$(document).ready(function() {
		$.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results == null){
				return null;
			}
			else {
				return decodeURI(results[1]);
			}
		}
	if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
		//alert( "This page is reloaded" );
		window.location = window.location.href.split("?")[0];
	} 


		$('#ConvExcel').click(function(){
			let cusno1 = $.urlParam('cusno1');
			let cusno2 = $.urlParam('cusno2');
			let cusname = $.urlParam('cusname');
			let cusgroup = $.urlParam('cusgroup');
			url= 'aws_cusmas_gettblXLS.php?cusno1='+ cusno1 + '&cusno2=' +cusno2 + '&cusname=' + cusname + '&cusgroup=' +cusgroup;
			//alert(url);
			window.open(url);	
		 });		   
   });

</script>



	</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->
		
		<?
		  require('../db/conn.inc');
		 
		  //$cusno1= '<select name="sslcusno1" id="sslcusno1" class="arial11blue"  onchange="changecuscode1()" style="width: 100%" >';
		  $cusno1= '<select name="cusno1" id="cusno1" class="arial11blue" style="width: 100%" >';
		  $cusno1= $cusno1 .  ' <option value="">Select option</option>';
		  //$query="select Cusno, Cusnm from cusmas cm where Custype = 'A' and Owner_Comp='$comp'";
		  $query="SELECT distinct cusmas.Cusno, cusmas.Cusnm
		  	FROM awscusmas join cusmas on awscusmas.cusno1 = cusmas.Cusno and cusmas.Custype = 'D'  and awscusmas.Owner_Comp = cusmas.Owner_Comp
			where awscusmas.Owner_Comp='$comp'";
		//echo $query;
		  $sql=mysqli_query($msqlcon,$query);	
		  while($hasil = mysqli_fetch_array ($sql)){
			$ycusno=$hasil['Cusno'];
			$ycusnm=$hasil['Cusnm'];
			$ycusno1=$_GET['cusno1'];
			$selected1 = ($hasil["Cusno"] == $ycusno1) ? "selected" : "";
			$cusno1= $cusno1 .  ' <option  value="'.$ycusno.'" '.$selected1.'>'.$ycusno. '</option>';
		  }
		  $cusno1= $cusno1 . ' </select>';

		  //$cusno2= '<select name="sslcusno2" id="sslcusno2" class="arial11blue" onchange="changecuscode2()"  style="width: 100%">';
		  $cusno2= '<select name="cusno2" id="cusno2" class="arial11blue"  style="width: 100%">';
		  $cusno2= $cusno2 .  ' <option value="">Select option</option>';
		  $query1="select distinct cusno2, cusgrp from awscusmas where Owner_Comp='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			$ycusno2=$hasil['cusno2'];
			$ycusgrp2=$hasil['cusgrp'];
			$ycusno_2=$_GET['cusno2'];
			$selected2 = ($hasil["cusno2"] == $ycusno_2) ? "selected" : "";
			$cusno2= $cusno2 .  ' <option value="'.$ycusno2.'" '.$selected2.'>'.$ycusno2. '</option>';
		  }
		  $cusno2= $cusno2 . ' </select>';


		  if(isset($_GET["button"])){
			$xcusno1=$_GET["cusno1"];
			$xcusno2=$_GET["cusno2"];
			$xcusname=$_GET["cusname"];
			$xcusgrp=$_GET["cusgroup"];
			
		}
		 ?>
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
               <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="awscusmas";
				include("navAdm.php");

			
			  ?>
        </div>
		
<div id="twocolRight">
<table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td width="19%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="3%"></td>
    <td width="19%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold"> 2 <sup>nd</sup> Customer MA</td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>    
   
   
        <form name ="search" method="get">
            <fieldset style="width:98%">
            <legend> &nbsp;Search Information</legend>
            <table width="97%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11blackbold">
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">1 <sup>st</sup> Customer Code</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $cusno1 ?></span></td>
                    <td width="2%"></td>
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">Customer Name</div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
					<?php 
					if($xcusname != ""){
						echo '<td width="15%"><span class="arial12Bold"><input type="text" name="cusname" id="cusname" style="width: 100%"value = "'.$xcusname.'"" /></span></td>';
					}
					else{
                    	echo '<td width="15%"><span class="arial12Bold"><input type="text" name="cusname" id="cusname" style="width: 100%" /></span></td>';
					}
					?>
                    <td colspan="3" width="32%">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">2 <sup>nd</sup> Customer Code</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $cusno2 ?></span></td>
                    <td width="2%"></td>
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">Customer Group</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"></span>
					<?php 
					if($xcusgrp != ""){
						echo '<input type="text" name="cusgroup" id="cusgroup" style="width: 100%" value = "'.$xcusgrp.'" />';
					}
					else{
                    	echo '<input type="text" name="cusgroup" id="cusgroup" style="width: 100%"/>';
					}
					?>
					
					</td>
                    <td width="2%"></td>
                    <td width="19%"><input type="submit" name="button" id="button" value="Search" class="arial11"></td>
                    <td width="11%">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            </fieldset>
        </form>

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="middle" class="arial11">
            	<th scope="col">&nbsp;</th>
                <th width="90" scope="col">
					<a href="aws_import.php" id="ImportExel" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0005"); ?></font>
                        </div>
                    </a>
                </th>
				<th width="10"></th>
                <th width="140" scope="col">
                    <div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
                        <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                        <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
                    </div>
                </th>
				<th width="10"></th>
                <th width="90" scope="col">
					<a href="aws_cusmasadd.php?action=add" id="new" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/new.png" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0006"); ?></font>
                        </div>
                    </a>
                </th>
            </tr>
            <tr height="5"><td colspan="5"></td><tr>
        </table>

<!-- start Modify -->
<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="5%" height="30" scope="col" rowspan="2">Company Code</th>
  	<th width="14%" height="30" scope="col" colspan="2">1 <sup>st </sup> Customer</th>
  	<th width="14%" height="30" scope="col" colspan="2">2 <sup>nd </sup> Customer</th>
    <th width="8%" scope="col" rowspan="2">Customer Name</th>
    <th width="8%" scope="col" rowspan="2">2 <sup>nd </sup> Customer Gr</th>
    <th width="8%" scope="col" rowspan="2">Ship to address</th>
    <th width="8%" scope="col" rowspan="2">E-mail 1</th>
    <th width="8%" scope="col" rowspan="2">E-mail 2</th>
    <th width="8%" scope="col" rowspan="2">E-mail 3</th>
    <th width="8%" scope="col" rowspan="2" style="min-width:50px;">Action</th>
  </tr>
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="10%" height="30" scope="col">Customer CD</th>
  	<th width="10%" height="30" scope="col">Ship to</th>
    <th width="10%" scope="col">customer CD</th>
    <th width="10%" scope="col">Ship to</th>
  </tr>
    
 <?
	require('../db/conn.inc');
	if(trim($xcusno1)!=''){
		$criteria .= " and cusno1 like '%$xcusno1%'";	
	}
	if(trim($xcusno2)!=''){
		$criteria .= " and cusno2 like '%$xcusno2%'";
	}
	if(trim($xcusname)!=''){
		$criteria .= "and cusmas.Cusnm like '%$xcusname%'";
	}
	if(trim($xcusgrp)!=''){
		$criteria .= " and cusgrp like'%$xcusgrp%'";
	}
	$per_page=10;
	$num=5;
	$criteria=" where awscusmas.Owner_Comp='$comp' ".$criteria;
	
	$query="select  awscusmas.* ,cusmas.Cusnm from awscusmas join cusmas on awscusmas.cusno2 = cusmas.Cusno and awscusmas.Owner_Comp = cusmas.Owner_Comp ". $criteria;
	//echo $query;
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	     
	$query1="select  awscusmas.* ,cusmas.Cusnm 
	from awscusmas join cusmas on awscusmas.cusno2 = cusmas.Cusno and awscusmas.Owner_Comp = cusmas.Owner_Comp ". $criteria . " order by cusno1".		
	       " LIMIT $start, $per_page";
	//echo $query1;
	$sql=mysqli_query($msqlcon,$query1);	
			if( ! mysqli_num_rows($sql) ) {
				echo "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
			}
			while($hasil = mysqli_fetch_array ($sql)){
				$vowner=$hasil['Owner_Comp'];
				$vcusno1=$hasil['cusno1'];
				$vshpto1=$hasil['ship_to_cd1'];
				$vcusno2=$hasil['cusno2'];
				$vshpto2=$hasil['ship_to_cd2'];
				$vcusnm2=$hasil['Cusnm'];
				$vcusgrp=$hasil['cusgrp'];
				$vshpaddr1=$hasil['ship_to_adrs1'];
				$vshpaddr2=$hasil['ship_to_adrs2'];
				$vshpaddr3=$hasil['ship_to_adrs3'];
				$vmail1=$hasil['mail_add1'];
				$vmail2=$hasil['mail_add2'];
				$vmail3=$hasil['mail_add3'];
				$addres_all = $vshpaddr1." ".$vshpaddr2." ".$vshpaddr3;
				
				if (mb_strlen($addres_all, 'UTF-8') > 20) {
					$title_vshpaddr1 = $addres_all;
					$addres_all = mb_substr($addres_all, 0, 20, 'UTF-8') . "...";
				} else {
					$title_vshpaddr1 = '';
				}

				
				if (mb_strlen($vcusnm2, 'UTF-8') > 20) {
					$title_vcusnm2 = $vcusnm2;
					$vcusnm2 = mb_substr($vcusnm2, 0, 20, 'UTF-8') . "...";
				} else {
					$title_vcusnm2 = '';
				}

				
				if(strlen($vmail1) > 20){
					$title_vmail1 = $vmail1;
                    $vmail1 = substr($vmail1,0,20) ."...";
                }else{
					$title_vmail1 = '';
				}
				if(strlen($vmail2) > 20){
					$title_vmail2 = $vmail2;
                    $vmail2 = substr($vmail2,0,20) ."...";
                }else{
					$title_vmail2 = '';
				}
				if(strlen($vmail3) > 20){
					$title_vmail3 = $vmail3;
                    $vmail3 = substr($vmail3,0,20) ."...";
                }else{
					$title_vmail3 = '';
				}
				
				echo "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
                echo "<td>".$vowner."</td>";
                echo "<td>".$vcusno1."</td>";
                echo "<td>".$vshpto1."</td>";
                echo "<td>".$vcusno2."</td>" ;
				echo "<td>".$vshpto2."</td>";
				echo "<td title='$title_vcusnm2'>".$vcusnm2."</td>";
				echo "<td>".$vcusgrp."</td>";
				echo "<td title='$title_vshpaddr1'>".$addres_all."</td>";
				echo "<td align=\"left\" title='$title_vmail1'>".$vmail1."</td>";
				echo "<td align=\"left\" title='$title_vmail2'>".$vmail2."</td>";
				echo "<td align=\"left\" title='$title_vmail3'>".$vmail3."</td>";
				echo "<td class=\"lasttd\">";

				echo "<a href='aws_cusmasadd.php?action=edit&cusno1=".$vcusno1."&cusno2=".$vcusno2."&shp1=".$vshpto1."&shp2=".$vshpto2."' > <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a> ";
				echo "<a href='aws_updcusmas.php?action=delete&cusno1=".$vcusno1."&cusno2=".$vcusno2."&shpto1=".$vshpto1."&shpto2=".$vshpto2."' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
				echo "<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"12\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";

		}
		
		
		  ?>
 
 <tr>
    <td colspan="12"  align="right" class="lasttd" >
		<img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> =edit</span>
    	<img src="../images/delete.png" width="20" height="20"><span class="arial11redbold"> =delete</span>
	</td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>



              
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
