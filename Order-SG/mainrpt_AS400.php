<? session_start() ?>
<?
require_once('../language/Lang_Lib.php');

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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
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
    </style>

<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">	<script src="lib/jquery.ui.core.js"></script>
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
 <script type="text/javascript" charset="utf-8">
			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
$(document).ready(function() {
		var date1=new Date();
		var d = date1.getDate();
    	var m = date1.getMonth() + 1;
    	var y = date1.getFullYear();
		var md=(m<=9 ? '0' + m : m) + (d <= 9 ? '0' + d : d);
		var def1='0401';
		if(md>def1){
			var yy=y;
		}else{
			var yy=y+1;
		}
    	var mdy= (m<=9 ? '0' + m : m) + '/' + (d <= 9 ? '0' + d : d)+'/' +y;
		var defa='08/01/'+yy;
		//alert(mdy);
		$( "#orderdatefrom" ).datepicker({
			dateFormat:'mm/dd/yy',		 
			changeMonth: true,
			changeYear: true
		});
			
		$( "#orderdatefrom" ).datepicker('setDate', mdy);
		$( "#orderdateto" ).datepicker({
			dateFormat:'mm/dd/yy',								   
			changeMonth: true,
			changeYear: true
		});									   
		$( "#orderdateto" ).datepicker('setDate', defa);
		
			var choose = $( "#OptChoose" ),
			field= $( "#OptField" ),
			desc= $( "#description" ),
			allFields = $( [] ).add(field).add(choose).add(desc),
			tips = $( ".validateTips" );
		
				$('#thnprd').change(function(){updatetbl('','','','','')});		
				
				$('#cmdGo').click(function(){
					
					updatetbl('','','','','');

				
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
				
				$('#ConvExcel').click(function(){
					var fromd=$( "#orderdatefrom" ).val();						   
					var tod=$( "#orderdateto" ).val();
					var m=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var d=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var m=tod.substr(0,2);
					var y=tod.substr(6,4);
					var d=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('<?php echo get_lng($_SESSION["lng"], "G0005"); ?>');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		
											
						var edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&search="+globalsearch+"&choose="+globalchoose+"&desc="+globaldesc;
						url= 'gettblorderXLS.php?'+edata;
						window.open(url);	
					
			  })
			  


			  function updatetbl(namafield, order, searchfield, choose, desc){

				var fromd=$( "#orderdatefrom" ).val();
					var tod=$( "#orderdateto" ).val();
					var m=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var d=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var m=tod.substr(0,2);
					var y=tod.substr(6,4);
					var d=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('<?php echo get_lng($_SESSION["lng"], "G0005"); ?>');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		

	
				edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&namafield="+namafield+"&sort="+order+"&search="+searchfield+"&choose="+choose+"&description="+desc;
						//alert(edata);
						$('#result1').empty().html('<div align="center"><img src="images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'gettblorderall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								console.log("data : "+data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'gettblorderpgall.php',
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
			height:250,
			width: 450,
			modal: true,
			buttons: {
				'<?php echo get_lng($_SESSION["lng"], "L0225")/*"Search"*/; ?>': function() {
				var data;					
				var edata;
				var bValid;
				var vfield=$('#OptField').val();
				var vchoose=$('#OptChoose').val();
				var vdesc=$('#description').val();
				if(vfield==""){
					data='<?php echo get_lng($_SESSION["lng"], "W0018")/*"Please choose field to search"*/; ?>';
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
					data='<?php echo get_lng($_SESSION["lng"], "W0019")/*"Please choose criteria to search"*/; ?>';
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
					data='<?php echo get_lng($_SESSION["lng"], "W0020")/*"Please fill description to search"*/; ?>';
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
				'<?php echo get_lng($_SESSION["lng"], "L0111")/*"Close"*/; ?>': function() {
					
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
					var m=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var d=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var m=tod.substr(0,2);
					var y=tod.substr(6,4);
					var d=tod.substr(3,2);
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

						$('#result1').empty().html('<div align="center"><img src="images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'gettblorderall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'gettblorderpgall.php',
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
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        <?
				$_GET['selection']="main";
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
    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0208"); ?><!--Customer Number--></td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0209"); ?><!--Customer Name--></td>
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
    <td><?php echo get_lng($_SESSION["lng"], "L0210"); ?><!--Order Date From--></td>
    <td>:</td>
    <td>
	<table width="224" border="0">
      <tr>
        <td width="105"><input name="orderdatefrom" id="orderdatefrom" type="text" size="12" maxlength="12"></td>
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
    <td><?php echo get_lng($_SESSION["lng"], "L0211"); ?><!--Order Date To--></td>
    <td>:</td>
    <td> 
	<table width="224" border="0">
      <tr>
        <td width="105"><input name="orderdateto" id="orderdateto" type="text" size="12" maxlength="12"></td>
        <td width="12">&nbsp;</td>
        <td width="93">
          <input name="cmdGo" type="submit" class="arial11" id="cmdGo" value="<?php echo get_lng($_SESSION["lng"], "L0212")/*Find*/; ?>">
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
    <th colspan="2" scope="col" align="right">
		<!--<a href="#" id="search" ><img src="images/search.png" width="101" height="25" border="0"></a>
			<a href="#" id="ConvExcel"><img src="images/export.png" width="101" height="25" border="0"></a>-->
		<a href="#" id="search" >
			<div style='display:inline-block;background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 80px;height:22px;border-radius: 7px;'>
				<img src="images/Icojam-Blue-Bits-Search.ico" width="18" height="18" border="0" style='float:left;margin-left:4px;margin-top:1px;'>
				<font style='margin-right:6px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0213"); ?></font>
			</div>
		</a>
		</th>
		<th>&nbsp;</th>
		 <th colspan="2" scope="col" align="right">
		<a href="#" id="ConvExcel">
			<div style='display:inline-block;background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 120px;height:22px;border-radius: 7px;'>
				<img src="images/excel.jpg" width="18" height="18" border="0" style='float:left;margin-left:4px;margin-top:1px;'>
				<font style='margin-right:6px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0214"); ?></font>
			</div>
		</a>
	</th>
    </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
</table>



<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead >
		
		<tr align="center" height="20" >
			<th width="13%"><div id="Partno"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0215"); ?><!--Part Number--></a></div></div></th>
           <th width="20%"><?php echo get_lng($_SESSION["lng"], "L0216"); ?><!--Part Name--></th>
      <th width="11%"><div id="Pono"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0217"); ?><!--PO Number--></a></div></div></th>
			<th width="12%"><?php echo get_lng($_SESSION["lng"], "L0218"); ?><!--Ship To--></th>
			<th width="9%"><div id="odrdate"><div class="PartAsc"><a href="#" ><?php echo get_lng($_SESSION["lng"], "L0219"); ?><!--Order Date--></a></div></div></th>
            <th width="9%"><?php echo get_lng($_SESSION["lng"], "L0220"); ?><!--Due Date--></th>
			<th width="11%" ><?php echo get_lng($_SESSION["lng"], "L0221"); ?><!--Qty--></th>
            <th width="10%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0222"); ?><!--user--></th>
		</tr>
	</thead>
	<tbody>
		<tr>
        <td colspan="8" id="result1"></td>
        </tr>
        
        <tr align="right" valign="middle" height="30" >
       	  <td colspan="8" class="lastpg"> <div id="pagination"> </div></td>
        </tr>
        
	</tbody>
	
</table>


<div id="loading" style="display:none;" align="center"><img src="images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title="<?php echo get_lng($_SESSION["lng"], "L0213");/*Search*/ ?>"  style="display: none;" >
				<p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0106"); ?><!--Search Option--></p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="partno" ><?php echo get_lng($_SESSION["lng"], "L0215");/*Page : mainrpt.php , ID : L0215 = Part Number*/ ?></option>
        	<option value="ITDSC"><?php echo get_lng($_SESSION["lng"], "L0216")/*Description*/; ?></option>
            <option value="corno"><?php echo get_lng($_SESSION["lng"], "L0217")/*PO Number*/; ?></option>
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
	Copyright &copy; 2023 DENSO . All rights reserved  
  </div>
</div>

	</body>
</html>
