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

			//let searchParams = new URLSearchParams(window.location.search)
			//let supno = searchParams.get('supno');
			let supno = $.urlParam('supno');
			url= 'sup_gettblXLS.php?supno='+ supno,
			window.open(url);	
					
		 });		   
   });

	function deleteSupno(supno){
		//alert(supno);
		$.ajax({
			type: 'GET',
			url: 'sup_checkcusref.php',
			data: "supno="+ supno,
			success: function(data) {
				if(data == "yes"){
					var result = confirm("Are you sure you want to delete?");
					if (result) {
						//Logic to delete the item
						url= 'sup_updmas.php?action=delete&id='+ supno,
						window.open(url);	
					}
				}
				else{
					alert(data);
					//if(confirm(data))) document.location = 'sup_mainref.php';
				}
			}
							
		})
		
	}
</script>



	</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->
		
		<?
		  require('../db/conn.inc');
		 
		    $inpsupmas= '<select name="supno" id="supno" class="arial11blue" style="width:200px">';
			$inpsupmas= $inpsupmas .  ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
			$query="select distinct supno from supmas where Owner_Comp='$comp' order by supno ";
			$sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
                $supmasno=$_GET['supno'];
                $selected = ($hasil["supno"] == $supmasno) ? "selected" : "";
				$supno=$hasil['supno'];
				$inpsupmas= $inpsupmas .  ' <option '.$selected.'  value="'.$supno.'">'.$supno.'</option>';		
				
			}
        	$inpsupmas= $inpsupmas . ' </select>';
			
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
			  	$_GET['current']="sup_mainadm";
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
    <td colspan="6" class="arial21redbold"> Supplier Maintenance</td>
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
                    <td width="16%" ><div align="right"><span class="arial12BoldGrey"><?php  echo get_lng($_SESSION["lng"], "L0451"); ?></span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $inpsupmas ?></span></td>
                    <td width="2%"></td>
                    <td width="19%"><input type="submit" name="button" id="button" value="Search" class="arial11"></td>
                    <td width="1%">&nbsp;</td>
                    <td width="45%">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td></td>
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

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                <th scope="col">&nbsp;</th>
                <th width="90" scope="col">
					<a href="sup_import.php" id="ImportExel" style='text-decoration-line: none;'>
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
					<a href="sup_mas.php?action=add" id="new" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/new.png" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0006"); ?></font>
                        </div>
                    </a>
                </th>
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
            </table>
		<!--
		<a href="sup_import.php" id="ImportExel"><img src="../images/importxls.gif" width="80" height="20" border="0"></a>
		<a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>
    	<a href="sup_mas.php?action=add" id="new"><img src="../images/newtran.png" width="80" height="20"></a>-->

<!-- start Modify -->
<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="9%" height="30" scope="col">Company Code</th>
  	<th width="9%" height="30" scope="col">Supplier Number</th>
  	<th width="22%" height="30" scope="col">Supplier Name</th>
    <th width="25%" scope="col">Address1</th>
    <th width="25%" scope="col">Address2</th>
    <th width="10%" scope="col">Action</th>
    </tr>
    
     <?
		  require('../db/conn.inc');
		  
		  $per_page=10;
		  $num=5;
		  
	$query="select * from supmas where Owner_comp='$comp'"; // eidt by CTC
	
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
	$criteria=" where Owner_Comp='$comp' ";
    
	$xsupno=$_GET["supno"];
	if(isset($xsupno)){
        if(trim($xsupno)!=''){
             $criteria .= ' and supno="'.$xsupno.'"';        
        }
    }
    $query="select * from supmas". $criteria;


	$query1="select * from supmas  $criteria  order by supno LIMIT $start, $per_page"; // edit by CTC
	$sql=mysqli_query($msqlcon,$query1);	
			if( ! mysqli_num_rows($sql) ) {
				echo "<tr height=\"30\"><td colspan=\"6\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
			}
			while($hasil = mysqli_fetch_array ($sql)){
				$vowner=$hasil['Owner_comp'];
				$vsupno=$hasil['supno'];
				$vsupnm=$hasil['supnm'];
				$vsupadd1=$hasil['add1'];
				$vsupadd2=$hasil['add2'];
				/*
				$valias=$hasil['alias'];
				if($valias=='')$valias='-';
				$vtype=trim($hasil['Custype']);
				*/
				echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vowner."</td><td>".$vsupno."</td><td>".$vsupnm."</td><td>".$vsupadd1."</td>" ;
				echo "<td>".$vsupadd2."</td>";
				
				echo "<td class=\"lasttd\">";
				echo "<a href='sup_mas.php?action=view&supno=".$vsupno."' > <img src=\"../images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a> ";
				echo "<a href='sup_mas.php?action=edit&supno=".$vsupno."' > <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a> ";
				//echo "<a href='sup_updmas.php?action=delete&id=".$vsupno."' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
				echo "<a  href=\"#\" onclick=\"deleteSupno('".$vsupno."')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
				echo "<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"6\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";

		}
		
		
		  ?>
 
 <tr>
    <td colspan="6"  align="right" class="lasttd" >
		<img src="../images/view.png" width="20" height="20"><span class="arial11redbold"> =view</span>
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
