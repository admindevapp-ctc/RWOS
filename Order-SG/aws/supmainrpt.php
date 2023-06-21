<?php 
session_start();

require_once('../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

//print_r($_SESSION);
require_once('../../language/Lang_Lib.php');
//if (session_is_registered('cusno'))
if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
        $cusno = $_SESSION['cusno'];
        $cusnm = $_SESSION['cusnm'];
        $password = $_SESSION['password'];
        $alias = $_SESSION['alias'];
        $table = $_SESSION['tablename'];
        $type = $_SESSION['type'];
        $custype = $_SESSION['custype'];
        $user = $_SESSION['user'];
        $dealer = $_SESSION['dealer'];
        $group = $_SESSION['group'];
    } else {
        echo "<script> document.location.href='../../" . redir . "'; </script>";
    }
} else {
    header("Location:../../login.php");
}


include('chklogin.php');

$ordertype = '';

require("crypt.php");
if (isset($_SERVER['REQUEST_URI'])) {
    $var = decode($_SERVER['REQUEST_URI']);
    $ordertype = trim($var['ordertype']);
}
?>

<html>
    <head>
	<input type="hidden" id="maxyr" name="maxyr" value="<?php echo $maxyr; ?>">
	 <title>Denso Ordering System</title>
</style><!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->

<link rel="stylesheet" type="text/css" href="../css/dnia.css">
<?php if ($ordertype == '' || $ordertype == 'Normal') { ?>
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-green.css">
<?php } else if ($ordertype == 'Urgent') { ?>
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui-red.css">
<?php } else if ($ordertype == 'Request') { ?>
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">
<?php } ?>

<script src="../lib/jquery-1.4.2.min.js"></script>
<script src="../lib/jquery.ui.core.js"></script>
<script src="../lib/jquery.ui.widget.js"></script>
<script src="../lib/jquery.ui.mouse.js"></script>
<script src="../lib/jquery.ui.button.js"></script>
<script src="../lib/jquery.ui.draggable.js"></script>
<script src="../lib/jquery.ui.position.js"></script>
<script src="../lib/jquery.ui.resizable.js"></script>
<script src="../lib/jquery.ui.button.js"></script>
<script src="../lib/jquery.ui.dialog.js"></script>
<script src="../lib/jquery.effect.core.js"></script>
<script src="../lib/jquery.ui.datepicker.js"></script>

<link rel="stylesheet" href="../css/demos.css">
<style>
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips2 { border: 1px solid transparent; padding: 0.3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
	<!--
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
	background-image: url(../images/thbg.png);
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
-->
.vertical-center{
	margin:
	0;
	position:
	absolute;
	top:
	24px;
	-ms-transform:
	translateY(-50%);
	transform:
	translateY(-50%);
}
</style>
<script type="text/javascript">
   var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
			
	function grandtotal() {
		let qty = 0;
		let amnt = 0;
		qty = $('#hid_sum_qty').val();
		amnt = $('#hid_sum_amnt').val();
		$('.amt-txt').val(qty);
		$('.ttl-txt').val(amnt);

	}

	function number_format(nStr) {
		nStr = parseFloat(nStr).toFixed(2);
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '.00';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
$(document).ready(function() {
									   
		$( "#orderdatefrom" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd/mm/yy'
		});					   
		$( "#orderdateto" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd/mm/yy'
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
				
				$('#Partno').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="partno";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="partno";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				
				$('#Pono').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="Corno";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="Corno";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				 
				
				$('#odrdate').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="Corno";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="Corno";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
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
						url= 'sup_gettblorderXLS.php?'+edata;
						window.open(url);	
					
					
			  })
			  


			  function updatetbl(namafield, order, searchfield, choose, desc){
				//console.log("namafield" +namafield + " order " + order + " Search" + searchfield + " choose" +  choose + " desc" + desc);
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
						//alert(edata);
						$('#result1').empty().html('<div align="center"><img src="../images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'supgettblorderall.php',
							data: edata,
							success: function(data) {
								//console.log(data);
								$('#result1').html(data);
								grandtotal();
							}
						})
						
						$.ajax({
							type: 'GET',
							url: 'supgettblorderpgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								grandtotal();
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
				$("#ssl_statusapprove").hide();
				$("<select id='ssl_status'><option value= ',NULL'>Incomplete</option>"+
				"<option value='1,2,\"R\"'>Complete</option></select>").insertAfter("#OptChoose");
			}
			else if($("#OptField").val() == "statusapprove"){
				$("#OptChoose").attr('disabled','disabled');
				$("#description").hide();
				$("#ssl_status").hide();
				$("<select id='ssl_statusapprove'><option value= ',NULL'>Pending</option><option value= '1'>Ship from Supplier</option>"+
				"<option value='2'>Ship from warehouse	</option><option value='R'>Reject</option></select>").insertAfter("#OptChoose");
			}
			else{
				$('#OptChoose').removeAttr('disabled');
				$("#description").show();
				$('#ssl_status').hide();
				$("#ssl_statusapprove").hide();
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
					if(vfield != "status" && vfield != "statusapprove" ){
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
					if(vfield != "status" && vfield != "statusapprove" ){
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
					else if(vfield=="statusapprove"){
						globalchoose="in";
						globaldesc=$("#ssl_statusapprove").val();
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
					$("#ssl_statusapprove").hide();
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				$('#OptChoose').removeAttr('disabled');
				$("#description").show();
				$('#ssl_status').hide();
				$("#ssl_statusapprove").hide();
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
							url: 'supgettblorderall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'supgettblorderpgall.php',
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
<body >
    
    <?php ctc_get_logo() ?> <!-- add by CTC -->

    <div id="mainNav">
        <?php
        include("navhoriz.php");
        ?>
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
    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0482"); //Customer Number?></td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0460"); //Customer Name?></td>
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
    <td><?php echo get_lng($_SESSION["lng"], "L0493"); //Order Date From ?></td>
    <td>:</td>
    <td>
	<table width="224" border="0">
      <tr>
        <td width="105"><input name="orderdatefrom" id="orderdatefrom"  placeholder="dd/mm/yyyy" type="text" size="12" maxlength="12"></td>
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
    <td><?php echo get_lng($_SESSION["lng"], "L0494"); //Order Date To?></td>
    <td>:</td>
    <td> 
	<table width="224" border="0">
      <tr>
        <td width="105"><input name="orderdateto" id="orderdateto"  placeholder="dd/mm/yyyy" type="text" size="12" maxlength="12"></td>
        <td width="12">&nbsp;</td>
        <td width="93">
          <input name="cmdGo" type="submit" class="arial11" id="cmdGo" value="<?php echo get_lng($_SESSION["lng"], "L0497"); //find?>">
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
   <table  border="0" cellspacing="0" cellpadding="0"  align="right" width="80%">
	<tr valign="middle" class="arial11" height="30">
		<th scope="col">&nbsp;</th>
		<th scope="col">&nbsp;</th>
		<th valign="middle" width="30%" align="right" scope="">
			<div style="width: 550;background-color: white;border-radius: 5px;height: 44px;">
				<div style="float:left; position:relative; width:20%; height: 40px;">
					<div class="vertical-center" style="right:10px;font-size: 12px;font-weight: 700;">Grand Total</div>
				</div>
				<div style="width:80%;">
					<div style="float:left; text-align:center;">
						<div style="font-size: 12px;font-weight: 600;color: #ad1d36;">Quantity</div>
						<div><input style="text-align:right;border-radius: 5px;" type="text" class="amt-txt" readonly /></div>
					</div>
					<div style="text-align:center;">
						<div style="font-size: 12px;font-weight: 600;color: #ad1d36;">Amount</div>
						<div><input style="text-align:right;border-radius: 5px;" type="text" class="ttl-txt" readonly /></div>
					</div>
				</div>
			</div>
		</th>
		<th scope="col" align="right" style="padding:10px">
			<a href="#" id="search" ><img src="../images/search.png" width="101" height="25" border="0"></a>
			<!--<a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>-->
			
		</th>
		<th width="100" scope="col" align="center">
                    <div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;width:180px;vertical-align: left;'>
                        <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:20px;margin-top:1px;'>
                        <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
                    </div>
                </th>
    </tr>
	<tr valign="middle" class="arial11">
		<th width="10%" scope="col">&nbsp;</th>
		<th width="10%" scope="col">&nbsp;</th>
		<th width="10%" valign="middle" scope="col"></th>
		<th width="10%" scope="col"></th>
		<th width="10%" scope="col" align="right"></th>
	</tr>
	</table>


	<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%" style="table-layout: fixed;" >
<thead >
<tr align="center" height="30" >
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0144"); //Part Number?></th>
            <th width="7% "><?php echo get_lng($_SESSION["lng"], "L0145"); //Part Name?></th>
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0053"); //PO Number?></th>
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0052"); //Order Date?></th>
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0220"); ?><!--Due Date--></th>
			<th width="4% "><?php echo get_lng($_SESSION["lng"], "L0221"); ?><!--Qty--></th>
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0569"); ?><!--Amount--></th>
			<th width="7% " ><?php echo get_lng($_SESSION["lng"], "L0564"); ?><!--Order Approve Status--></th>
			<th width="7% " ><?php echo get_lng($_SESSION["lng"], "L0556"); ?><!--Ship Date--></th> 
			<th width="7% " ><?php echo get_lng($_SESSION["lng"], "L0557"); ?><!--Ship Qty--></th>
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0451"); //Supplier Code?></th>
			<th width="7% "><?php echo get_lng($_SESSION["lng"], "L0516"); //Remark?></th>
			<th width="7% " class="lastth"><?php echo get_lng($_SESSION["lng"], "L0559"); ?><!--Status--></th>
		</tr>
	</thead>
	<tbody>
		<tr>
        <td colspan="13" id="result1"></td>
        </tr>
        
        <tr align="right" valign="middle" height="30" >
       	  <td colspan="13" class="lastpg"> <div id="pagination"> </div></td>
        </tr>
        
	</tbody>
	
</table>



<div id="loading" style="display:none;" align="center"><img src="../images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title="<?php echo get_lng($_SESSION["lng"], "L0213");/*Search*/ ?>"  style="display: none;" >
				<p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0106"); ?><!--Search Option--></p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value=""></option>
	       	<option value="partno" ><?php echo get_lng($_SESSION["lng"], "L0215");/*Page : mainrpt.php , ID : L0215 = Part Number*/ ?></option>
        	<option value="ITDSC"><?php echo get_lng($_SESSION["lng"], "L0145")/*Part Name*/; ?></option>
            <option value="corno"><?php echo get_lng($_SESSION["lng"], "L0217")/*PO Number*/; ?></option>
            <option value="statusapprove"><?php echo get_lng($_SESSION["lng"], "L0564")/*Order Approve Status*/; ?></option>
            <option value="status"><?php echo get_lng($_SESSION["lng"], "L0559")/*Status*/; ?></option>
        </select>
         <select name="choose" id="OptChoose" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="eq" ><?php echo get_lng($_SESSION["lng"], "L0298")/*equals*/; ?></option>
        	<option value="like"><?php echo get_lng($_SESSION["lng"], "L0299")/*contains*/; ?></option>
          </select>
			
        <input type="text" name="descrip" id="description"  class="arial11blackbold" maxlength="30" size="30">
     	</div>   
            
        <div id="footerMain1">
            <ul>
                <!-- Disable by Zia
                     
                          
                -->
            </ul>

            <div id="footerDesc">

                <p>
                    Copyright &copy; 2023 DENSO . All rights reserved

            </div>
        </div>

        </div>
</div> 
</body>
<?php include('timecheck.php');?><!--03/10/2019 Prachaya inphum CTC-->

</html>
