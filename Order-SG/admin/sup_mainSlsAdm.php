<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC


if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
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
			header("Location:../main.php");
		}
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   
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

	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">

	<script src="../lib/jquery-1.4.2.min.js"></script>

	<script src="../lib/jquery.ui.core.js"></script>
 	<script src="../lib/jquery.ui.widget.js"></script>
    <script src="../lib/jquery.ui.mouse.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.draggable.js"></script>
	<script src="../lib/jquery.ui.position.js"></script>
	<script src="../lib/jquery.ui.resizable.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.effects.core.js"></script>
    <script src="../lib/jquery.ui.datepicker.js"></script>
    <script src="../lib/jquery.ui.autocomplete.js"></script>
  	<link rel="stylesheet" href="../css/demos.css">   
    <style>
	
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
	
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		button .btn{
			height: 18px;
			width: 18px;
		}
	</style>
    
     
<script type="text/javascript">
$(function() {

	if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
		//alert( "This page is reloaded" );
		window.location = window.location.href.split("?")[0];
	} 
	$.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results == null){
			return null;
			}
			else {
			return decodeURI(results[1]);
			}
		}

	$('#ConvExcel').click(function(){
			//let searchParams = new URLSearchParams(window.location.search)
			let cusno = $.urlParam('vcusno');
			let partno = $.urlParam('vprtno');
			let supno = $.urlParam('vsupno');
			//alert(cusno + partno+ supno);
			url= 'sup_gettblslsXLS.php?vcusno='+ cusno + '&vpartno=' +partno + '&vsupno=' + supno,
			window.open(url);	
					
	})	
		 
});
</script>
</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->

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
			  	$_GET['current']="sup_mainSlsAdm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        
  
       
        <?
		  require('../db/conn.inc');
		 $xcusno='';
		 $xprtno='';
		 $xsupno='';
		  if(isset($_GET["button"])){
				$xcusno=$_GET["vcusno"];
				$xprtno=$_GET["vprtno"];
				$xsupno=$_GET["vsupno"];
		  }
		  	$query="select distinct cusmas.Cusno, cusmas.Cusnm 
			  from cusmas 
			  	join supprice on cusmas.Cusno = supprice.Cusno and cusmas.Owner_Comp = supprice.Owner_comp"
			  ." where cusmas.Owner_Comp='$comp' order by cusmas.cusno ";
			$sql=mysqli_query($msqlcon,$query);	
		  //echo $query;
		  	$inpcusno= '<select name="vcusno" id="vcusno" class="arial11blue" style="width:200px;">';
			$inpcusno= $inpcusno .  ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
			
			while($hasil = mysqli_fetch_array ($sql)){
				$ycusno=$hasil['Cusno'];
				$ycusnm=$hasil['Cusnm'];
				
				if(trim($ycusno)==trim($xcusno)){
				   	$inpcusno= $inpcusno .  ' <option value="'.$ycusno.'" selected>'.$ycusno.' - '. $ycusnm. ' </option>';
				}else{
					$inpcusno= $inpcusno .  ' <option value="'.$ycusno.'">'.$ycusno.' - '. $ycusnm. ' </option>';		
				}
				
			}
        	$inpcusno= $inpcusno . ' </select>';



			$query1="select distinct supmas.supno, supmas.supnm 
				from supmas join supprice on supmas.supno = supprice.supno and supmas.Owner_Comp = supprice.Owner_comp "
			." where supmas.Owner_Comp='$comp' order by supmas.supno ";
			$sql=mysqli_query($msqlcon,$query1);	
			//echo $query1;
		  	$inpsupno= '<select name="vsupno" id="vsupno" class="arial11blue" style="width:200px;">';
			$inpsupno= $inpsupno .  ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
			
			while($hasil = mysqli_fetch_array ($sql)){
				$ysupno=$hasil['supno'];
				$ysupnm=$hasil['supnm'];
				
				if(trim($ysupno)==trim($xsupno)){
				   	$inpsupno= $inpsupno .  ' <option value="'.$ysupno.'" selected>'.$ysupno.' - '. $ysupnm. ' </option>';
				}else{
					$inpsupno= $inpsupno .  ' <option value="'.$ysupno.'">'.$ysupno.' - '. $ysupnm. ' </option>';		
				}
				
			}
        	$inpsupno= $inpsupno . ' </select>';
			
			$inpprtno="<input type=\"text\" name=\"vprtno\" style=\"width:200px;\"  value ='" . $xprtno. "' class=\"arial11black\" maxlength=\"30\" size=\"30\" ></input>";
		  ?>
		
        
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
    <td colspan="6" class="arial21redbold">Supplier Sales Price Maintenance</td>
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
   <form name ="searchprice" method="get">
     <fieldset style="width:97%">
<legend> &nbsp;Search Information</legend>
 <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
  	<td ><div align="right"><span class="arial12BoldGrey">Supplier Number</span></div></td>
    <td><div align="center"><span class="arial12Bold">:</span></div></td>
    <td><span class="arial12Bold"><? echo $inpsupno?></span></td>
    <td>&nbsp;</td>
    <td ><div align="right"><span class="arial12BoldGrey">Customer Number</span></div></td>
    <td><div align="center"><span class="arial12Bold">:</span></div></td>
    <td><span class="arial12Bold"><? echo $inpcusno?></span></td>
  </tr>
  <tr class="tbl1">
    <td class="arial12BoldGrey"><div align="right"></div></td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="16%" ><div align="right"><span class="arial12BoldGrey">Part Number</span></div></td>
    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
    <td width="20%"><span class="arial12Bold"><? echo $inpprtno ?></span></td>
    <td width="3%"><input type="submit" name="button" id="button" value="Search" class="arial11"></td>
    <td width="19%"></td>
    <td width="1%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 </table>
   </fieldset>
   </form>
<table width="100%" border="0" style="padding:10px;">
	<tr >
		<th width="10%" height="30" scope="col"></th>
		<th width="10%" height="30" scope="col"></th>
		<th width="10%" height="30" scope="col"></th>
		<th width="10%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="10%" scope="col" align="right">
			<!--<a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>-->
			<div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;width:150px;'>
				<img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:20px;margin-top:1px;'>
				<font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
			</div>
		</th>
 	</tr>
    <tr class="arial11blackbold">
        <th width="10%" height="10" scope="col"></th>
		<th width="10%" height="10" scope="col"></th>
		<th width="10%" height="10" scope="col"></th>
		<th width="10%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="20%" scope="col"></th>
		<th width="10%" scope="col"></th>
    </tr>
</table>

<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="10%"height="30"  scope="col">Company Code</th>
  	<th width="10%" height="30" scope="col">Supplier Number</th>
  	<th width="10%" height="30" scope="col">Customer</th>
    <th width="20%" height="30" scope="col">Item Number</th>
    <th width="10%" height="30" scope="col">Shipto</th>
    <th width="10%" height="30" scope="col">Currency</th>
    <th width="20%" height="30" scope="col">Sales Price</th>
    </tr>
    
     <?
		
		  
		  $per_page=10;
		  $num=5;
	$criteria=" where Owner_Comp='$comp' ";
	if(isset($_GET["button"])){
		$xcusno=$_GET["vcusno"];
		$xprtno=$_GET["vprtno"];
		$xsupno=$_GET["vsupno"];
		if(trim($xcusno)!=''){
			$criteria .= ' and Cusno="'.$xcusno.'"';	
		}
		if(trim($xprtno)!=''){
			$criteria .= ' and partno="'.$xprtno.'"';
		}
		if(trim($xsupno)!=''){
			$criteria .= ' and supno="'.$xsupno.'"';
		}
	}
	$query="select * from supprice ". $criteria;
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

	$query1="select * from supprice ". $criteria . " order by cusno".		
	       " LIMIT $start, $per_page";
  
	$sql=mysqli_query($msqlcon,$query1);	
	if( ! mysqli_num_rows($sql) ) {
		echo "<tr height=\"30\"><td colspan=\"7\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... */ . "</td></tr>";
	}
	//echo $query1;
			while($hasil = mysqli_fetch_array ($sql)){
				$vcomp=$hasil['Owner_comp'];
				$vsupno=$hasil['supno'];
				$vcusno=$hasil['Cusno'];
				$vcurcd=$hasil['curr'];
				$vitnbr=$hasil['partno'];
				$vpshipto=$hasil['shipto'];
				$vprice=$hasil['price'];
				
		echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vcomp."</td>";
		echo "<td>".$vsupno."</td><td>".$vcusno."</td><td>".$vitnbr."</td><td>".$vpshipto."</td>";
		echo "<td>".$vcurcd."</td><td class=\"lasttd\">".$vprice."</td>" ;
		echo "</tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";
		}
		
		
		  ?>
 
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
