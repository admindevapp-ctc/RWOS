<? session_start() ?>
<?
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
	var reason = $( "#reason" ),
			allFields = $( [] ).add( reason),
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
		
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 350,
			modal: true,
			buttons: {
				"Ok": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( reason, "Reason", 2, 200 );
					/** check reason**/
				
				ereason=reason.val();
				var edata;
				var vaction="Reject";
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				var vcusno=$('#txtcusno').val();
				edata="vcusno="+vcusno+"&periode="+periode+"&orderno="+orderno+"&corno="+corno+"&action="+vaction+"&mpartno="+mpart+"&reason="+ereason;
			
				
			
					/********************************************************/																	                   if ( bValid ) {
						$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
							 $.each($('.chkaction:checked'), function() {
								$(this).closest('tr').children('td[class=status]').text('Rejected');							 	$(this).attr('checked', false);
        						$( "#dialog-form").dialog( "close" );
								
    							});
								
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

	
	
	
	$( "#reject" ).click(function() {
			mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
			if(mpart!==''){					  
				$( "#dialog-form").dialog( "open" );
			}else{
				$("p[id=message]").text('please at least select 1 document to Reject!');
					$( "#dialog-message" ).dialog( "open" );
				//alert('please at least select 1 document to delete!');	
				
			}
			
			
	});
	
	$( "#approve" ).click(function() {
		
					mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
			if(mpart!==''){
				
				//alert(mpart);
				
				vaction='Approve';
			    $( "#dialog-confirm" ).dialog('open');				
			
			}else{
				$("p[id=message]").text('please at least select 1 document to Approve!');
					$( "#dialog-message" ).dialog( "open" );
				//alert('please at least select 1 document to delete!');	
				
			}
			
			});
	

	
	
	
				
		$( "#cmdClose" ).click(function() {
				var answer = confirm("Do you want to close without saving?")
				if (answer){
				
				var edata;
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var vcusno=$('#txtcusno').val();
				var corno=$('#corno').val();
				edata="vcusno="+vcusno+"&periode="+periode+"&orderno="+orderno+"&corno="+corno+"&action=close";
				alert(edata);	
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
				  "Approve selected": function() {
					$.each($('.chkaction:checked'), function() {
						$(this).closest('tr').children('td[class=status]').text('Approved');							 						$(this).attr('checked', false);
        		
    			});
					//==================================================
				var vaction="Approve";
				var edata;
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				edata="&periode="+periode+"&orderno="+orderno+"&action="+vaction+"&mpartno="+mpart;
			alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
								alert(data);
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
	$txtcusno="<input name=\"txtcusno\" type=\"text\"  id=\"txtcusno\" class=\"arial11blackbold\"  maxlength=\"10\" size=\"10\" readonly=\"true\" value=".$vcusno.">";
  
	   
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
    <td width="26%"><? echo $txtcusno ?></td>
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
            <td><a href="main.php"><img src="images/close.png" border="0"></a>
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
          <th width="35%" >
          <table class="tblorder"  width="100%" border="0" cellpadding="0" cellspacing="0"  id="myTable2">
            <tbody>
              <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
            	<th width="35%" height="30">Part Number</th>
                <th width="10%" >Qty</th>
            	<th width="15%" >Price</th>
            	<th width="13%" >Discount</th>
            	<th width="19%" class="lastth">total</th>
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
				$ordflg=$hasil['ordflg'];
				switch(trim($ordflg)){
					case "":
						$status="Pending";
						break;
					case "1":
						$status ="Approved";
						break;
					case "R":
						$status="Reject";
						break;
				}
				
				if(trim($ordflg)=="R"){
				$qryreject="select * from rejectorder where trim(orderno)='".$xordno."' ";
				$qryreject=$qryreject. " and partno='".$partno."'"; 
				$sqlreject=mysqli_query($msqlcon,$qryreject);		
					if($hsl = mysqli_fetch_array ($sqlreject)){
						$msg=$hsl['message'];
					}
				}
				
				
				
				
				$disco=number_format(($bprice*$disc)/100,0,".",",");
				$ttl=number_format($slsprice*$qty,0,".",",");
				$vbprice=number_format($bprice,0,".",",");
			echo "<tr>";
			if(trim($ordflg)=="R" & trim($msg)!=""){
				echo "<td rowspan=\"2\">".$partno." - ". $partdes."</td>";
			}else{
				echo "<td >".$partno." - ". $partdes."</td>";
			}
			
			echo "<td class=\"qty\" align=\"right\">".$qty."</td>";
			echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>";								            echo "<td class=\"disco\" align=\"right\">" . $disco . "</td>" ;
			echo "<td id=\"amount\" class=\"lasttd\" align=\"right\">". $ttl . "</td>";
			echo "</tr>";
			if(trim($ordflg)=="R" & trim($msg)!=""){
					echo "<tr>";
					echo "<td colspan=\"5\" class=\"lasttd\">";
					echo "<span class=\"arial11redbold\">Reason : ".$msg."</span>";
					echo "</td>";
					echo "</tr>";
				
			}
			
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
							<label for="reason">
							  <textarea cols="50" rows="4" name="reason" class="text ui-widget-content ui-corner-all" id="reason" />                              
</textarea>
							</label>
						</fieldset>
					</form>
    			
                
                </div>
		
  <div id="dialog-confirm" title="Approved Selected Record?">
				<p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Approved Selected document . Are you sure?</p>
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
