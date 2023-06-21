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
-->
	.vertical-center
        {
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
 <script type="text/javascript" charset="utf-8">
 	function grandtotal() {
		let qty = 0;
		let amnt = 0;
		qty = $('#hid_sum_qty').val();
		amnt = $('#hid_sum_amnt').val();
		$('.amt-txt').val(qty);
		$('.ttl-txt').val(amnt);
	}

	function number_format(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}
			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
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
			  


			  async function updatetbl(namafield, order, searchfield, choose, desc){
				console.log("namafield" +namafield + " order " + order + " Search" + searchfield + " choose" +  choose + " desc" + desc);
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
							success: await function(data) {
								//alert(data);
								$('#result1').html(data);
								//savedata();
								grandtotal();
							}
							
						})
						
						$.ajax({
							type: 'GET',
							url: 'supgettblorderpgall.php',
							data: edata,
							success: await function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
					
				}
			
		$("#search").click(function(){
			$( "#dialog-form" ).dialog( "open" );							
		})
	
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
					data="Please choose criteria to search";
					$('#optchoose').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					return false;
					bValid="";
				}else{
					$('#optchoose').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				if(vdesc==""){
					data="Please fill description to search";
					$('#description').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					return false;
					bValid="";
				}else{
					$('#description').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				
				
			   if ( bValid =="1") {
					globalsearch=vfield;
					globalchoose=vchoose;
					globaldesc=vdesc;	
					updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
									
					}
				},
				Cancel: function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
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
								savedata();
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
			 function savedata(){
				$( ".saveremark" ).click(function(){
					var id = $(this).attr("id");
					var txtrmk = id.replaceAll('|','');
					var rmk = $("#txt_remark" + txtrmk).val();
					//alert(id + ">>" +txtrmk + ">>"+rmk);
					var data = id.split("|");

					$.ajax({
						type: 'POST',
						url: 'sup_save_orderremark.php',
						data: {
							orderno:data[0],
							supno:data[1],
							owner_comp:data[2],
							corno:data[3],
							cusno:data[4],
							remark:rmk,
						},
						success: function(data) {
							//console.log(globalfield+globalsort+globalsearch+globalchoose+globaldesc);
							alert(data);
						}
					})
					
				});	
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
        <td width="105"><input name="orderdatefrom" id="orderdatefrom" type="text" size="12" maxlength="12" autocomplete="off" placeholder="dd/mm/yyyy"></td>
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
        <td width="105"><input name="orderdateto" id="orderdateto" type="text" size="12" maxlength="12" autocomplete="off" placeholder="dd/mm/yyyy"></td>
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
			<th scope="col">&nbsp;</th>
			<th width="50%" scope="col" align="right">
				<!-- Total sumary section -->
				<div style="width: 463px;background-color: white;border-radius: 5px;height: 44px;">
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
		<th width="" scope="col" align="center">
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
		<tr align="center" height="30">
			<!--	<th width="5%"><?php //echo get_lng($_SESSION["lng"], "L0218"); 
									?></th><!--Ship To-->

			<th width="10%">
				<div id="Pono">
					<div class="PartAsc"><a href="#"><?php echo get_lng($_SESSION["lng"], "L0217"); ?>
							<!--PO Number-->
						</a></div>
				</div>
			</th>
			<th width="10%">
				<div id="odrdate">
					<div class="PartAsc"><a href="#"><?php echo get_lng($_SESSION["lng"], "L0219"); ?>
							<!--Order Date-->
						</a></div>
				</div>
			</th>
			<th width="10%">
				<div id="Partno">
					<div class="PartAsc"><a href="#"><?php echo get_lng($_SESSION["lng"], "L0215"); ?>
							<!--Part Number-->
						</a></div>
				</div>
			</th>
			<th width="15%"><?php echo get_lng($_SESSION["lng"], "L0145"); //Part Name
										?></th>
						
						
			<th width="10%"><?php echo get_lng($_SESSION["lng"], "L0492"); //Due Date
							?></th>
			<th width="5%"><?php echo 'QTY'; //QTY
							?></th>
			<th width="10%"><?php echo 'Price'; //Price
							?></th>
			<th width="10%"><?php echo 'Amount'; //Amount
							?></th>
			<th width="10%"><?php echo get_lng($_SESSION["lng"], "L0451"); //Supplier Code
							?></th>
			<th width="10%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0516"); //Remark
							?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
        <td colspan="12" id="result1"></td>
        </tr>
        
        <tr align="right" valign="middle" height="30" >
       	  <td colspan="12" class="lastpg"> <div id="pagination"> </div></td>
        </tr>
        
	</tbody>
	
</table>



<div id="loading" style="display:none;" align="center"><img src="images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title="<?php echo get_lng($_SESSION["lng"], "L0213");/*Search*/ ?>"  style="display: none;" >
				<p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0106"); ?><!--Search Option--></p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value=""></option>
	       	<option value="partno" ><?php echo get_lng($_SESSION["lng"], "L0215");/*Page : mainrpt.php , ID : L0215 = Part Number*/ ?></option>
        	<option value="ITDSC"><?php echo get_lng($_SESSION["lng"], "L0216")/*Description*/; ?></option>
            <option value="corno"><?php echo get_lng($_SESSION["lng"], "L0217")/*PO Number*/; ?></option>
        	<option value="cuscode"><?php echo get_lng($_SESSION["lng"], "L0441")/*Customer Code*/; ?></option>
            <option value="supcode"><?php echo get_lng($_SESSION["lng"], "L0442")/*Supplier Code*/; ?></option>
        </select>
         <select name="choose" id="OptChoose" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="eq" ><?php echo get_lng($_SESSION["lng"], "L0298")/*equals*/; ?></option>
        	<option value="like"><?php echo get_lng($_SESSION["lng"], "L0299")/*contains*/; ?></option>
          </select>
			
        <input type="text" name="descrip" id="description"  class="arial11blackbold" maxlength="30" size="30">
     	</div>   
</div> 
<p><br>
<div id="footerMain1">
	<ul>
       <!-- Disabled by zia
     
          
      -->
	  </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2014 DENSO Thailand. All rights reserved  
  </div>
</div>

	</body>
</html>
