<? session_start() ?>
<?
if (session_is_registered('cusno'))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
		$_SESSION['user'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$_SESSION['type'];
		$_SESSION['custype'];
		

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
				var corno=$('#corno').val();
				var quantity=qty.val();
				edata="partno="+epartno +"&periode="+periode+"&orderno="+orderno+"&corno="+corno+"&qty="+quantity+"&action="+vaction;
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
								var xdata=data.split("||");
								var itdsc=xdata[0];
								var bprice=xdata[1];
								var disco=xdata[2];
								var ttl=xdata[3];
								if(vaction=='add'){
								$('#myTable > tbody:last').append( "<tr>" +
								"<td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" + partno.val() + "/></td>" +  
								"<td>" + partno.val() + " - "+itdsc+ "</td>" + 
								"<td class=\"qty\" align=\"right\">" +qty.val()+"</td>" +
								"<td class=\"price\" align=\"right\">" + bprice + "</td>" +
								"<td class=\"disco\" align=\"right\">" + disco + "</td>" +
								"<td id=\"amount\" align=\"right\">" + ttl + "</td>" +
								"</tr>" );
								}else{
									
									$.each($('.chkaction:checked'), function() {
										$(this).closest('tr').children('td[class=qty]').text(quantity);								 						   		
										$(this).closest('tr').children('td[class=price]').text(bprice);
										$(this).closest('tr').children('td[class=disco]').text(disco);
										$(this).closest('tr').children('td[id=amount]').text(ttl);
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
				
				vaction='delete';
			    $( "#dialog-confirm" ).dialog('open');				
			
			}else{
				$("p[id=message]").text('please at least select 1 document to delete!');
					$( "#dialog-message" ).dialog( "open" );
				//alert('please at least select 1 document to delete!');	
				
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
				$("p[id=message]").text('please at least select 1 document to edit!');
					$( "#dialog-message" ).dialog( "open" );
			}
			});
	
	$( "#cmdSave" ).click(function() {
				var rowCount = $('#myTable >tbody >tr').length;
				if(rowCount==1){
					$("p[id=message]").text('There are no Transaction to save, Please use close button!');
					$( "#dialog-message" ).dialog( "open" );
					return;
				}
					
		
				
				var edata;
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				var action=$('#action').val();
				edata="&periode="+periode+"&orderno="+orderno+"&corno="+corno+"&action="+action;
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

			
			
			
			//-------------------------------------------------------
				}
			
			
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
	   include "crypt.php";
	   $var = decode($_SERVER['REQUEST_URI']);
	   $xordno=trim($var['ordno']);
	   $vcusno=trim($var['cusno']);
	   $vcdate=trim($var['orddate']);
	   $vcorno=trim($var['corno']);
	   $xmonth=trim($var['prdmonth']);
	   $cyear=trim($var['prdyear']);
	   $action=trim($var['action']);
	   require('db/conn.inc');
	$query="select * from cusmas where trim(cusno) ='".$vcusno. "'";
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
	 	$vcusnm=$hasil['Cusnm'];  
	}
	
  
	   
	   echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=".$action.">";
	   
		$inputmonth="<input name=\"prdmonth\" type=\"text\"  id=\"prdmonth\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"4\" size=\"4\" value=".$xmonth.">";
		$inputyear="<input name=\"prdyear\" type=\"text\"  id=\"prdyear\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"5\" size=\"5\" value=".$cyear.">";
		
		$txtcorno="<input name=\"corno\" type=\"text\"  id=\"corno\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value=".$vcorno.">";
	
		$inputordno="<input name=\"orderno\" type=\"text\"  id=\"orderno\" class=\"arial11blackbold\" readonly=\"true\" value=".$xordno.">"; 
		
	?>
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
    <td width="22%">Customer Number</td>
    <td width="2%">:</td>
    <td width="26%"><? echo $vcusno ?></td>
    <td width="4%"></td>
    <td width="20%">Customer Name</td>
    <td width="2%">:</td>
    <td width="25%"><? echo $vcusnm ?></td>
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
    <td><? echo 
	substr($vcdate,-2)."/".substr($vcdate,4,2)."/".substr($vcdate,0,4)
	 ?></td>
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
    <td>Denso Order No.</td>
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
    <td>PO Number</td>
    <td>:</td>
    <td  class="arial11blackbold">
    <? echo $txtcorno ?>
    </td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
        </table>
        <p>&nbsp;</p>
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr align="right">
            <td width="10%" ></td>
            <td width="10%"></td>
            <td width="10%">&nbsp;</td>
            <td width="4%">&nbsp;</td>
            <td><a href="
            <?
	 $action="Approve";
	 $lokasi="OrderReg.php?".paramEncrypt("action=$action&ordno=$xordno&cusno=$vcusno&corno=$vcorno");
	 		echo $lokasi;
            ?>
            
            "><img src="images/approve.png" border="0" ></a><a href="
            <?
	 $action="Reject";
	 $lokasi="OrderReg.php?".paramEncrypt("action=$action&ordno=$xordno&cusno=$vcusno&corno=$vcorno");
	 		echo $lokasi;
            ?>
            "><img src="images/reject.png" border="0"></a>
            <a href="mainAws.php"><img src="images/close.png" border="0"></a>
</td>
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
          <th width="35%" ><table class="tblorder"  width="100%" border="0" cellpadding="0" cellspacing="0"  id="myTable2">
            <tbody>
              <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
                <th width="35%" height="30">Part Number</th>
                <th width="11%" >Qty</th>
                <th width="19%" >Price</th>
                <th width="15%" >Discount</th>
                <th width="20%" class="lastth">total</th>
                  
              </tr>
              
              <?
		  require('db/conn.inc');
	$query="select * from orderdtl inner join bm008pr on partno=ITNBR "; 
	$query= $query . "where trim(cusno) ='".$vcusno. "' and trim(orderno)='".$xordno."' order by partno";
		$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$partdes=$hasil['ITDSC'];
				$qty=$hasil['qty'];
				$disc=$hasil['disc'];
				$bprice=$hasil['bprice'];
				$slsprice=$hasil['slsprice'];
				$disco=number_format(($bprice*$disc)/100,0,".",",");
				$ttl=number_format($slsprice*$qty,0,".",",");
				$vbprice=number_format($bprice,0,".",",");
			echo "<tr>";
			echo "<td>".$partno." - ". $partdes."<td class=\"qty\" align=\"right\">".$qty."</td>";
			echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>";								            echo "<td class=\"disco\" align=\"right\">" . $disco . "</td>" ;
			echo "<td id=\"amount\" class=\"lasttd\" align=\"right\">". $ttl . "</td>";
			echo "</tr>";
			}
		  
		  
		  ?>
              
              
              
            </tbody>
          </table>          </tr>
        </tbody>
        </table>
        <p><div id="result"></div></p>
        <div class="demo">
        
        
        
   	  <div id="dialog-form" title="Add Order Detail">
				<p class="validateTips">Reason </p>


					<form>
						<fieldset>
							<label for="partno">Reason
							  <textarea cols="40" rows="5" name="reason" class="text ui-widget-content ui-corner-all" id="reason" />                              
</textarea>
							</label>
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
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
