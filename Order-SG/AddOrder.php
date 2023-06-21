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
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
	<script src="js/vendor/jquery-1.7.2.js"></script>
	<link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
	<script src="lib/jquery-ui-1.90.min.js"></script>
	<script src="js/jquery.iframe-transport.js"></script>
	<script src="js/jquery.fileupload.js"></script>
	<script src="js/jquery.fileupload-ui.js"></script>
    <script src="js/jquery.fileupload-fp.js"></script>
   
    <link rel="stylesheet" href="css/demos.css">
    <script type="text/javascript" src="lib/jquery.tipsy.js"></script>
     <link rel="stylesheet" href="css/tipsy.css" type="text/css" />
	<link rel="stylesheet" href="css/tipsy-docs.css" type="text/css" />

    <style>
		body { font-size: 62.5%; }
		/**label, input { display:block; }**/
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
		.ui-datepicker{
			z-index:1003;
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
		   
	//dialog message
	/**
	$( "#dialog-message" ).dialog({
						autoOpen: false,						  
						modal: true,
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});
	
	
	**/
	//-----------------------
    
	
	
	
		/**$('.add').click(function(){
			$('#myTable tr:last').clone(true).insertAfter('#myTable tr:last');
		});**/
	var vaction="";
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var res="";
	var partno = $( "#partno" ),
			qty = $( "#qty" ),
			allFields = $( [] ).add(partno).add(qty),
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
				if(max!=min){	
					updateTips( "Length of " + n + " must be between " +
						min + " and " + max + "." );
						return false;
					}else{
						updateTips( "invalid Due date" );
						return false;
					}
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
			 my: "center bottom",
 at: "center top",
   			of: window,
			
			width: 350,
			modal: true,
			
			buttons: {
				"save Order Detail": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
				
					bValid = bValid && checkLength( partno, "Part Number", 2, 15 );
					bValid = bValid && checkRegexp( partno, /([0-9a-z_-])+$/i, "Part number may consist of a-z,-, 0-9" );
					bValid = bValid && checkRegexp( qty, /^([0-9])+$/, "Qty field only allow : 0-9" );
					//if(bValid) checkDate( tgl);			
					/** check part number **/
				
				epartno=partno.val();
				var edata;
				
				var corno=$('#txtCorno').val();
				var orderno=$('#orderno').val();
				var quantity=qty.val();
				var ordtype=$('#OptOrderType').val();
				var shpno=$('#shpno').val();
				var oecus=$('#oecus').val();
				var shipment=$('#shipment').val();
				edata="partno="+epartno +"&orderno="+orderno+"&qty="+quantity+"&corno="+corno+"&ordtype="+ordtype+"&shpno="+shpno+"&oecus="+oecus+"&shipment="+shipment+"&action="+vaction;
			//	kdata="tanggal="+skr+"&duedate="+ymd;
			 //alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getAddpartno.php',
						//data: "partno="+epartno,
						data: edata,
						success: function(data) {
							
							if(data.substr(0,5)=='Error'){
								
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
				
				
								"<td id=\"amount\" align=\"right\">" +duedt + "</td>" +
								"</tr>" );
								}else{
									
									$.each($('.chkaction:checked'), function() {
										
										$(this).closest('tr').children('td[class=qty]').text(quantity);								 						   		
										$(this).closest('tr').children('td[class=price]').text(bprice);
										$(this).closest('tr').children('td[class=ttl]').text(ttlprice);
										$(this).closest('tr').children('td[id=ttlex]').text(ttlex);
									
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
				var ordtype=$('#OptOrderType').val();
				var transport=$('#OptTransport').val();
				if(transport=='' ||ordtype==''){
						
					//alert('Please fill the required item !');
					$("p[id=message]").text('Order Type and Delivery by field should be filled!');
					$( "#dialog-message" ).dialog( "open" );
					

					}else{
					$( "#dialog-form" ).dialog( "open" );
					vaction='add';
				}
			});
			
	
	$( "#dlt" ).click(function() {
		
					mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
			if(mpart!==''){
				
				//alert(mpart);
				
				$xaction='delete';
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
						etgl=($(this).closest('tr').children('td[class=tgl]').text());
    				});
					
					$('#partno').val(epart);
					 $('#partno').attr("disabled", true);
					$('#qty').val(eqty);
					$('#tglkirim').val(etgl);
					vaction='edit';
					$( "#dialog-form" ).dialog( "open" );
				}
			}else{
				alert('please select record that you want to edit!');
			}
			});
	
	$( "#cmdSave" ).click(function() {
				var rowCount = $('#myTable >tbody >tr').length;
				//alert(rowCount);
				if(rowCount==1){
					$("p[id=message]").text('There are no Transaction to save, Please use close button!');
					$( "#dialog-message" ).dialog( "open" );
					return;
				}
				
				var edata;
				var shpno=$('#shpno').val();
				var orderno=$('#orderno').val();
				var corno=$('#txtCorno').val();
				var action=$('#action').val();
				
				edata="&orderno="+orderno+"&corno="+corno+"&shpno="+shpno+"&action="+action;
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saveAddorder.php',
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
				var orderno=$('#orderno').val();
				var corno=$('#txtCorno').val();
				var shpno=$('#shpno').val();
				edata="&orderno="+orderno+"&shpno="+shpno+"&corno="+corno+"&action=close";
				//alert(edata);	
				$.ajax({
						type: 'GET',
						url: 'saveAddorder.php',
						data: edata,
						success: function(data) {
								$('#result').html(data);
								}
						});

			
			
				}
			//-------------------------------------------------------
			
			
			
			});		

		
				

	//fileupload

				
			
				
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
					$( this ).dialog( "close" );

				}
			}
		});

		$("#OptOrderType").change(function(){
			var orddate=$('#orddate').val();
			var ordtype=$('#OptOrderType').val();
			edata="orddate="+orddate +"&ordtype="+ordtype;
			//alert(edata);
			$.ajax({
						type: 'GET',
						url: 'getAddordno.php',
						//data: "partno="+epartno,
						data: edata,
						success: function(data) {
							
							if(data.substr(0,5)=='Error'){
							}else{
								$('#orderno').val(data);
							}
						}
					});
			
			
								  
		  });
	
	//
	var corno=$('#txtCorno').val();
	var shpno=$('#shpno').val();
	var ok='';
	var prog='';
	var progtext='';
	var canceltext='';
	 $('#fileupload').fileupload({
								 
        dataType: 'json',
        formData: {
		shpno: shpno,
		corno:corno	
		},
		autoUpload : false,
        add : function(e, data) {
		//	 alert ('add');
           var filex=data.files[0];
		   var goUpload = true;
     	   if (!(/\.(gif|jpg|jpeg|tiff|png)$/i).test(filex.name)) {
        	    alert('You must select an image file only');
            	goUpload = false;
        	}
       if (goUpload == true) {
	   		if (filex.size > 2000000) { // 2mb
            	alert('Please upload a smaller image, max size is 2 MB');
            	goUpload = false;
        	}
	   }
		 if (goUpload == true) {	 
			 var vOutput="";
             vOutput+="<tr><td width='300px'>"+filex.name+"</td>";
	     	 vOutput+="<td width='200px'><img src='images/progressbar.gif' width='200px' height='12px'></td>";
			// alert(vOutput);
	     	 vOutput+="<td width='100px'><div  id='"+filex.name +"'></div></td>";
			//vOutput+="<td width='100px'></td>";
         	 vOutput+="<td width='100px' id='idremove'><input type='button' class='fileCancel' ></td></tr>";
             $("#resultTable").append(vOutput)
         /**    $(".fileUpload").eq(-1).on("click",function(){ **/
		 	//prog= $(this).parent().parent().find('.bar');
		 	//progtext= $(this).parent().parent().find('#progress');
			//canceltext= $(this).parent().parent().find('.fileCancel');
	      	//ok=$(this).parent().parent().find('.fileUpload');
			//console.dir($this);
			canceltext= $('.fileCancel').eq(-1);
	      	data.submit();
		    //ok=$(this);
			
		
           /**  })
             $(".fileCancel").eq(-1).on("click",function(){
                  $(this).parent().parent().remove()
             })**/
		 }  //goupload true
         },
	     done: function (e, data) {
		 	
    	$.each(data.result.files, function (index, file) { 
			var filex=data.files[0];
			var slpgr=$(document.getElementById(filex.name));
			//var slpgr=$("#"+filex.name);
			//console.dir(slpgr);
			slpgr.parent().parent().remove()
			//ok.parent().parent().remove()
			 var vOutput="";
             vOutput+="<tr><td width='300px'>"+file.name+"</td>";
	     	 vOutput+="<td width='200px'><div id='progress'><div class='bar' style='width: 0%;'></div></div></td>";
	     	// vOutput+="<td width='100px'></td>";
			vOutput+="<td width='100px' id='view'><a href='server/php/files/"+file.name+"' target='_blank' ><img src='images/viewpic.png' border='0' > </a> </td>";
         	 vOutput+="<td width='100px' id='idremove'><input type='button' class='fileCancel' ></td></tr>";
			// alert(vOutput);
             $("#resultTable").append(vOutput)		
					
		 			$(".fileCancel").eq(-1).attr('id', 'fileDiv_' + file.name);
		 			$(".fileCancel").eq(-1).on('click', { filename: file.name, files: data.files }, function (event) {  
																											  				canceltext=$(this);
                        event.preventDefault();
            edata='filename='+event.data.filename+'&shpno='+shpno+'&corno='+corno;
			//alert(edata);
			$.ajax({
						type: 'GET',
						url: 'deletefile.php',
						data: edata,
						async: false,
						success: function(data) {
						if(data.substr(0,5)=='Error'){
							}else{
 							canceltext.parent().parent().remove()
							 }
						}
					});
                                                
                        data.files.length = 0;    //zero out the files array  
			                            
                    });

            

		   
		     });

        }

    });
	
	
	//
			$(".fileCancel1").click(function (event) {  
				var idfile=$(this).attr('id');	
				var pjg=idfile.length;
				//alert(idfile);
				//alert(pjg);
				var ids=idfile.substring(2, (pjg));
				//alert(ids);
				var canceltext=$(this);
				event.preventDefault();
            			edata='filename='+ids+'&shpno='+shpno+'&corno='+corno;
			//alert(edata);
						$.ajax({
							type: 'GET',
							url: 'deletefile.php',
							data: edata,
							async: false,
							success: function(data) {
							if(data.substr(0,5)=='Error'){
								}else{
 								canceltext.parent().parent().remove()
							 }
							}
				
			});
              	                                  
                        
			                            
                    });
	//---- end delete
	
	var ok=susu(partno.val());
			//create config object	
			             var shpno=$('#shpno').val();
						//alert(shpno);	
    					$("#partno").autocomplete({
								source: "getAutopartnospec.php?shpno="+shpno,
								minLength: 2		 
							  
						 });
	//	turn specified element into an auto-complete
   	  
	  function susu(prtno){
						//	alert(prtno);
							if(prtno.length!=0){
								var shpno=$('#shpno').val();
								var edata="&q="+prtno.val()+"&shpno="+shpno;
								//alert(partno.val().length);
								$.ajax({
									type: 'GET',
									url: 'getAutopartnospec.php?shpno='+shpno,
								
									data: edata,
									success: function(data) {
										return data;
										}
									});
							}
						}
	

//$(".btn img[title]").tipsy({gravity: 'e'});

	});
</script>


	</head>
	<body >
    
<?
		include "crypt.php";
	   $var = decode($_SERVER['REQUEST_URI']);
	   $vordno=trim($var['ordno']);
	   $vcusno=trim($var['cusno']);
	   $vcdate=trim($var['odrdate']);
	   $vcorno=trim($var['corno']);
	   $vshpno=trim($var['shpno']);
	   $vodrsts=trim($var['ordtype']);
	   $oecus=trim($var['oecus']);
	   $shipment=trim($var['shipment']);
	   $action=trim($var['action']);
	   if($shipment='A'){
			$vshipment='by Air';   
	   }else{
		   $vshipment='by Sea';
	   }
	   
	  
	   echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=".$action.">";
	   $query="select * from cusrem where cusno = '". $vshpno. "'";
		
        $sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
        if($hasil){
			$vremark=$hasil['remark'];
			$vcurcd=$hasil['curcd'];
			$alamat=$vremark . '  (' .$vcurcd.')' ;
			
		}
		$inputshpno="<input type=\"hidden\" name=\"shpno\" type=\"text\"  id=\"shpno\" class=\"arial11blackbold\"  value=".$vshpno.">";

	   	$inpoecus="<input name=\"oecus\" type=\"hidden\"  id=\"oecus\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='$oecus'>";
		if(strtoupper($oecus)=='Y'){
				
				$inpshipment="<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"   maxlength=\"20\" size=\"20\" readonly=\"true\" value='$shipment'>";	
		$inpshipmod= '<tr class="arial11blackbold"> <td>&nbsp;</td><td>&nbsp;</td>  <td>&nbsp;</td> <td  class="arial11blackbold">&nbsp;</td>';
		$inpshipmod=$inpshipmod.' <td></td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> </tr><tr > <td>&nbsp;</td>  <td class="arial11redbold">Shipment Mode</td>';
		$inpshipmod=$inpshipmod.' <td>:</td> <td colspan="5" class="arial11blackbold" >'. $vshipment.$inpshipment .'</td>    </tr>';
		
  
		
		}else{
			
				$inpshipment="<input name=\"shipment\" type=\"hidden\"  id=\"shipment\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value='S'>";		
				$inpshipmod=$inpshipment;
		}
		
	   
	   //echo  "<h3> isi action=".$action."</h3>";
	  //print_r($var);
	  //echo "<br>";
	  //echo $vcorno;
	 //echo $vordno;
	 
	  switch($vodrsts){
				case 'R':
					$ordsts='Regular';
					break;
					
				case 'U':
					$ordsts='Urgent';
					break;	
				case 'C':
					$ordsts='Campaign';
					break;	
				case 'S':
					$ordsts='Special';
					break;
				
				}
				switch($vdlvby){
				case 'a':
					$vtrans="by Air";
					break;
				case 'n':
					$vtrans="normal";
					break;
				case 'h':
					$vtrans="Hand Carry";
					break;
		
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
        
        <table width="97%" border="0"  bgcolor="#CCCCCC" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
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
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="3%">&nbsp;</td>
    <td width="27%" class="arial11redbold">Customer Number</td>
    <td width="3%">:</td>
    <td width="23%"><? echo $cusno ?></td>
    <td width="3%"></td>
    <td width="16%" class="arial11redbold">Customer Name</td>
    <td width="1%">:</td>
    <td width="24%"><? echo $cusnm ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td height="16">&nbsp;</td>
    <td><? echo  	$inputshpno ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td class="arial11redbold">Ship To</td>
    <td>:</td>
    <td colspan="5">
    <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="11%" class="arial11redbold"><? echo  $vshpno ?> </td>
    <td width="89%"> <? echo  $alamat ?></td>
  </tr>
	</table>
    </td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td class="arial11redbold">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td class="arial11redbold">Order Date</td>
    <td>:</td>
    <td><? 
		$orddt= date("d-m-Y");
		echo "<input name=\"orddate\" type=\"text\"  id=\"orddate\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"10\" size=\"10\" value=".$orddt.">";
			 
		?></td>
    <td></td>
    <td><span class="arial11redbold">Denso Order Number</span></td>
    <td>:</td>
    <td><input type="text" name="orderno" id="orderno" readonly="true" class="arial11blackbold" value=
    <?
		echo trim($vordno);
	?>
    ></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td class="arial11redbold">Po Number</td>
    <td>:</td>
    <td>
   <input name="txtCorno" type="text" class="arial11greybold" id="txtCorno"  value=
    <?
	//	echo trim($xcorno);
	echo "'".urldecode($vcorno)."'";
	
	?>
    /></td>
    <td></td>
    <td class="arial11redbold">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td class="arial11redbold">Order Type</td>
    <td>:</td>
    <td  class="arial11blackbold"><input type="text" name="OptOrderType" id="OptOrderType" class="arial11blackbold" style="width: 100px"  readonly="true" value =
    <?
	echo $ordsts;
	?>
    ></td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11redbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    </tr>
    <? echo $inpshipmod;
   ?>
      
    
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><span class="arial11redbold">Upload Related File</span></td>
    <td><span class="arial11blackbold">:</span></td>
    <td colspan="5"> <input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5"><table id="resultTable">
     <?
      require('db/conn.inc');
	$queryatc="select * from attachment  where trim(cusno) ='".$vshpno. "' and trim(corno)='".$vcorno."'";
	//echo $queryatc;
	$j=0;
	$sqlatc=mysqli_query($msqlcon,$queryatc);		
	while($hasilatc = mysqli_fetch_array ($sqlatc)){
   		$Output="<tr><td width='300px'>".$hasilatc['namefile']."</td>";
	    $Output=$Output. "<td width='200px'></td>";
	   $Output=$Output."<td width='100px' id='view'><a href='server/php/files/".$hasilatc['namefile']."' target='_blank' ><img src='images/viewpic.png' border='0' > </a> </td>";
        $Output=$Output."<td width='100px' ><input type='button' class='fileCancel1' id='id". $hasilatc['namefile']."' ></td></tr>";
		 echo $Output;
		//echo $hasilatc['namefile'];
		
	}
	?>
    </table>
    </td>
    
  </tr>
        </table>
       
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr align="right">
            <td width="10%" ><input type="button" value="Save order Entry" id="cmdSave" class="arial11blackbold">              </input></td>
            <td width="10%"><input type="button" class="arial11blackbold" id="cmdClose" value="Close order Entry"></input></td>
            <td width="10%">&nbsp;</td>
            <td width="4%">&nbsp;</td>
            <td width="29%"></td>
            <td width="37%"><button class="btn" id="add"><span class="arial11blackbold"><img src="images/add.png" width="18" height="18" title="add new record"></span></button>
              <button class="btn" id="chg"><span class="arial11blackbold"><img src="images/edit.png" width="18" height="18" title="Change record"></span></button>
              <button class="btn" id="dlt"><span class="arial11blackbold"> <img src="images/delete.png" width="18" height="18" title="Delete record"></span></button></td>
          </tr>
        </table>
      
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr class="arial11redbold"  align="center" >
            <td width="7%" height="10"></td>
            <td width="37%" class="arial11greybold"></td>
            <td width="46%"></td>
            <td width="10%"></td>
          </tr>
          </table>
        <table class="tblorder"  width="97%" border="0" cellpadding="0" cellspacing="0"  id="myTable">
         <tbody>
         <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
            <th width="3%" height="30">Sel</th>
            <th width="30%" >Part Number</th>
            <th width="10%" >Qty</th>
            <th width="3%" >Curr</th>
            <th width="13%" >Price</th>
             <th width="15%" >Amount</th>
              <th width="15%" >Amount SGD</th>
             
            <th width="10%" class="lastth">Due Date
            <br>(DD-MM-YYY)</th>
           
          </tr>

          <?
		  require('db/conn.inc');
	$query="select * from ".$table. "  where trim(cusno) ='".$vshpno. "' and trim(orderno)='".$vordno."' order by partno";
		$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$partdes=$hasil['partdes'];
				$duedt=substr($hasil['DueDate'],-2)."/".substr($hasil['DueDate'],4,2)."/".substr($hasil['DueDate'],0,4);
				$qty=$hasil['qty'];
				$curcd=$hasil['CurCD'];
				$disc=$hasil['disc'];
				$bprice=$hasil['bprice'];
				$slsprice=$hasil['slsprice'];
				$disco=number_format(($bprice*$disc)/100,0,".",",");
				$ttl=number_format($slsprice*$qty,2,".",",");
				$vbprice=number_format($bprice,2,".",",");
				$exrate=$hasil['SGPrice'];
				$ttlex=number_format($slsprice*$qty*$exrate,2,".",",");
			echo "<tr><td><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" . $partno . "></td>" ;
			
			echo "<td>".$partno." - ". $partdes."<td class=\"qty\" align=\"right\">".$qty."</td>";
			echo "<td class=\"curcd\" align=\"right\">" . $curcd . "</td>";								            echo "<td class=\"price\" align=\"right\">" . $vbprice . "</td>" ;
			echo "<td class=\"price\" align=\"right\">" . $ttl . "</td>" ;
			echo "<td class=\"price\" align=\"right\">" . $ttlex. "</td>" ;
			echo "<td id=\"duedt\" class=\"lasttd\" align=\"center\">". $duedt . "</td>";
			echo "</tr>";
			}
		  
		  ?>
          
          
          
          </tbody>
          
        </table>
        <p><div id="result"></div></p>
        <div class="demo">
        
        
        
<div id="dialog-form" title="Add Order Detail"  style="display: none;">
				<p class="validateTips">All form fields are required.</p>


					<form>
						<fieldset>
							<label for="partno" class="arial11redbold">Part Number</label>
							<input type="text" name="partno" id="partno"  class="text ui-widget-content ui-corner-all" />
							<br>
<label for="qty" class="arial11redbold">Order Qty</label>
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
