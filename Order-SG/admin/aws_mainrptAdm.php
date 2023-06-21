<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='a'){
			header("Location:../main.php");
		}
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{
	header("Location:../../login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

 <style type="text/css">
#example tbody {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
}
#example thead{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	
}
#example thead tr th{
	border-bottom-width: 2px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
	background-image: url(images/thbg.png);
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #B00;
}

#example tbody tr td table tr td{
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
		
}

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#0063DC;
	text-decoration: none;
	background-color: #E8E8E8;
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
</style>

<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">	
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

	<link rel="stylesheet" href="../css/demos.css">  
 <script type="text/javascript" charset="utf-8">
			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
$(document).ready(function() {
									   
		$( "#orderdatefrom" ).datepicker({
       		dateFormat: 'dd/mm/yy', 
			changeMonth: true,
			changeYear: true
		});					   
		$( "#orderdateto" ).datepicker({
       		dateFormat: 'dd/mm/yy',
			changeMonth: true,
			changeYear: true
		});									   
									   
			var choose = $( "#OptChoose" ),
			field= $( "#OptField" ),
			desc= $( "#description" ),
			allFields = $( [] ).add(field).add(choose).add(desc),
			tips = $( ".validateTips" );
		
				$('#thnprd').change(function(){updatetbl('','','','','')});		
				
				$('#cmdGo').click(function(){
					updatetbl('','','','','');
					globalsearch = '';
				
				})
				
				$('#ConvExcel').click(function(){
					var fromd=$( "#orderdatefrom" ).val();						   
					var tod=$( "#orderdateto" ).val();
					var d=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var m=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var d=tod.substr(0,2);
					var y=tod.substr(6,4);
					var m=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('Date to should be greater than date from!');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		
											
						var edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&search="+globalsearch+"&choose="+globalchoose+"&desc="+globaldesc;
						url= 'aws_gettblorderXLS.php?'+edata;
						window.open(url);	
					
			  })
			  


			  function updatetbl(namafield, order, searchfield, choose, desc){
				var fromd=$( "#orderdatefrom" ).val();
					var tod=$( "#orderdateto" ).val();
					var d=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var m=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var d=tod.substr(0,2);
					var y=tod.substr(6,4);
					var m=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('Date to should be greater than date from!');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		

	
				edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&namafield="+namafield+"&sort="+order+"&search="+searchfield+"&choose="+choose+"&description="+desc;
					//	alert(edata);
                        
						$('#result1').empty().html('<div align="center"><img src="../images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'aws_gettblorderall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'aws_gettblorderpgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
					
				}
			
		$("#search").click(function(){
			$( "#dialog-form" ).dialog( "open" );							
		})
	
		$("#OptField").change(function() {
			if($("#OptField").val() == "status"){
				$("#OptChoose").attr('disabled','disabled');
				$("#description").hide();
				$("<select id='ssl_status'><option value= '1,2'>Complete</option>"+
				"<option value=''>Pending</option>"+
				"<option value='R'>Reject</option></select>").insertAfter("#OptChoose");
			}
			else{
				$('#OptChoose').removeAttr('disabled');
				$("#description").show();
				$('#ssl_status').hide();
			}
		});

		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height:200,
			width: 450,
			modal: true,
			buttons: {
				"Search": function() {
				var data;					
				var edata;
				var bValid;
				var vfield=$('#OptField').val();
				var vchoose=$('#OptChoose').val();
				var vdesc=$('#description').val();
				if(vfield==""){
					data="Please choose field to search";
					$('#optfield').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					bValid="";
					return false;
				}else{
					$('#optfield').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				
				}
				if(vchoose==""){
					if(vfield != "status"){
						data="Please choose criteria to search";
						$('#optchoose').addClass( "ui-state-error" );
						$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
						return false;
						bValid="";
					}
				}else{
					$('#optchoose').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				if(vdesc==""){
					if(vfield != "status"){
						data="Please fill description to search";
						$('#description').addClass( "ui-state-error" );
						$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
						return false;
						bValid="";
					}
				}else{
					$('#description').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				
				
			   if ( bValid =="1") {
					globalsearch=vfield;
					globalchoose=vchoose;
					if(vfield=="status"){
						globalchoose="in";
						globaldesc=$("#ssl_status").val();
					}
					else{
						globaldesc=vdesc;
					}	
					updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
									
					}
				},
				Cancel: function() {
					
					$('#OptChoose').removeAttr('disabled');
					$("#description").show();
					$('#ssl_status').hide();
					allFields.val( "" ).removeClass( "ui-state-error" );
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				$('#OptChoose').removeAttr('disabled');
				$("#description").show();
				$('#ssl_status').hide();
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
									   
								   
		   });
			
			 function paging(x){
				
				var fromd=$( "#orderdatefrom" ).val();
					var tod=$( "#orderdateto" ).val();
					var d=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var m=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var d=tod.substr(0,2);
					var y=tod.substr(6,4);
					var m=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('Date to should be greater than date from!');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		


				edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&page="+x+"&namafield="+globalfield+"&sort="+globalsort+"&search="+globalsearch+"&choose="+globalchoose+"&description="+globaldesc;

						$('#result1').empty().html('<div align="center"><img src="../images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'aws_gettblorderall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'aws_gettblorderpgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
	
			 }
				
	</script>

    <?
	    $year=2012;
		$cyear=date('Y');
		$cmonth=date('m');
		$beda=$cyear-$year;
		
		
		
		$selthn='<option value=""></option>';
		$kali=$beda+1;
		
		for($x=1;$x<=$kali;$x++){
			$thn=$year+$x;
			if($thn!=$cyear){
			$selthn=$selthn . "<option value=".$thn.">".$thn."</option>";
			}else{
			$selthn=$selthn . "<option value=".$thn.">".$thn."</option>";
			};
		}
      $selbln="<select name=\"bulan\" class=\"arial11black\" id=\"blnprd\"> ";
		$selbln= $selbln."<option value=\"\"></option>";
			$selbln= $selbln."<option value=\"01\">January</option>";
			$selbln=$selbln."<option value=\"02\">February</option>";
		 	$selbln=$selbln."<option value=\"03\">March</option>";
			$selbln=$selbln."<option value=\"04\">April</option>";
			$selbln=$selbln."<option value=\"05\">May</option>";
			$selbln=$selbln."<option value=\"06\">June</option>";
			$selbln=$selbln."<option value=\"07\">July</option>";
			$selbln=$selbln."<option value=\"08\">Augustus</option>";
			$selbln=$selbln."<option value=\"09\">September</option>";
			$selbln=$selbln."<option value=\"10\">October</option>";
			$selbln=$selbln."<option value=\"11\">November</option>";
			$selbln=$selbln."<option value=\"12\">December</option>";
		$selbln=$selbln."</select>";
				
			
	?>

    
 
	</head>
	<body>
		<?php require_once('../../core/ctc_cookie.php');?>
		<?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
        	<ul>  
  				<li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
			</ul>
	</div> 
    	<div id="isi">
        
        
        <div id="twocolRight1">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="22%">Customer Number</td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%">Customer Name</td>
    <td width="2%">:</td>
    <td width="25%"><? echo $cusnm ?></td>
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
    <td>Order Date From</td>
    <td>:</td>
    <td>
	<table width="224" border="0">
      <tr>
        <td width="105"><input name="orderdatefrom" id="orderdatefrom" placeholder="dd/mm/yyyy" type="text" size="12" maxlength="12"></td>
        <td width="12">&nbsp;</td>
        <td width="93">
          
        </td>
      </tr>
    </table>
	

    </td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td>Order Date To</td>
    <td>:</td>
    <td> 
	<table width="224" border="0">
      <tr>
        <td width="105"><input name="orderdateto" id="orderdateto" placeholder="dd/mm/yyyy" type="text" size="12" maxlength="12"></td>
        <td width="12">&nbsp;</td>
        <td width="93">
          <input name="cmdGo" type="submit" class="arial11" id="cmdGo" value="Find ">
        </td>
      </tr>
    </table>
</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        
   </table>     
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11" height="30">
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"></th>
    <th scope="col" align="right">
		<a href="#"  id="search" ><img src="../images/search.png" width="101" height="25" border="0"></a>
	</th>
    <th scope="col" align="right">
		<div id="ConvExcel" style="margin: 10;/* min-width: 150px; */background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;width: 140px;">
            <img src="../images/excel.jpg" width="18" height="18" style="float:left;margin-left:4px;margin-top:1px;">
            <font style="margin-right:18px;line-height:22px;">Export to XLSX</font>
        </div>
	</th>
    </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="30%" scope="col"></th>
    <th width="10%" scope="col" align="right"></th>
  </tr>
</table>


<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead >
		<tr align="center" height="30" >
            <th width="10%" rowspan="2">Order Date</th>
            <th width="15%" colspan="2">1 <sup>st</sup> Customer</span></div></th>
            <th width="15%" colspan="2">2 <sup>nd</sup> Customer</span></div></th>
            <th width="12%" rowspan="2">PO Number</th>
            <th width="12%" rowspan="2">Part Number</th>
            <th width="12%" rowspan="2">Quantity</th>
            <th width="12%" rowspan="2">Amount</th>
            <th width="12%" rowspan="2" class="lastth">Status</th>
        </tr>
        <tr>
            <th width="7%">Customer CD</th>
            <th width="8%">Ship to</th>
            <th width="7%">Customer CD</th>
            <th width="8%">Ship to</th>
        </tr>
	</thead>
	<tbody>
		<tr align="middle" height="30" >
        <td colspan="10" id="result1"><center>To be continous</center></td>
        </tr>
        
        <tr align="right" valign="middle" height="30" >
       	  <td colspan="10" class="lastpg"> <div id="pagination"> </div></td>
        </tr>
        
	</tbody>
	
</table>


<div id="loading" style="display:none;" align="center"><img src="images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title="Search" >
		<p class="validateTips">Search Option</p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="partno" >Part Number</option>
            <option value="corno">PO Number</option>
        	<option value="cusno1">1 <sup>st</sup> Customer Code</option>
            <option value="cusno2">2 <sup>nd</sup> Customer Code</option>
            <option value="status">Status</option>
        </select>
         <select name="choose" id="OptChoose" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="eq" >equals</option>
        	<option value="like">contains</option>
          </select>
			
        <input type="text" name="descrip" id="description"  class="arial11blackbold" maxlength="30" size="30">
        </div>   
</div> 
<p><br>
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

	</body>
</html>
