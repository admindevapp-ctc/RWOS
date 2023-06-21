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
?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
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
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.ui.effect.js"></script>
<link rel="stylesheet" href="../css/demos.css">    <script type="text/javascript" charset="utf-8">
			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
			$(document).ready(function() {
			var choose = $( "#OptChoose" ),
			field= $( "#OptField" ),
			desc= $( "#description" ),
			allFields = $( [] ).add(field).add(choose).add(desc),
			tips = $( ".validateTips" );
									   
				updatetbl('','','','','');
				//$('#thnprd').change(function(){updatetbl('','','','','')});		
				
				$('#Partno').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="ITNBR";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="ITNBR";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				 
						$('#Partdsc').click(function(){

						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="ITDSC";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="ITDSC";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				 
				
				$('#PartName').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="Product";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="Product";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				 
				 
				 		$('#subprod').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="SUBPROD";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="SUBPROD";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				 
				 
				
				$('#ConvExcel').click(function(){
						var edata="search="+globalsearch+"&choose="+globalchoose+"&desc="+globaldesc;
						//alert(edata);
						url= 'gettblItemXLS.php?'+edata;
						window.open(url);	
					
			  })
			  
			  function updatetbl(namafield, order, searchfield, choose, desc){
					
					
						
						var edata="namafield="+namafield+"&sort="+order+"&search="+searchfield+"&choose="+choose+"&description="+desc;
						//alert(edata);
						$('#result1').empty().html('<div align="center"><img src="../images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'getitem.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
							}
						})
						
						$.ajax({
							type: 'GET',
							url: 'getitempgall.php',
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
	
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height:200,
			width: 450,
			modal: true,
			buttons: {
				'<?php echo get_lng($_SESSION["lng"], "L0109")/*"Search"*/; ?>': function() {
				var data;					
				var edata;
				var bValid;
				var vfield=$('#OptField').val();
				var vchoose=$('#OptChoose').val();
				var vdesc=$('#description').val();
				if(vfield==""){
					data='<?php echo get_lng($_SESSION["lng"], "W0014")/*"Please choose field to search"*/; ?>';
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
					data='<?php echo get_lng($_SESSION["lng"], "W0015")/*"Please choose criteria to search"*/; ?>';
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
					data='<?php echo get_lng($_SESSION["lng"], "W0016")/*"Please fill description to search"*/; ?>';
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
				'<?php echo get_lng($_SESSION["lng"], "L0111")/*"Cancel"*/; ?>': function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
									   
								   
		   });
			
			 function paging(x){
						var edata="page="+x+"&namafield="+globalfield+"&sort="+globalsort+"&search="+globalsearch+"&choose="+globalchoose+"&description="+globaldesc;;
						$('#result1').empty().html('<div align="center"><img src="../images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'getitem.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'getitempgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
				

			 }
				
	</script>
    
 
	</head>
	<body>

		<?php ctc_get_logo() ?> <!-- add by CTC -->
		
		<div id="mainNav">
			<?
				$_GET['selection']="part";
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
    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0094")/*Customer Number*/; ?></td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0095")/*Customer Name*/; ?></td>
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
    <td></td>
    <td>&nbsp;</td>
	
    <td colspan="5">
	<table width="97%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="150">
		</td>	
	    <td>&nbsp;</td>
	    </tr>
	  </table></td>
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
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11" height="30">
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
	<th scope="col">&nbsp;</th>
    <th colspan="2" scope="col" align="right">
		<!--<a href="#" id="search" ><img src="../images/search.png" width="101" height="25" border="0"></a>-->
		<!--<a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>-->

		<a href="#" id="search" style='text-decoration-line: none;'>
			<div style='display:inline-block;background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 80px;height:22px;border-radius: 7px;'>
				<img src="../images/Icojam-Blue-Bits-Search.ico" width="18" height="18" border="0" style='float:left;margin-left:4px;margin-top:1px;'>
				<font style='margin-right:6px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0303"); ?></font>
			</div>
			</th>
			<th>&nbsp;</th>
			<th colspan="2" scope="col" align="right">
		</a>
		<a href="#" id="ConvExcel" style='text-decoration-line: none;'>
			<div style='display:inline-block;background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 120px;height:22px;border-radius: 7px;'>
				<img src="../images/excel.jpg" width="18" height="18" border="0" style='float:left;margin-left:4px;margin-top:1px;'>
				<font style='margin-right:6px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
			</div>
		</a>
	</th>
    </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="18%" scope="col" align="right">&nbsp;</th>
  </tr>
</table>



<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead >
		
		<tr align="center" height="20" >
		   <th width="15%"><div id="Partno"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0096")/*Part Number*/; ?></a></div></div></th>
           <th width="20%"><div id="Partdsc"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0097")/*Part Name*/; ?></a></div></div></th>
           <th width="15%"><div id="PartName"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0098")/*Product*/; ?></a></div></div></th>
			<th width="15%"><div id="subprod"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0099")/*Sub Product*/; ?></a></div></div></th>
			<th width="10%"><?php echo get_lng($_SESSION["lng"], "L0100")/*Lot Size*/; ?></th>
            <th width="10%"><?php echo get_lng($_SESSION["lng"], "L0101")/*Currency*/; ?></th>
			<th width="15%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0102")/*Price*/; ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
        <td colspan="7" id="result1"></td>
        </tr>
        
        <tr align="right" valign="middle" height="30" >
       	  <td colspan="7" class="lastpg"> <div id="pagination"> </div></td>
        </tr>
        
	</tbody>
	
</table>


<div id="loading" style="display:none;" align="center"><img src="../images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title='<?php echo get_lng($_SESSION["lng"], "L0109"); ?>'>
				<p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0106")/*Search Option*/; ?></p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="partno" ><?php echo get_lng($_SESSION["lng"], "L0107")/*Part Number*/; ?></option>
        	<option value="ITDSC"><?php echo get_lng($_SESSION["lng"], "L0295")/*Description*/; ?></option>
            <option value="product"><?php echo get_lng($_SESSION["lng"], "L0296")/*Product*/; ?></option>
        </select>
         <select name="choose" id="OptChoose" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="eq" ><?php echo get_lng($_SESSION["lng"], "L0297")/*equals*/; ?></option>
        	<option value="like"><?php echo get_lng($_SESSION["lng"], "L0108")/*contains*/; ?></option>
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
	Copyright &copy; 2023 DENSO . All rights reserved  
  </div>
</div>

	</body>
</html>
