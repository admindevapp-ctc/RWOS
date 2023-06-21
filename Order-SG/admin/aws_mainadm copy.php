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
    body { font-size: 62.5%; }
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
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


		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			width: 500,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			close: function() {
			}
		});

        $( ".view" ).click(function() {

			pos =$( this ).attr("id");
			//alert(pos);
			$( "#dialog-form" ).attr("title","Part Detail");
            //id='".$vcusno1."||".$vitnbr."||".$vitdsc."
			var xdata=pos.split("||");
			var xcusno1 = xdata[0];
			var xpartno=xdata[1];
			var xpartnm=xdata[2];

            $.ajax({
            type: 'POST',
            url: 'aws_partdetail.php',
            data: {
                vCusno1:xcusno1,
                vPartno:xpartno
            },
            success: function(data) {
                console.log(data);
               // alert(data);
                $("#p_partno").text(xpartno);
                $("#p_partname").text(xpartnm);
                $("#part_detail").html(data);
                $('#dialog-form').dialog('open');
            }
            });
		});
		



		$('#ConvExcel').click(function(){
            //s_cusno1=&s_cusgrp2=TX1&s_partnumber=&s_product=&s_partname=&s_condition=&button=Search
			let s_cusno1 = $.urlParam('s_cusno1');
			let s_cusgrp2 = $.urlParam('s_cusgrp2');
			let s_partnumber = $.urlParam('s_partnumber');
			let s_product = $.urlParam('s_product');
			let s_partname = $.urlParam('s_partname');
			let s_condition = $.urlParam('s_condition');
			url= 'aws_mainexc_gettblXLS.php?s_cusno1='+ s_cusno1 + '&s_cusgrp2=' +s_cusgrp2 +
             '&s_partnumber=' + s_partnumber + '&s_product=' +s_product +
             '&s_partname=' + s_partname + '&s_condition=' +s_condition;
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
		 
		  $cusno1= '<select name="s_cusno1" id="s_cusno1" class="arial11blue" style="width: 100%" >';
		  $cusno1= $cusno1 .  ' <option value="">select option</option>';
          
		  $query="SELECT distinct cusno1
		  	FROM awsexc
			where Owner_Comp='$comp'";
            
		  $sql=mysqli_query($msqlcon,$query);	
		  while($hasil = mysqli_fetch_array ($sql)){
			$ycusno=$hasil['cusno1'];
			  $cusno1= $cusno1 .  ' <option value="'.$ycusno.'">'.$ycusno. '</option>';
		  }
		  $cusno1= $cusno1 . ' </select>';

		  $cusgrp2= '<select name="s_cusgrp2" id="s_cusgrp2" class="arial11blue"  style="width: 100%">';
		  $cusgrp2= $cusgrp2 .  ' <option value="">select option</option>';
		  $query1="select distinct cusgrp from awsexc where Owner_Comp='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			    $ycusgrp2=$hasil['cusgrp'];
			  $cusgrp2= $cusgrp2 .  ' <option value="'.$ycusgrp2.'">'.$ycusgrp2. '</option>';
		  }
		  $cusgrp2= $cusgrp2 . ' </select>';


          $product= '<select name="s_product" id="s_product" class="arial11blue"  style="width: 100%">';
		  $product= $product .  ' <option value="">select option</option>';
		  $query1="select distinct Product from bm008pr where Owner_Comp='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			    $yProduct=$hasil['Product'];
			  $product= $product .  ' <option value="'.$yProduct.'">'.$yProduct. '</option>';
		  }
		  $product= $product . ' </select>';

          $condition= '<select name="s_condition" id="s_condition" class="arial11blue"  style="width: 100%">';
		  $condition= $condition .  ' <option value="">select option</option>';
		  $query1="select distinct sell from awsexc where Owner_Comp='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			    $ysell=$hasil['sell'];
			  $condition= $condition .  ' <option value="'.$ysell.'">'.$ysell. '</option>';
		  }
		  $condition= $condition . ' </select>';
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
			  	$_GET['current']="aws_mainadm";
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
    <td colspan="6" class="arial21redbold"> 2 <sup>nd</sup> Customer Sales Condition MA (DENSO)</td>
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
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">2 <sup>nd</sup> Customer Group</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $cusgrp2 ?></span></td>
                    <td colspan="3" width="32%">&nbsp;</td>
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
                <tr class="arial11blackbold">
                <td width="16%"><div align="right"><span class="arial12BoldGrey">Part Number</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><input type="text" name="s_partnumber" id="s_partnumber" style="width: 100%"/></span></td>
                    <td width="2%"></td>
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">Product</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $product ?></span></td>
                    <td colspan="3" width="32%">&nbsp;</td>
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
                <tr class="arial11blackbold">
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">Part Name</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><input type="text" name="s_partname" id="s_partname" style="width: 100%"/></span></td>
                    <td width="2%"></td>
                    <td width="16%"><div align="right"><span class="arial12BoldGrey">Condition</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $condition ?></span></td>
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
                <th width="90"></th>
				<th width="10"></th>
				<th width="10"></th>
                <th width="140" scope="col">
                    <div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
                        <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                        <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
                    </div>
                </th>
            </tr>
            <tr height="5"><td colspan="5"></td><tr>
        </table>

<!-- start Modify -->
<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="5%" height="30" scope="col">Company Code</th>
  	<th width="14%" height="30" scope="col">1 <sup>st </sup> Customer Code</th>
    <th width="8%" scope="col">Product</th>
    <th width="8%" scope="col">Part Number</th>
    <th width="8%" scope="col">Part Name</th>
    <th width="8%" scope="col">Condition</th>
  	<th width="14%" height="30" scope="col" class=\"lasttd\" >2 <sup>nd </sup> Customer Group</th>
  </tr>
    
 <?
	require('../db/conn.inc');
		  
	$per_page=10;
	$num=5;
	$criteria=" where awsexc.Owner_Comp='$comp' group by  awsexc.Owner_Comp, cusno1, trim(awsexc.itnbr) ";
	if(isset($_GET["button"])){
		$xcusno1=$_GET["s_cusno1"];
		$xcusgrp2=$_GET["s_cusgrp2"];
		$xpartnumber=$_GET["s_partnumber"];
		$xproduct=$_GET["s_product"];
		$xpartname=$_GET["s_partname"];
		$xcondition=$_GET["s_condition"];
		if(trim($xcusno1)!=''){
			$criteria .= ' and cusno1="'.$xcusno1.'"';	
		}
		if(trim($xcusgrp2)!=''){
			$criteria .= ' and cusgrp="'.$xcusgrp2.'"';
		}
		if(trim($xpartnumber)!=''){
			$criteria .= ' and trim(awsexc.itnbr)="'.$xpartnumber.'"';
		}
		if(trim($xproduct)!=''){
			$criteria .= ' and Product="'.$xproduct.'"';
		}
		if(trim($xpartname)!=''){
			$criteria .= ' and ITDSC="'.$xpartname.'"';
		}
		if(trim($xcondition)!=''){
			$criteria .= ' and sell="'.$xcondition.'"';
		}
	}
	$query="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
        case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
    FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) ". $criteria;
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
	     
	$query1="SELECT awsexc.Owner_Comp, cusno1, Product, trim(awsexc.itnbr) as itnbr, ITDSC,   
    case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, price, curr
    FROM awsexc left join bm008pr on trim(awsexc.itnbr) = trim(bm008pr.ITNBR) ". $criteria . "order by cusno1".		
	       " LIMIT $start, $per_page";
	//echo $query1;
	$sql=mysqli_query($msqlcon,$query1);	
			if( ! mysqli_num_rows($sql) ) {
				echo "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
			}
			while($hasil = mysqli_fetch_array ($sql)){
				$vowner=$hasil['Owner_Comp'];
				$vcusno1=$hasil['cusno1'];
				$vproduct=$hasil['Product'];
				$vitnbr=$hasil['itnbr'];
				$vitdsc=$hasil['ITDSC'];
				$vsell=$hasil['sell'];
				$vcusgrp=$hasil['cusgrp'];
				$vprice=$hasil['price'];
				$vcurr=$hasil['curr'];
				
				
				echo "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
                echo "<td>".$vowner."</td>";
                echo "<td>".$vcusno1."</td>";
				echo "<td>".$vproduct."</td>";
				echo "<td><a href='#' class='view' id='".$vcusno1."||".$vitnbr."||".$vitdsc."'>".$vitnbr."</a></td>";
				echo "<td>".$vitdsc."</td>";
				echo "<td>".$vsell."</td>";
				echo "<td class=\"lasttd\">".substr($vcusgrp,0,12) ."...</td>";
                echo "</tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
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
            </p>
        </div>
    </div>

    <div id="dialog-form" title="Part Detail">
	<form>
		<fieldset>
            <span class="arial12Bold">Part Number </span> : <span class="arial12Grey" id="p_partno"></span>
            <span class="arial12Bold">Part Name </span>: <span class="arial12Grey" id="p_partname"></span>
            <br/><br/>
            <div id="part_detail"></div>
		</fieldset>
	</form>
       
 </div>
</div>



</body>
</html>
