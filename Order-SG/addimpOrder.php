<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['cusad1'];
		$_SESSION['cusad2'];
		$_SESSION['cusad3'];
		$_SESSION['type'];
		$_SESSION['zip'];
		$_SESSION['state'];
		$_SESSION['phone'];
		$_SESSION['password'];
		$_SESSION['tablename'];

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$cusad1=$_SESSION['cusad1'];
	$cusad2=$_SESSION['cusad2'];
	$cusad3=$_SESSION['cusad3'];
	$type=$_SESSION['type'];
	$zip=$_SESSION['zip'];
	$state=$_SESSION['state'];
	$phone=$_SESSION['phone'];
	$password=$_SESSION['password'];
	 $table=$_SESSION['tablename'];
   
}else{	
header("Location: login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<script src="lib/jquery-1.4.2.js"></script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
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
	<link rel="stylesheet" href="css/demos.css">   
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
$(function() {
	$().ajaxStart(function() {
		$('#loading').show();
		$('#result').hide();
	}).ajaxStop(function() {
		$('#loading').hide();
		$('#result').fadeIn('slow');
	});	   
		   
		   
    
		
		/**$('.add').click(function(){
			$('#myTable tr:last').clone(true).insertAfter('#myTable tr:last');
		});**/
	var vaction="";
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
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
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
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
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"save Order Detail": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( partno, "Part Number", 2, 15 );
					bValid = bValid && checkRegexp( partno, /([0-9a-z_-])+$/i, "Part number may consist of a-z,-, 0-9" );
					bValid = bValid && checkRegexp( qty, /^([0-9])+$/, "Qty field only allow : 0-9" );
			
					/** check part number **/
				
				epartno=partno.val();
				var edata;
				
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var quantity=qty.val();
				edata="partno="+epartno +"&periode="+periode+"&orderno="+orderno+"&qty="+quantity+"&action="+vaction;
				//alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getpartno.php',
						//data: "partno="+epartno,
						data: edata,
						success: function(data) {
							
							if(data.substr(0,5)=='Error'){
								//alert(data);
								partno.addClass( "ui-state-error" );
								$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
								return false;
							}else{
								if(vaction=='add'){
								$('#myTable > tbody:last').append( "<tr>" +
								"<td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" + partno.val() + "/></td>" +  
								"<td>" + partno.val() + "</td>" + 
								"<td>" + data+"</td>" +
								"<td class=\"qty\">" + qty.val() + "</td>" +
								"</tr>" );
								}else{
									
									$.each($('.chkaction:checked'), function() {
										$(this).closest('tr').children('td[class=qty]').text(quantity);								 						   		
    								});
									$('.chkaction:checked').attr('checked', false);
								}
								
						
								$( "#dialog-form").dialog( "close" );
								}
							}
						});
						
						
								
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

	
	
	
	$( "#add" ).click(function() {
				$('#partno').removeAttr("disabled");
				$( "#dialog-form" ).dialog( "open" );
				vaction='add';
			});
			
	
	$( "#dlt" ).click(function() {
		
					mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
			if(mpart!==''){
				
				//alert(mpart);
				
				$xaction='delete';
			    $( "#dialog-confirm" ).dialog('open');				
			
			}else{
				alert('please at least select 1 document to delete!');	
				
			}
			
			});
	
	
	$( "#chg" ).click(function() {
				var x=$('input:checked').length;
			if(x>0){
				if(x>1){
					alert('please choose only one row to edit');
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
					$( "#dialog-form" ).dialog( "open" );
				}
			}else{
				alert('please select record that you want to edit!');
			}
			});
	
	$( "#cmdSave" ).click(function() {
				
				
				var edata;
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				edata="&periode="+periode+"&orderno="+orderno+"&corno="+corno;
				//edata="&periode="+periode+"&orderno="+orderno+"&action=edit";
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
				
				var answer = confirm("Do you want to close without saving?")
				if (answer){
				var edata;
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				edata="&periode="+periode+"&orderno="+orderno+"&corno="+corno+"&action=close";
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
								$('#result').html(data);
								}
						});

			
				}
			
			//-------------------------------------------------------
			
			
			
			});		
		

				
				
			
				
	// dialog confirm
	$( "#dialog-confirm" ).dialog({
			autoOpen: false,					  
			resizable: false,
			height:200,
			modal: true,
			buttons: {
				"Delete selected": function() {
					$.each($('.chkaction:checked'), function() {
						$(this).parent().parent().remove();								 
        		
    			});
					//==================================================
			
				var edata;
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				edata="&periode="+periode+"&orderno="+orderno+"&partno="+mpart;
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
					$("#dialog-message" ).dialog( "close" );
				}
			}
		});

		
	});
</script>


	</head>
	<body >
    
<?
	   
	   //get from server
	   $vperiode=trim($_GET['periode']);
	   $vordno=trim($_GET['ordno']);
	   $vcusno=trim($_GET['cusno']);
	   $vcdate=trim($_GET['cdate']);
	   $vcorno=trim($_GET['corno']);
	   
	   $vbln=substr($vperiode,-2);
	   $vthn=substr($vperiode,0,4);
	   $vdmy=substr($vcdate,-2)."-".substr($vcdate,4,2)."-".substr($vcdate,0,4);
	   //Order Number and Date 
	   	   
		$cyear=date('Y');
		$cmonth=date('m');
		$cdate=date('d');
		$cymd=date('Ymd');
		if((int)$cdate>15){
			$cmonth=(int)$cmonth+2;
		}else{
			$cmonth=(int)$cmonth+1;
		}
		if((int)$cmonth>12){
				$cmonth=(int)$cmonth-12;
				$cyear=(int)$cyear+1;
		}
		$xmonth=str_pad((int) $cmonth,2,"0",STR_PAD_LEFT);
		$inputmonth="<input name=\"prdmonth\" type=\"text\"  id=\"prdmonth\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"4\" size=\"4\" value=".$vbln.">";
		$inputyear="<input name=\"prdyear\" type=\"text\"  id=\"prdyear\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"5\" size=\"5\" value=".$vthn.">";
				
		$xperiode=$cyear.$xmonth;
		
		$inputordno="<input name=\"orderno\" type=\"text\"  id=\"orderno\" class=\"arial11blackbold\" readonly=\"true\" value=".$vordno.">"; 
		
		if($vperiode!=$xperiode){
			$disablebtn="1";
		}
	?>
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="main.php" target="_self">Ordering</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li><a href="Table" target="_self">Table</a></li>
  				<li ><a href="logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
        	  <h3 class="headerbar">Order Data</h3>
              <ul><li id="current"><a href="#"> Regular Order</a></li>
              			   <li><a href="mainadd.php" target="_self">Additional Order</a></li>
                		<li><a href="History.php" target="_self">History</a></li>
            		
		</ul>
              
          </div>
        </div>
        <div id="twocolRight">
        
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
    <td>Order Date</td>
    <td>:</td>
    <td><? echo $vdmy ?></td>
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
    <td>Order Periode</td>
    <td>:</td>
    <td colspan="5">
	<table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="16%" class="arial11redbold">Month :</td>
    <td width="16%"><? echo  $inputmonth ?></td>
    <td width="18%" class="arial11redbold"> Year :</td>
    <td width="50%"><? echo $inputyear   ?> </td>
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
    <td>Order Number</td>
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
    <td>Po Number</td>
    <td>&nbsp;</td>
    <td  class="arial11blackbold"><input name="corno" type="text"  id="corno" class="arial11blackbold" value=
    <? echo $vcorno
	?>
     ></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        </table>
        <p>&nbsp;</p>
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr align="right">
            <td width="10%" ><input type="button" value="Save order Entry" id="cmdSave" class="arial11blackbold">              </input></td>
            <td width="10%"><input type="button" class="arial11blackbold" id="cmdClose" value="Close order Entry"></input></td>
            <td width="10%">&nbsp;</td>
            <td width="4%">&nbsp;</td>
            <td width="29%"></td>
            
            <? if($disablebtn!="1"){
				echo "<td width=\"37%\"><button class=\"btn\" id=\"add\"><span class=\"arial11blackbold\"><img src=\"images/add.png\" width=\"18\" height=\"18\"></span></button>
              <button class=\"btn\" id=\"chg\"><span class=\"arial11blackbold\"><img src=\"images/edit.png\" width=\"18\" height=\"18\"></span></button>
              <button class=\"btn\" id=\"dlt\"><span class=\"arial11blackbold\"> <img src=\"images/delete.png\" width=\"18\" height=\"18\"></span></button></td>";
			}else{
				echo "<td width=\"37%\"></td>";
			}
			?>	
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
        <table width="97%" border="1" cellspacing="0" cellpadding="0" id="myTable">
          <tbody>
          <tr class="arial11redbold" bgcolor="#C5C5C5" align="center" >
            <td width="7%" height="30">Select</td>
            <td width="37%">Part Number</td>
            <td width="46%">Part Description</td>
            <td width="10%">Qty</td>
          </tr>
          <?
		  require('db/conn.inc');
	$query="select * from ".$table. " where trim(cusno) ='".$cusno. "' and trim(orderno)='".$vordno."' order by partno";
		$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$partdes=$hasil['partdes'];
				$qty=$hasil['qty'];
			echo "<tr><td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" . $partno . "></td>" ;
			echo "<td>".$partno."</td><td>".$partdes."</td><td class=\"qty\">".$qty."</td></tr>";
			}
		  
		  
		  ?>
          
          
          
          </tbody>
          
        </table>
        <p><div id="result"></div></p>
        <div class="demo">
        
        
        
   	  <div id="dialog-form" title="Add Order Detail">
				<p class="validateTips">All form fields are required.</p>


					<form>
						<fieldset>
							<label for="partno">Part Number</label>
									<input type="text" name="partno" id="partno" class="text ui-widget-content ui-corner-all" />
        
									<label for="qty">Order Qty</label>
									<input type="text" name="qty" id="qty" value="" class="text ui-widget-content ui-corner-all" />
        
        
        
						</fieldset>
					</form>
    			
                
                </div>
		
  <div id="dialog-confirm" title="Delete Selected Record?">
				<p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Selected  items will be permanently deleted and cannot be recovered. Are you sure?</p>
		</div>
        <div id="dialog-message"" title="Error Message!">
				<p id="message" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please fill required item before add Transaction detail!</p>
		</div>

        
    
    
        
        
        </div>
        
    
    
    
    </div>
              
<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
