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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


include('chklogin.php');
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
      <!--  <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">-->
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<script src="lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
	<script src="lib/jquery.bgiframe-2.1.2.js"></script>
	<script src="lib/jquery.ui.core.js"></script>
 	<script src="lib/jquery.ui.widget.js"></script>
     <script src="lib/jquery.ui.mouse.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.draggable.js"></script>
	<script src="lib/jquery.ui.position.js"></script>
	<script src="lib/jquery.ui.resizable.js"></script>
	<script src="lib/jquery.ui.dialog.js"></script>
	<script src="lib/jquery.effects.core.js"></script>
    <script src="lib/jquery.ui.autocomplete.js"></script>


   <script type="text/javascript" src="lib/jquery.tipsy.js"></script>
    <link rel="stylesheet" href="css/tipsy.css" type="text/css" />
	<link rel="stylesheet" href="css/tipsy-docs.css" type="text/css" />
	<link rel="stylesheet" href="css/demos.css">   
  
 
    <style>
		
		body { font-size: 62.5%; }
		
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; } 
		input[type=radio]{ 
			display:inline;
			margin-top:0px;
		} 
			
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
		**/
	</style>
   
    
<script type="text/javascript">
$(function() {
	$().ajaxStart(function() {
		$('#loading').show();
		$('#result').hide();
	}).ajaxStop(function() {
		$('#loading').hide();
		$('#result').fadeIn('slow');
	})	   
		   
		   
    
		
		/**$('.add').click(function(){
			$('#myTable tr:last').clone(true).insertAfter('#myTable tr:last');
		});**/
	var vaction="";
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var res="";
	var partno = $( "#partno" ),
			qty = $( "#qty" ),
			allFields = $( [] ).add( partno ).add( qty ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				/*updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );*/
				updateTips('<?php echo get_lng($_SESSION["lng"], "G0009");?>');
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		
		/* check part number 
		function checkPartno(o) {
			var partno=o.val();	
			$.get('getpartno.php?partno='+partno, function(res){
				alert(res);									  
				if(res.substr(0,5)=='Error'){
					o.addClass( "ui-state-error" );
					updateTips(res );
					return res.substr(0,5);
				}else{
				return res;
					
				}
			  });
		
			}	
	
		
		*/
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
		
			width: 450,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				'<?php echo get_lng($_SESSION["lng"], "L0075")/*"save Order Detail"*/; ?>': function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( partno, "Part Number", 2, 15 );
					bValid = bValid && checkRegexp( partno, /([0-9a-z_-])+$/i, "Part number may consist of a-z,-, 0-9" );
					bValid = bValid && checkRegexp( qty, /^([0-9])+$/, '<?php echo get_lng($_SESSION["lng"], "W0023"); ?>'/*"Qty field only allow : 0-9"*/ );
			
					/** check part number **/
				
				epartno=partno.val();
				var edata;
				
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				var quantity=qty.val();
				var shpno=$('#shpno').val();
				var oecus=$('#oecus').val();
				var shipment=$('#shipment').val();
				//var shipment=$('input:radio[name=shipment]:checked').val();
				//alert(oecus);
				//alert(shipment);
				edata="partno="+epartno +"&orderno="+orderno+"&corno="+corno+"&shpno="+shpno+"&oecus="+oecus+"&shipment="+shipment+"&qty="+quantity+"&action="+vaction;
				
				//alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getpartno.php',
						//data: "partno="+epartno,
						data: edata,
						success: function(data) {
							//alert(data);
							if(data.substr(0,5)=='Error'){
								//alert(data);
								partno.addClass( "ui-state-error" );
								$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
								return false;
							}else{
								
								var xdata=data.split("||");
								var itdsc=xdata[0];
								var curcd=xdata[1];
								var bprice=xdata[2];
								var ttlprice=xdata[3];
								var ttlex=xdata[4];
								var duedt=xdata[5];
								if(vaction=='add'){
								$('#myTable > tbody:last').append( "<tr>" +
								"<td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" + partno.val() + "></td>" +  
								"<td>" + partno.val().toUpperCase() + " - "+itdsc+ "</td>" + 
								"<td class=\"qty\" align=\"right\">" +qty.val()+"</td>" +
								"<td class=\"curcd\" align=\"right\">" + curcd + "</td>" +
								"<td class=\"price\" align=\"right\">" + bprice + "</td>" +
								"<td class=\"ttl\" align=\"right\">" + ttlprice + "</td>" +
								"<td class=\"ttlex\" align=\"right\">" + ttlex + "</td>" +
				
				
								"<td id=\"duedt\" align=\"right\">" +duedt + "</td>" +
								"</tr>" );
								}else{
									
									$.each($('.chkaction:checked'), function() {
										$(this).closest('tr').children('td[class=qty]').text(quantity);								 	
										//alert(bprice);	
										$(this).closest('tr').children('td[class=price]').text(bprice);
										//alert(ttlprice);	
										$(this).closest('tr').children('td[class=ttl]').text(ttlprice);
										$(this).closest('tr').children('td[class=ttlex]').text(ttlex);
										$(this).closest('tr').children('td[id=duedt]').text(duedt);
    								});
									$('.chkaction:checked').attr('checked', false);
								}
								
						
								$( "#dialog-form").dialog( "close" );
								}
							}
						});
						
						
								
					}
				},
				'<?php echo get_lng($_SESSION["lng"], "L0076"); ?>': function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

	
	
	
	$( "#add" ).click(function() {
				$('#partno').removeAttr("disabled");
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
				partno.removeClass( "ui-state-error" );
				$("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0025"); ?>'/*'Add New Record'*/); 
				$( "#dialog-form" ).dialog( "open" );
				vaction='add';
			});
			
	
	$( "#dlt" ).click(function() {
		
					mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
			if(mpart!==''){
				
				//alert(mpart);
				
				vaction='delete';
			    $( "#dialog-confirm" ).dialog('open');				
			
			}else{
					$("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0003"); ?>'/*'please at least select 1 document to delete!'*/);
					$( "#dialog-message" ).dialog( "open" );
				//alert('please at least select 1 document to delete!');	
				
			}
			
			});
	
	
	$( "#chg" ).click(function() {
				var x=$('input:checked').length;
			if(x>0){
				if(x>1){
					alert('<?php echo get_lng($_SESSION["lng"], "G0010"); ?>'/*'please choose only one row to edit'*/);
				}else{
			
					var epart=$('.chkaction:checked').val();
					//alert(epart);
					$.each($('.chkaction:checked'), function() {
						eqty=($(this).closest('tr').children('td[class=qty]').text());
						
    				});
					
					$('#partno').val(epart);
					 $('#partno').attr("disabled", true);
					$('#qty').val(eqty);
					vaction='edit';
					//$( "#dialog-form" ).attr("title","change record");
					//var xtitle=$( "#dialog-form" ).attr('title')
					$("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0305")/*Edit Record*/; ?>'); 
					//alert(xtitle);
					$( "#dialog-form" ).dialog( "open" );
						}
			}else{
					$("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0010"); ?>'/*'please at least select 1 document to edit!'*/);
					$( "#dialog-message" ).dialog( "open" );
			}
			});
	
	$( "#cmdSave" ).click(function() {
				var rowCount = $('#myTable >tbody >tr').length;
				if(rowCount==1){
					$("p[id=message]").text('<?php echo get_lng($_SESSION["lng"], "G0004"); ?>'/*'There are no Transaction to save, Please use close button!'*/);
					$( "#dialog-message" ).dialog( "open" );
					return;
				}
					
		
				
				var edata;
				var shpno=$('#shpno').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				var action=$('#action').val();
				edata="&orderno="+orderno+"&corno="+corno+"&shpno="+shpno+"&action="+action;
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
								$('#result').html(data);
								}
						});

			
			
			
			//-------------------------------------------------------
			
			
			
			});
	
				
		$( "#cmdClose" ).click(function() {
				var answer = confirm('<?php echo get_lng($_SESSION["lng"], "G0002"); ?>'/*"Do you want to close without saving?"*/);
				if (answer){
				
				var edata;
				var shpno=$('#shpno').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				edata="&shpno="+shpno+"&orderno="+orderno+"&corno="+corno+"&action=close";
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
								$('#result').html(data);
								}
						});

			
			
			
			//-------------------------------------------------------
				}
			
			
			});		

				
				
			
				
	// dialog confirm
	$( "#dialog-confirm" ).dialog({
								  
			autoOpen: false,					  
			resizable: false,
			height:200,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			modal: true,
			buttons: {
				  "Delete selected": function() {
					$.each($('.chkaction:checked'), function() {
						$(this).parent().parent().remove();								 
        		
    			});
					//==================================================
			
				var edata;
				var shpno=$('#shpno').val();
				var orderno=$('#orderno').val();
				edata="&shpno="+shpno+"&orderno="+orderno+"&partno="+mpart;
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'delpartno.php',
						data: edata,
						success: function(data) {
								//alert(data);
								}
					});
							
			//-------------------------------------------------------
				
					
				 					
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	//end dialog confirm
		
		$( "#dialog-message" ).dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				Ok: function() {
					//$("#dialog-message"�).dialog( "close" );
					$(this).dialog( "close" );
				}
			}
		});

				var ok=susu(partno.val());
			//create config object
		
			            var shpno=$('#shpno').val();
								//alert(shpno);	
    					$("#partno").autocomplete({
											  
								source: "getAutopartno.php?shpno="+shpno,
								minLength: 2		 
							  
						 });
	//	turn specified element into an auto-complete
   	  
	  function susu(prtno){
						//	alert(prtno);
							if(prtno.length!=0){
								var shpno=$('#shpno').val();
								//alert(shpno);
								var edata="&q="+prtno.val()+"&shpno="+shpno;
								//alert(partno.val().length);
								$.ajax({
									type: 'GET',
									url: 'getAutopartno.php?shpno='+shpno,
									data: edata,
									success: function(data) {
										return data;
										//alert(data);
										}
									});
							}
						}
						
	// Tool Tips
	//$(".btn img[title]").tipsy({gravity: 'e'});
	
		 
	});
</script>


	</head>
	<body >
    
<?
	   include "crypt.php";
	   $var = decode($_SERVER['REQUEST_URI']);
	   $xordno=trim($var['ordno']);
	   $vcusno=trim($var['cusno']);
	   $vcdate=trim($var['orddate']);
	   $vcorno=trim($var['corno']);
	   $vshpno=trim($var['shpno']);
	   $cyear=trim($var['prdyear']);
	   $action=trim($var['action']);
	   $oecus=trim($var['oecus']);
	   $shipment=trim($var['shipment']);
	   if($shipment=='A'){
			$vshipment='by Air';   
	   }else{
		   $vshipment='by Sea';
	   }
		   
		$inpoecus="<input name=\"oecus\" type=\"hidden\"  id=\"oecus\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='$oecus'>";
		if(strtoupper($oecus)=='Y'){
				
				$inpshipment="<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"   maxlength=\"20\" size=\"20\" readonly=\"true\" value='$shipment'>";	
		$inpshipmod= '<tr class="arial11blackbold"> <td>&nbsp;</td>  <td>&nbsp;</td> <td  class="arial11blackbold">&nbsp;</td>';
		$inpshipmod=$inpshipmod.' <td></td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> </tr><tr class="arial11blackbold">  <td>Shipment Mode</td>';
		$inpshipmod=$inpshipmod.' <td>:</td> <td colspan="5"  >'. $vshipment.$inpshipment .'</td>    </tr>';
		
  
		
		}else{
			
				$inpshipment="<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='S'>";		
				$inpshipmod=$inpshipment;
		}
		
	
	
	   
	   echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=".$action.">";
	   if($cusno==$vshpno){
	   }
	   	$query="select * from cusrem where cusno = '". $vshpno. "'";
		
        $sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
        if($hasil){
			$vremark=$hasil['remark'];
			$vcurcd=$hasil['curcd'];
			$alamat=$vremark . '  (' .$vcurcd.')' ;
			
		}
		$inputshpno="<input type=\"hidden\" name=\"shpno\" type=\"text\"  id=\"shpno\" class=\"arial11blackbold\"  value=".$vshpno.">";

		$txtcorno="<input name=\"corno\" type=\"text\"  id=\"corno\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='".$vcorno."'>";
	
		$inputordno="<input name=\"orderno\" type=\"text\"  id=\"orderno\" class=\"arial11blackbold\" readonly=\"true\" value=".$xordno.">"; 
		
		
	?>
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       		<ul>  
  				<li id="current"><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0046"); ?><!--Ordering--></a></li>
				<li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0047"); ?><!--User Profile--></a></li>
  				<li><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0048"); ?><!--Table Part--></a></li>
  				<li ><a href="#" onClick="alert('<?php echo get_lng($_SESSION["lng"], "G0012");/*'Please use Close button to move from transaction menu! '*/ ?>')"><?php echo get_lng($_SESSION["lng"], "L0049"); ?><!--Log out--></a></li>
  				  				
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
    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0050"); ?><!--Customer Number--></td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0055"); ?><!--Customer Name--></td>
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
    <td><?php echo get_lng($_SESSION["lng"], "L0052"); ?><!--Order Date--></td>
    <td>:</td>
    <td><? echo date("d-m-Y") ?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><? echo $inpoecus;?>
    
	</td>
  </tr>
  <tr class="arial11blackbold">
    <td><? echo $inputshpno ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td><?php echo get_lng($_SESSION["lng"], "L0051"); ?><!--Ship To--></td>
    <td>:</td>
    <td colspan="5">
	<table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11%" class="arial11redbold"><? echo  $vshpno ?> </td>
    <td width="89%"> <? echo  $alamat ?></td>
  </tr>
	</table>

	
	
	       
    <td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5"><div id="errthn" class="arial11redbold"></div></td>
  </tr>
  <tr class="arial11blackbold">
    <td><?php echo get_lng($_SESSION["lng"], "L0056"); ?><!--Denso Order Number--></td>
    <td>:</td>
    <td  class="arial11blackbold"><? echo $inputordno ?></td>
   <td></td>
    <td>Status</td>
    <td>:</td>
    <td><input name="status" type="text"  id="ordstatus" class="arial11blackbold" readonly="true" value="Regular"></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td><?php echo get_lng($_SESSION["lng"], "L0053"); ?><!--PO Number--></td>
    <td>:</td>
    <td  class="arial11blackbold">
    <? echo $txtcorno ?>
    </td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <? echo $inpshipmod;
   ?>
        </table>
        <p>&nbsp;</p>
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr align="right">
            <td width="10%" ><input type="button" value="<?php echo get_lng($_SESSION["lng"], "L0057");/*Save order Entry*/ ?>" id="cmdSave" class="arial11blackbold">              </input></td>
            <td width="10%"><input type="button" class="arial11blackbold" id="cmdClose" value="<?php echo get_lng($_SESSION["lng"], "L0058");/*Close order Entry*/ ?>"></input></td>
            <td width="10%">&nbsp;</td>
            <td width="4%">&nbsp;</td>
            <td width="29%"></td>
            <td width="37%"><button class="btn" id="add"><span class="arial11blackbold"><img src="images/add.png" title="add new record" width="18" height="18"></span></button>
              <button class="btn" id="chg"><span class="arial11blackbold"><img src="images/edit.png" title="Change record" width="18" height="18"></span></button>
              <button class="btn" id="dlt"><span class="arial11blackbold"> <img src="images/delete.png" title="Delete record" width="18" height="18"></span></button></td>
          </tr>
        </table>
      
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr class="arial11redbold"  align="center" >
            <td width="7%" height="10"></td>
            <td width="37%"></td>
            <td width="46%"></td>
            <td width="10%"></td>
          </tr>
          </table>
        <table class="tblorder"  width="97%" border="0" cellpadding="0" cellspacing="0"  id="myTable">
         <tbody>
          <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
            <th width="3%" height="30"><?php echo get_lng($_SESSION["lng"], "L0059"); ?><!--Sel--></th>
            <th width="30%" ><?php echo get_lng($_SESSION["lng"], "L0060"); ?><!--Part Number--></th>
            <th width="10%" ><?php echo get_lng($_SESSION["lng"], "L0061"); ?><!--Qty--></th>
            <th width="3%" ><?php echo get_lng($_SESSION["lng"], "L0062"); ?><!--Curr--></th>
            <th width="13%" ><?php echo get_lng($_SESSION["lng"], "L0063"); ?><!--Price--></th>
            <th width="15%" ><?php echo get_lng($_SESSION["lng"], "L0064"); ?><!--Amount--></th>
            <th width="15%" >Amount SGD</th>
            <th width="10%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0065"); ?><!--Due Date<br>(DD-MM-YYY)--></th>
           
          </tr>
          <?
		  require('db/conn.inc');
	$query="select * from ".$table. " where trim(cusno) ='".$vshpno. "' and trim(orderno)='".$xordno."' order by partno";
	//echo $query;
	    $Ymd=date('Ymd');
	
		$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$partdes=$hasil['partdes'];
				$curcd=$hasil['CurCD'];
				$qty=$hasil['qty'];
				$disc=$hasil['disc'];
				$bprice=$hasil['bprice'];
				$slsprice=$hasil['slsprice'];
				$duedt=substr($hasil['DueDate'],-2)."/".substr($hasil['DueDate'],4,2)."/".substr($hasil['DueDate'],0,4);
				$disco=number_format(($bprice*$disc)/100,0,".",",");
				$ttl=number_format($slsprice*$qty,2,".",",");
				$vbprice=number_format($bprice,2,".",",");
				$exrate=$hasil['SGPrice'];
				
				//$exttl=$slsprice*$qty*exrate;
				$ttlex=number_format($slsprice*$qty*$exrate,2,".",",");
			echo "<tr><td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value='" . $partno . "'></td>" ;
			echo "<td>".$partno." - ". $partdes."<td class=\"qty\" align=\"right\">".$qty."</td>";
			echo "<td class=\"curcd\" align=\"right\">" . $curcd . "</td>";								            echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>" ;
			echo "<td class=\"ttl\" align=\"right\">" . $ttl . "</td>" ;
			echo "<td class=\"ttlex\" align=\"right\">" . $ttlex. "</td>" ;
			echo "<td id=\"duedt\" class=\"lasttd\" align=\"center\">". $duedt . "</td>";
			echo "</tr>";
			}
		  
		  
		  ?>
          
          
          
          
          </tbody>
        </table>
        <p><div id="result"></div></p>
        <div class="demo">
        
        
        
        
   	  <div id="dialog-form" title="Add Order Detail" style="display: none;">
				<p class="validateTips">All form fields are required.</p>


					<form>
						<fieldset>
							<label for="partno"><?php echo get_lng($_SESSION["lng"], "L0060"); ?><!--Part Number--></label>
									<input type="text" name="partno" id="partno" class="text ui-widget-content ui-corner-all" />
        
									<label for="qty"><?php echo get_lng($_SESSION["lng"], "L0293"); ?><!--Order Qty--></label>
									<input type="text" name="qty" id="qty" value="" class="text ui-widget-content ui-corner-all" />
        
        
        
						</fieldset>
					</form>
    			
                
                </div>
		
  <div id="dialog-confirm" title="Delete Selected Record?" style="display: none;">
				<p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Selected  items will be permanently deleted and cannot be recovered. Are you sure?</p>
		</div>
        
         <div id="dialog-message"" title="<?php echo get_lng($_SESSION["lng"], "L0295")/*Error Message!ss*/; ?>" style="display: none;">
				<p id="message" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please fill required item before add Transaction detail!</p>
		</div>

        
    
    
        
        
        </div>
        </div>
    
    
    
              
<div id="footerMain1">
	<ul>
      <!-- Disabled by Zia
     
          
      -->
	  </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
