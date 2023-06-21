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


include('chklogin.php');
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
    <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
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
	})	   
		   
		   
    
		
		/**$('.add').click(function(){
			$('#myTable tr:last').clone(true).insertAfter('#myTable tr:last');
		});**/
	var vaction="";
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var res="";
	var partno = $( "#partno" ),
			desc = $( "#description" ),
			allFields = $( [] ).add( partno ).add( desc ),
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
		
			width: 450,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				"save RFQ Detail": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );

					bValid = bValid && checkLength( partno, "Part Number", 2, 15 );
					bValid = bValid && checkRegexp( partno, /([0-9a-z_-])+$/i, "Part number may consist of a-z,-, 0-9" );
					bValid = bValid && checkLength( desc, "description", 2, 30 );
					//bValid = bValid && checkRegexp( qty, /^([0-9])+$/, "Qty field only allow : 0-9" );
			
					/** check part number **/
				
				epartno=partno.val();
				var edata;
				
				var vdesc=desc.val();
				var shpno=$('#shpno').val();
				edata="prtno="+epartno +"&desc="+vdesc+"&action="+vaction;
				
				//alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getrfqno.php',
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
								
							
								
						
								$( "#dialog-form").dialog( "close" );
								window.location.reload();
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
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
				partno.removeClass( "ui-state-error" );
				$("span.ui-dialog-title").text('Add New Record'); 
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
						edesc=($(this).closest('tr').children('td[class=description]').text());
						
    				});
					
					$('#partno').val(epart);
					 $('#partno').attr("disabled", true);
					$('#description').val(edesc);
					vaction='edit';
					//$( "#dialog-form" ).attr("title","change record");
					//var xtitle=$( "#dialog-form" ).attr('title')
					$("span.ui-dialog-title").text('Edit Record'); 
					//alert(xtitle);
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
				
				var rfqno=$('#rfqno').val();
				var action=$('#action').val();
				var shpno=$('#shpno').val();
				edata="&rfqno="+rfqno+"&shpno="+shpno+"&action="+action;
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saverfq.php',
						data: edata,
						success: function(data) {
								$('#result').html(data);
								}
						});

			
			
			
			//-------------------------------------------------------
			
			
			
			});
	
				
		$( "#cmdClose" ).click(function() {
				pos =$( this ).attr("data-tip");
				if(pos=='edt'){
					var answer = confirm("Do you want to close without saving?");
					if (answer){
				
						var edata;
						var rfqno=$('#rfqno').val();
						var action=$('#action').val();
						var mpage=$('#mpage').val();
						var shpno=$('#shpno').val();
						edata="&rfqno="+rfqno+"&shpno="+shpno+"&action=close";
						$.ajax({
							type: 'GET',
							url: 'saverfq.php',
							data: edata,
							success: function(data) {
								$('#result').html(data);
								}
						});

					}
			
			
			//-------------------------------------------------------
				}else{
					//alert(pos);
					if(pos=='viewN'){
						document.location.href='mainRFQ.php';
					}else{
						document.location.href='mainRFQHis.php';
					}
						
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
				var action='delete';
				edata="&prtno="+mpart+"&action=delete";
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'getrfqno.php',
					//	url: 'delpartno.php',
						data: edata,
						success: function(data) {
								//alert('test');
								window.location.reload();
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

				var ok=susu(partno.val());
			//create config object
			//alert(ok);
			            var shpno=$('#shpno').val();
								//alert(shpno);	
    					$("#partno").autocomplete({
											  
								source: "getAutopartnoRFQ.php",
								minLength: 2,		 
							   select: function( event, ui ) {
									var terms = ui.item.value;
									//alert(terms);
									//return false;
									}  
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
									url: 'getAutopartnoRFQ.php?shpno='+shpno,
									data: edata,
									success: function(data) {
										return data;
										alert(data);
										}
									});
							}
						}
						
	// Tool Tips
	$(".btn img[title]").tipsy({gravity: 'e'});
	
		 
	});
</script>


	</head>
	<body >
    
<?
	   include "crypt.php";
	   $var = decode($_SERVER['REQUEST_URI']);
	   $xrfqno=trim($var['rfqno']);
	   $action=trim($var['action']);
	   $vshpno=trim($var['shpno']);
	   $history=trim($var['history']);
	   
	  
	 /**  $iki='JK01010-111145';
	   echo 'panjang iki='. strlen($iki);
	   echo '<br>';
	   echo '7 charcter paling kanan='. substr(substr($iki, -7),0,1);
	   **/
	/**$qrycusmas="select cusno, cusnm from cusmas where cust3= '$cusno' ";
	$arr1=array();
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	while($hslcusmas = mysql_fetch_object ($sqlcusmas)){
	array_push($arr1, $hslcusmas);
	};
	echo json_encode($arr1);**/
	   $inputrfq="<input name=\"rfqno\" type=\"text\"  id=\"rfqno\" class=\"arial11blackbold\" readonly=\"true\" value=".$xrfqno.">"; 
	   echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=".$action.">";
	   $inputshpno="<input name=\"shpno\" type=\"text\"  id=\"shpno\" class=\"arial11blackbold\" readonly=\"true\" value=".$vshpno.">";
	  //echo uniqid();
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
		$fldtmp=array();
		$fldtmp=($_SESSION['sip']);
		$idxprt=getkey($fldtmp, 'C');
		
		
function getkey($products, $needle)	{
	$jml=count($products);
 	$found='';
 	for($i = 0; $i < $jml; $i++) {
		if($products[$i]['sts']==$needle){
			$found='1';
			return true;
			//echo 'ketemu di='.$i."<br>";
			break;
	 	}
 	}
   if($found!='1'){
	      return false;
   	}
}	
	?>
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       		<ul>  
  				<li id="current"><a href="#" onClick="alert('Please use Close Order Entry button to move from transaction menu! ')">Ordering</a></li>
				<li><a href="#" onClick="alert('Please use Close Order Entry button to move from transaction menu! ')">User Profile</a></li>
  				<li><a href="#" onClick="alert('Please use Close Order Entry button to move from transaction menu! ')">Table Part</a></li>
  				<li ><a href="#" onClick="alert('Please use Close button to move from transaction menu! ')">Log out</a></li>
  				  				
			</ul>
        
			
		</div> 
    	<div id="isi">
        <div id="twocolRight1">
           
       
        
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
   <?php
	if($action!="new"){
  		echo "<tr class=\"arial11blackbold\">";
  		echo  "<td>RFQ No</td>";
   		echo " <td>:</td>";
   		echo " <td>";
   		echo $inputrfq;
   		echo "</td>";
   		echo "<td></td>";
   		echo "<td>&nbsp;</td>";
   		echo"<td>&nbsp;</td>";
   		echo "<td>&nbsp;</td>";
  		echo "</tr>";
	}
  ?>
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
    <td>Inquiry Date</td>
    <td>:</td>
    <td><? echo date("d-m-Y") ?></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>Ship To</td>
    <td>:</td>
    <td>
    <? 
	echo $inputshpno
	?>
    </td>
    <td colspan="4"> <? 
	echo $alamat;
	?></td>
    </tr>
        </table>
        <p>&nbsp;</p>
        
        
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr >
            
           
            <?
            if($idxprt==false){
            	 echo '<td width="10%" ><input type="button" value="Save Request For Quotation"  id="cmdSave" class="submit_buttoncp" /></td>';
	           echo '<td width="10%" ><input type="button" id="cmdClose" data-tip="edt" value="Close Request For Quotation"  class="submit_buttoncp" /></td>';
            	echo '<td width="43%" ></td>';
				echo '<td width="37%" align="right"><button class="btn" id="add"><span class="arial11blackbold"><img src="images/add.png" title="add new record" width="18" height="18"></span></button>';
              echo '<button class="btn" id="chg"><span class="arial11blackbold"><img src="images/edit.png" title="Change record" width="18" height="18"></span></button>';
              echo '<button class="btn" id="dlt"><span class="arial11blackbold"> <img src="images/delete.png" title="Delete record" width="18" height="18"></span></button></td>';
			}else{
				  echo '<td width="10%" ><input type="button" id="cmdClose" data-tip="view' .$history. '" value="Close Request For Quatation"  class="submit_buttoncp" /></td>';		
					echo '<td width="43%" ></td>';
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
        <table class="tblorder"  width="97%" border="0" cellpadding="0" cellspacing="0"  id="myTable">
         <tbody>
          <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
            <th width="3%" height="30">Sel</th>
            <th width="15%" >Part Number</th>
            <th width="20%" >Description</th>
            <th width="10%" >Reply</th>
			<th width="20%" >Remark</th>
			<th width="20%" class="lastth">Answer</th>
	        <th width="12%" class="lastth">Status
            </th>
           
          </tr>
          <?
		  require('db/conn.inc');
		 // print_r(
		 //print_r($_SESSION['sip']);
		  $answered=0;
		$jml=count($_SESSION['sip']);	
		for($i = 0; $i < $jml; $i++) {
			$rpldt= $_SESSION['sip'][$i]['rpldt'];
			if($rpldt==''){
			$rpldt='-';	
    		echo "<tr><td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value='" . $_SESSION['sip'][$i]['prtno'] . "'></td>" ;
			}else{
				
			echo "<tr><td>-</td>" ;	
			}
			echo "<td>";
			echo  $_SESSION['sip'][$i]['prtno'];
			echo "<td class=\"description\" align=\"left\">";
			echo  $_SESSION['sip'][$i]['desc'];
			echo "</td>";
			$rpldt= $_SESSION['sip'][$i]['rpldt'];
			if($rpldt=='')$rpldt='-';
			echo "<td class=\"rpldt\" align=\"left\">";
			echo  $rpldt;
			echo "</td>";
			
			$remark=$_SESSION['sip'][$i]['diasrmk'];
			if($remark=='')$remark='-';
			echo "<td class=\"remark\" align=\"left\">";
			echo  $remark;
			echo "</td>";
			$answer=$_SESSION['sip'][$i]['diasans'];
			if($answer=='')$answer='-';
			echo "<td class=\"remark\" align=\"left\">";
			echo  $answer;
			echo "</td>";
			echo "<td class=\"status\" align=\"center\">";
			$status=$_SESSION['sip'][$i]['sts'];
			switch(trim($status)){
			case "P":
				$sts="Pending";
				break;
			case "C":
				$sts="Closed";
				break;
			case "U":
				$sts="Uncompleted";
				break;
			}
			echo $sts;
			echo "</td>";
			echo "</tr>";
		}
			
			
			
			
		  
		  ?>
          
          
          
          
          </tbody>
        </table>
        <p><div id="result"></div></p>
        <div class="demo">
        
        
        
        
   	  <div id="dialog-form" title="Add RFQ Part Number" style="display: none;" >
				<p class="validateTips">All form fields are required.</p>


					<form>
						<fieldset>
							<label for="partno">Part Number</label>
									<input type="text" name="partno" id="partno" class="text ui-widget-content ui-corner-all" />
        
									<label for="qty">Description</label>
									<input type="text" name="decription" id="description" value="" class="text ui-widget-content ui-corner-all" />
        
        
        
						</fieldset>
					</form>
    			
                
                </div>
		
  <div id="dialog-confirm" title="Delete Selected Record?" style="display: none;>
				<p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Selected  items will be permanently deleted and cannot be recovered. Are you sure?</p>
		</div>
        
         <div id="dialog-message"" title="Error Message!" style="display: none;>
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
