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

    #data tr {  
  display: none;  
}  
.page {  
margin-top: 50px;  
}  
#data {  
  font-family: Arial, Helvetica, sans-serif;  
  border-collapse: collapse;  
  width: 100%;  
}  
#data td, #data th {  
  border: 1px solid #ddd;  
  padding: 8px;  
}  
#data tr:nth-child(even){ background-color: #f2f2f2; }  
  
#data tr:hover {  
background-color: #ddd;  
}  
#data th {  
  padding-top: 12px;  
  padding-bottom: 12px;  
  text-align: left;  
  background-color: #f6a828;  
  color: white;  
}  
#nav {  
  margin-bottom: 1em;  
  margin-top: 1em;  
}  
#nav a {  
  font-size: 10px;  
  margin-top: 22px;  
  font-weight: 600;    
  padding-top: 30px;  
  padding-bottom: 10px;  
  text-align: center;  
  background-color: #e78f08;  
  color: white;  
  padding: 6px;  

}  
a:hover, a:visited, a:link, a:active {  
    text-decoration: none;  
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
			var xsupcd=xdata[3];
			var xcusgrp=xdata[4];

            $.ajax({
            type: 'POST',
            url: 'aws_sup_partdetail.php',
            data: {
                vCusno1:xcusno1,
                vPartno:xpartno,
                vSupcd:xsupcd,
                vCusgrp:xcusgrp
            },
            success: function(data) { 
                //alert(data);
                $("#p_partno").text(xpartno);
                $("#p_partname").text(xpartnm);
                //$("#part_detail").html(data);
                $("#part_detail").html(data);


                $('#data').after ('<div id="nav" align="right"></div>');  
                    var rowsShown = 5;  
                    var rowsTotal = $('#data tbody tr').length;  
                
                    var numPages = rowsTotal/rowsShown;  
                    for (i = 0;i < numPages;i++) {  
                        var pageNum = i + 1;  
                        $('#nav').append ('<a href="#" rel="'+i+'">'+pageNum+'</a> ');  
                    }  
                    $('#data thead tr').show(); 
                    $('#data tbody tr').hide();  
                    $('#data tbody tr').slice (0, rowsShown).show();  
                    $('#nav a:first').addClass('active');  
                    $('#nav a').bind('click', function() {  
                    $('#nav a').removeClass('active');  
                    $(this).addClass('active');  
                        var currPage = $(this).attr('rel');  
                        var startItem = currPage * rowsShown;  
                        var endItem = startItem + rowsShown;  
                        $('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).  
                        css('display','table-row').animate({opacity:1}, 300);  
                    }); 

                $('#dialog-form').dialog('open');
            }
            });
			
		});
		



		$('#ConvExcel').click(function(){
            //s_cusno1=&s_cusgrp2=TX1&s_partnumber=&s_product=&s_partname=&s_condition=&button=Search
			let s_cusno1 = $.urlParam('s_cusno1');
			let s_cusgrp2 = $.urlParam('s_cusgrp2');
			let s_supcode = $.urlParam('s_supcode');
			let s_partnumber = $.urlParam('s_partnumber');
			let s_brand = $.urlParam('s_brand');
			let s_partname = $.urlParam('s_partname');
			let s_condition = $.urlParam('s_condition');

			url= 'aws_mainsupexc_gettblXLS.php?s_cusno1='+ s_cusno1 + '&s_cusgrp2=' +s_cusgrp2 + '&s_supcode=' +s_supcode +
             '&s_partnumber=' + s_partnumber + '&s_brand=' +s_brand +
             '&s_partname=' + s_partname + '&s_condition=' +s_condition;
			//alert(url);
			window.open(url);	
		 });	



		$('#button-search').click(function() {
			$('.search_text').hide();
			$('.tbl1 .data_res1').empty().html('<tr><td colspan="8"><div align="center" width="100%"><img src="images/35.gif" height="20"/></div></td></tr>');
			updatetbl();
			paging(1);
			
		});
		async function updatetbl() {
			let cusno1 = $('#s_cusno1').val();
			let cusgrp = $('#s_cusgrp2').val();
			let product = $('#s_brand').val();
			let part_no = $('#s_partnumber').val();
			let condition = $('#s_condition').val();
			let part_name = $('#s_partname').val();
			let supcode = $('#s_supcode').val();
			let data = {
				'cusno1' : cusno1,
				'cusgrp': cusgrp,
				'brand': product,
				'supcode': supcode,
				'part_no': part_no,
				'condition': condition,
				'part_name': part_name,
			};
			$.ajax({
				type: 'POST',
				url: 'aws_salenondenso_adm_tblupdate.php',
				data: data,
				success: await function(data) {
					//alert(data);
					// console.log(data);
					$('.tbl1 .data_res1').html('');
					$('.tbl1 .data_res1').html(data);
				}
			})
		}		 
	});// doc ready
async function paging(x=1) {
	let cusno1 = $('#s_cusno1').val();
	let cusgrp = $('#s_cusgrp2').val();
	let product = $('#s_brand').val();
	let part_no = $('#s_partnumber').val();
	let condition = $('#s_condition').val();
	let part_name = $('#s_partname').val();
	let supcode = $('#s_supcode').val();
	let page = x;
	let data = {
		'cusno1' : cusno1,
		'cusgrp': cusgrp,
		'brand': product,
		'supcode': supcode,
		'part_no': part_no,
		'condition': condition,
		'part_name': part_name,
		'page' : page,
	};
	$('.tbl1 .data_res1').empty().html('<tr><td colspan="8"><div align="center" width="100%"><img src="../images/35.gif" height="20"/></div></td></tr>');

	$.ajax({
		type: 'POST',
		url: 'aws_salenondenso_adm_tblupdate.php',
		data: data,
		success: await function(data) {
			$('.tbl1 .data_res1').html('');
			$('.tbl1 .data_res1').html(data);
		}
	})
	$.ajax({
		type: 'POST',
		url: 'aws_salenondenso_adm_tblpage.php',
		data: data,
		success: function(data) {
			// console.log(data);
			$('#pagination').html(data);
		}
	});
}

function view_edit(e = null) {
	// console.log(e);
	pos = $(e).attr("id");
	console.log(pos);

	//alert(pos);
	$("#dialog-form").attr("title", "Part Detail");
	//id='".$vcusno1."||".$vitnbr."||".$vitdsc."
	var xdata=pos.split("||");
	var xcusno1 = xdata[0];
	var xpartno=xdata[1];
	var xpartnm=xdata[2];
	var xsupcd=xdata[3];
	var xcusgrp=xdata[4];
	$("#p_cusno").val(xcusno1);
	$.ajax({
	type: 'POST',
	url: 'aws_sup_partdetail.php',
	data: {
		vCusno1:xcusno1,
		vPartno:xpartno,
		vSupcd:xsupcd,
		vCusgrp:xcusgrp
	},
	success: function(data) { 
		//alert(data);
		$("#p_partno").text(xpartno);
		$("#p_partname").text(xpartnm);
		//$("#part_detail").html(data);
		$("#part_detail").html(data);


		$('#data').after ('<div id="nav" align="right"></div>');  
			var rowsShown = 5;  
			var rowsTotal = $('#data tbody tr').length;  
		
			var numPages = rowsTotal/rowsShown;  
			for (i = 0;i < numPages;i++) {  
				var pageNum = i + 1;  
				$('#nav').append ('<a href="#" rel="'+i+'">'+pageNum+'</a> ');  
			}  
			$('#data thead tr').show(); 
			$('#data tbody tr').hide();  
			$('#data tbody tr').slice (0, rowsShown).show();  
			$('#nav a:first').addClass('active');  
			$('#nav a').bind('click', function() {  
			$('#nav a').removeClass('active');  
			$(this).addClass('active');  
				var currPage = $(this).attr('rel');  
				var startItem = currPage * rowsShown;  
				var endItem = startItem + rowsShown;  
				$('#data tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).  
				css('display','table-row').animate({opacity:1}, 300);  
			}); 

		$('#dialog-form').dialog('open');
	}
	});
}

</script>



	</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->
		
		<?
		  require('../db/conn.inc');
		 
		  $cusno1= '<select name="s_cusno1" id="s_cusno1" class="arial11blue" style="width: 100%" >';
		  $cusno1= $cusno1 .  ' <option value="">Select option</option>';
          
		  $query="SELECT distinct cusno1
		  	FROM supawsexc
			where Owner_Comp='$comp'";
            
		  $sql=mysqli_query($msqlcon,$query);	
		  while($hasil = mysqli_fetch_array ($sql)){
			    $ycusno=$hasil['cusno1'];
                $scusno1=$_GET['s_cusno1'];
                $selected1 = ($hasil["cusno1"] == $scusno1) ? "selected" : "";
			    $cusno1= $cusno1 .  ' <option value="'.$ycusno.'" '.$selected1.'>'.$ycusno. '</option>';
		  }
		  $cusno1= $cusno1 . ' </select>';


		  $supcode= '<select name="s_supcode" id="s_supcode" class="arial11blue" style="width: 100%" >';
		  $supcode= $supcode .  ' <option value="">Select option</option>';
          
		  $query="SELECT distinct supcode
		  	FROM supawsexc
			where Owner_Comp='$comp'";
            
		  $sql=mysqli_query($msqlcon,$query);	
		  while($hasil = mysqli_fetch_array ($sql)){
			    $ysupcode=$hasil['supcode'];
                $ssupcode=$_GET['s_supcode'];
                $selected2 = ($hasil["supcode"] == $ssupcode) ? "selected" : "";
			    $supcode= $supcode .  ' <option value="'.$ysupcode.'" '.$selected2.'>'.$ysupcode. '</option>';
		  }
		  $supcode= $supcode . ' </select>';

		  $cusgrp2= '<select name="s_cusgrp2" id="s_cusgrp2" class="arial11blue"  style="width: 100%">';
		  $cusgrp2= $cusgrp2 .  ' <option value="">Select option</option>';
		  $query1="select distinct cusgrp from supawsexc where Owner_Comp='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			    $ycusgrp2=$hasil['cusgrp'];
                $scusgrp2=$_GET['s_cusgrp2'];
                $selected3 = ($hasil["cusgrp"] == $scusgrp2) ? "selected" : "";
			    $cusgrp2= $cusgrp2 .  ' <option value="'.$ycusgrp2.'" '.$selected3.'>'.$ycusgrp2. '</option>';
		  }
		  $cusgrp2= $cusgrp2 . ' </select>';


          $brand= '<select name="s_brand" id="s_brand" class="arial11blue"  style="width: 100%">';
		  $brand= $brand .  ' <option value="">Select option</option>';
		  $query1="SELECT distinct brand 
          FROM `supcatalogue`  join supawsexc on supcatalogue.Owner_Comp = supawsexc.Owner_Comp and supcatalogue.Prtno = supawsexc.prtno
          WHERE supawsexc.`Owner_Comp`='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			    $ybrand=$hasil['brand'];
                $sbrand=$_GET['s_brand'];
                $selected4 = ($hasil["brand"] == $sbrand) ? "selected" : "";
			  $brand= $brand .  ' <option value="'.$ybrand.'" '.$selected4.'>'.$ybrand. '</option>';
		  }
		  $brand= $brand . ' </select>';

          $condition= '<select name="s_condition" id="s_condition" class="arial11blue"  style="width: 100%">';
		  $condition= $condition .  ' <option value="">Select option</option>';
		  $query1="select distinct sell, case when sell = 1 then 'Sell' else 'Not Sell' end sellText from supawsexc where Owner_Comp='$comp' ";
		  $sql1=mysqli_query($msqlcon,$query1);	
		  while($hasil = mysqli_fetch_array ($sql1)){
			    $ysell=$hasil['sell'];
			    $ysellText=$hasil['sellText'];
                $scondition=$_GET['s_condition'];
                $selected5 = ($hasil["sell"] == $scondition) ? "selected" : "";
			  $condition= $condition .  ' <option value="'.$ysell.'" '.$selected5.'>'.$ysellText. '</option>';
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
			  	$_GET['current']="aws_mainadm_nondenso";
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
    <td colspan="6" class="arial21redbold"> 2 <sup>nd</sup> Customer Sales Condition MA (NON DENSO)</td>
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
                    <td width="15%"><div align="right"><span class="arial12BoldGrey">1 <sup>st</sup> Customer Code</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $cusno1 ?></span></td>
                    <td width="2%"></td>
                    <td width="15%"><div align="right"><span class="arial12BoldGrey">2 <sup>nd</sup> Customer Group</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $cusgrp2 ?></span></td>
                    <td width="2%"></td>
                    <td width="15%"><div align="right"><span class="arial12BoldGrey"> Condition</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $condition ?></span></td
                </tr>
                <tr class="arial11blackbold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td width="15%"><div align="right"><span class="arial12BoldGrey">Part Number</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><input type="text" name="s_partnumber" id="s_partnumber" 
					<?php
					if(isset($_GET["button"])){
						$xpartnumber=$_GET["s_partnumber"];
						echo "value = '$xpartnumber'";
					}
					?>
					style="width: 100%"/></span></td>
                    <td width="2%"></td>
                    <td width="15%"><div align="right"><span class="arial12BoldGrey">Suppiler</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $supcode ?></span></td>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td width="15%"><div align="right"><span class="arial12BoldGrey">Part Name</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><input type="text" name="s_partname" id="s_partname" 
					<?php
					if(isset($_GET["button"])){
						$xpartnamer=$_GET["s_partname"];
						echo "value = '$xpartnamer'";
					}
					?>
					style="width: 100%"/></span></td>
                    <td width="2%"></td>
                    <td width="15%"><div align="right"><span class="arial12BoldGrey">Brand</span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $brand ?></span></td>
                    <td width="2%"></td>
                    <td width="15%"></td>
                    <!--<td colspan="2"><input type="submit" name="button" id="button" value="Search" class="arial11" style="display:none;"></td>-->
					<td colspan="2"><input type="button" name="button-search" id="button-search" value="Search" class="arial11" style="display:;"></td>

                </tr>
                <tr class="arial11blackbold">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
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
            	<th scope="col" align="left"><div class="arial21redbold" style="margin: 0px;">*Please select search information to view the MA</div></th>
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
	<thead>
	  <tr class="arial11whitebold" bgcolor="#AD1D36" >
		<th width="5%" height="30" scope="col">Company Code</th>
		<th width="10%" height="30" scope="col">1 <sup>st </sup> Customer Code</th>
		<th width="8%" scope="col">Supplier</th>
		<th width="5%" scope="col">Brand</th>
		<th width="12%" scope="col">Part Number</th>
		<th width="20%" scope="col">Part Name</th>
		<th width="5%" scope="col">Condition</th>
		<th width="8%" height="30" scope="col" class=\"lasttd\" >2 <sup>nd </sup> Customer Group</th>
	  </tr>
    
 <?
	// require('../db/conn.inc');
		  
	// $per_page=10;
	// $num=5;
	// $criteria=" where supawsexc.Owner_Comp='$comp' ";
	// if(isset($_GET["button"])){


		// $xcusno1=$_GET["s_cusno1"];
		// $xcusgrp2=$_GET["s_cusgrp2"];
		// $xpartnumber=$_GET["s_partnumber"];
		// $xpartname=$_GET["s_partname"];
		// $xbrand=$_GET["s_brand"];
		// $xsupcode=$_GET["s_supcode"];
		// $xcondition=$_GET["s_condition"];
		// if(trim($xcusno1)!=''){
			// $criteria .= ' and supawsexc.cusno1="'.$xcusno1.'"';	
		// }
		// if(trim($xcusgrp2)!=''){
			// $criteria .= ' and supawsexc.cusgrp="'.$xcusgrp2.'"';
		// }
		// if(trim($xpartnumber)!=''){
			// $criteria .= ' and supawsexc.prtno like "%'.$xpartnumber.'%"';
		// }
		// if(trim($xpartname)!=''){
			// $criteria .= ' and supcatalogue.Prtnm like "%'.$xpartname.'%"';
		// }
		// if(trim($xbrand)!=''){
			// $criteria .= ' and  supcatalogue.Brand ="'.$xbrand.'"';
		// }
		// if(trim($xsupcode)!=''){
			// $criteria .= ' and  supawsexc.supcode ="'.$xsupcode.'"';
		// }
		// if(trim($xcondition)!=''){
			// $criteria .= ' and supawsexc.sell="'.$xcondition.'"';
		// }
	// }
	// $criteria .= " group by supawsexc.Owner_Comp, cusno1,supawsexc.supcode, trim(supawsexc.prtno)";
	// $query="SELECT 1
    // FROM supawsexc 
    // -- left join supcatalogue on trim(supcatalogue.Prtno) = trim(supawsexc.prtno)
    // -- left join supmas on supmas.supno = supawsexc.supcode
    // -- left join supprice on supprice.Cusno = supawsexc.cusno1  and supawsexc.prtno = supprice.partno 
	// ". $criteria;
	// //echo $query;
	// $sql=mysqli_query($msqlcon,$query);
	// $count = mysqli_num_rows($sql);
	
	// $pages = ceil($count/$per_page);
	// $page = $_GET['page'];
	// if($page){ 
		// $start = ($page - 1) * $per_page; 			
	// }else{
		// $start = 0;	
		// $page=1;
	// }
	     
	// $query1="SELECT distinct supawsexc.Owner_Comp, supawsexc.cusno1, supawsexc.supcode, supmas.supnm,   supawsexc.prtno, supcatalogue.Prtnm, supcatalogue.Brand,
            // case when sell = 1 then 'Sell' else 'Not Sell' end sell, group_concat(cusgrp ORDER BY cusgrp ASC) as cusgrp, supprice.price, supawsexc.curr
        // FROM supawsexc 
        // left join supcatalogue on trim(supcatalogue.Prtno) = trim(supawsexc.prtno)
        // left join supmas on supmas.supno = supawsexc.supcode
        // left join supprice on supprice.Cusno = supawsexc.cusno1  and supawsexc.prtno = supprice.partno ". $criteria . " order by supawsexc.cusno1".		
	       // " LIMIT $start, $per_page";
	// //echo $query1;
	// $sql=mysqli_query($msqlcon,$query1);	
			// if( ! mysqli_num_rows($sql) ) {
				// echo "<tr height=\"30\"><td colspan=\"12\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... ! */ . "</td></tr>";
			// }
			// while($hasil = mysqli_fetch_array ($sql)){
				// $vowner=$hasil['Owner_Comp'];
				// $vcusno1=$hasil['cusno1'];
				// $vsupname=$hasil['supnm'];
				// $vbrand=$hasil['Brand'];
				// $vprtno=$hasil['prtno'];
				// $vprtnm=$hasil['Prtnm'];
				// $vsupnm=$hasil['supnm'];
				// $vsell=$hasil['sell'];
				// $vsupcd=$hasil['supcode'];
				// $vcusgrp=$hasil['cusgrp'];
				// if(strlen($vcusgrp) > 12){
                    // $vcusgrp = substr($vcusgrp,0,12) ."...";
                // }
				
				// echo "<tr class=\"arial11black\" align=\"center\" height=\"30\">";
                // echo "<td>".$vowner."</td>";
                // echo "<td>".$vcusno1."</td>";
				// echo "<td>".$vsupnm."</td>";
				// echo "<td>".$vbrand."</td>";
				// echo "<td><a href='#' class='view' id='".$vcusno1."||".$vprtno."||".$vprtnm."||".$vsupcd."||".$vcusgrp."'>".$vprtno."</a></td>";
				// echo "<td>".$vprtnm."</td>";
				// echo "<td>".$vsell."</td>";
				// echo "<td class=\"lasttd\">".$vcusgrp."</td>";
                // echo "</tr>";
			
			// }
			
			// require('pagerx.php');
		// if($count>$per_page){		
		  	// echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	// //echo $query;
		  	// $fld="page";
		  	// pagingx($query,$per_page,$num,$page);
		  	// echo "</div></td></tr>";

		// }
		
		
		  ?>
	</thead>
		<tbody class="data_res1">
		</tbody>
		<tr align="right" valign="middle" height="30" class="search_text">
			<td colspan="12" class="lastpg">
				<div class="search_text" style="width:100%; text-align:center;"> Please search data </div>
			</td>
		</tr>
		<tr align="right" valign="middle" height="30">
			<td colspan="12" class="lastpg">
				<div id="pagination"> </div>
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
