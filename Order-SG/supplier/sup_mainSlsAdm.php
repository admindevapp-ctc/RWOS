<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
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
		$supno=$_SESSION['supno'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='s'){
			header("Location:../main.php");
		}
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">
<!--

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
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

#pagination1 a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination1 a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination1 a.current 
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
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.effects.core.js"></script>
     <script src="../lib/jquery.ui.datepicker.js"></script>
   <script src="../lib/jquery.ui.autocomplete.js"></script>
  <link rel="stylesheet" href="../css/demos.css">   
    <style>
	
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
	
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
    
	<link rel="stylesheet" type="text/css" href="../css/custom-bootstrap.css">
     
<script type="text/javascript">
$(function() {

	if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
		//alert( "This page is reloaded" );
		window.location = window.location.href.split("?")[0];
	} 
	
	var vaction="";
	var res="";
	var  shpno=$( "#shpno" ),
	 itnbr=$( "#itnbr" ),	
	 curcd = $( "#curcd" ),
	 slsprice = $( "#slsprice" ),
	 cust2 = $( "#cust2" ),
	 cust3 = $( "#cust3" ),
	 shipto = $( "#shiptocus" ),
	 allFields = $( [] ).add( shpno ).add( curcd ).add( itnbr).add( slsprice ).add( cust2 ).add( cust3 ).add( shipto ),
	 tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
				/*setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );*/
		}

		function checkLength( o, n, min ) {
			if (o.val().length != min ) {
				o.addClass( "ui-state-error" );
				updateTips( "<?php echo get_lng($_SESSION["lng"], "W9024")?>" + n +  "<?php echo get_lng($_SESSION["lng"], "W9026")?>" +
					min  + "<?php echo get_lng($_SESSION["lng"], "W9025")?>" );
				return false;
			} else {
				return true;
			}
		}

		function checkLengthmax( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				if(max!=min){	
					updateTips( "<?php echo get_lng($_SESSION["lng"], "W9024")?>" + n + "<?php echo get_lng($_SESSION["lng"], "W9027")?>" +
						min + "<?php echo get_lng($_SESSION["lng"], "W9028")?>" + max + "<?php echo get_lng($_SESSION["lng"], "W9025")?>");
						return false;
					}else{
						updateTips(  "<?php echo get_lng($_SESSION["lng"], "E0052")?>"  );
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
			width: 600,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				"<?php echo get_lng($_SESSION["lng"], "L0487"); ?>": function() {
					var bValid = true;
					var vprice=$("#slsprice" ).val();
					
					
					allFields.removeClass( "ui-state-error" );
					//alert(excrate);
					bValid = bValid && checkLengthmax( shpno,  "<?php echo get_lng($_SESSION["lng"], "L0128")?>" , 3,8 );
					bValid = bValid && checkLengthmax( itnbr, "<?php echo get_lng($_SESSION["lng"], "L0464")?>", 2,15 );
					bValid = bValid && checkLength( curcd,  "<?php echo get_lng($_SESSION["lng"], "L0465")?>", 2 );
					bValid = bValid && checkRegexp( slsprice, /^[\d ]+([.,][\d ]+)?$/,"<?php echo get_lng($_SESSION["lng"], "E0077")?>" );
					//bValid = bValid && getshipno(shpno);
				
				//alert(bValid);
				/** check part number **/
				
				var vcurcd=curcd.val();
				var vshpno=shpno.val();
				var vitnbr=itnbr.val();
				var vshipto=shipto.val();
				var edata;
				
				edata="shpno="+vshpno +"&itnbr="+vitnbr+"&curcd="+vcurcd+"&price="+vprice+"&shipto="+vshipto+"&action="+vaction;
				//alert(edata);
				
			
					/********************************************************/																	                   
					if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getslsprice.php',
						//data: "curcd="+ecurcd,
						data: edata,
						success: function(data) {
							xdata=jQuery.trim(data);
							console.log(data);
							if(xdata.substr(0,5)=='Error'){
								//alert(data);
								//curcd.addClass( "ui-state-error" );
								$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
								updateTips( data);
								return false;
							}else{
							
								$( "#dialog-form").dialog( "close" );
								window.location.reload();
							}
						}
					});
						
				 }
				},
				<?php echo get_lng($_SESSION["lng"], "L0045"); ?>: function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				allFields.val( "" ).removeClass( "ui-state-error" );

				$("#shiptocus").empty();
				$("#txtshpaddr").text("");
			}
		});

	
			$( ".edit" ).click(function() {
				pos =$( this ).attr("id");
				//alert(pos);
				var xdata=pos.split("||");
				var shpno=xdata[0];
				var itnbr=xdata[1];
				var curcd=xdata[2];
				var price=xdata[3];
				var shipto=xdata[4];
				//getshipaddress(shpno);
				//getshipaddress($("#shpno").val());
				
				$('#shpno').val(shpno);
				$('#shpno').attr("disabled", true);
				$('#itnbr').val(itnbr);
				$('#itnbr').attr("disabled", true);
				$('#curcd').val(curcd);
				$('#slsprice').val(price);
				//$('#shipto').val(shipto);
				//$('#shipto').attr("disabled", true);
				//$('#txtshpaddr').text(shipto);
				//$('#txtshpaddr').attr("disabled", true);
				
				vaction='edit';
					//alert("click Edit" + shipto);
					$( "#dialog-form" ).attr("title","<?php echo get_lng($_SESSION["lng"], "L0530"); ?>");
					getshipno($("#shpno").val(),shipto);
					//var xtitle=$( "#dialog-form" ).attr('title')
					$("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0530")//Edit Record?>'); 
					//alert(xtitle);
					$( "#dialog-form" ).dialog( "open" );
						
			
			});



			$( "#new" ).click(function() {

				//Key check
				let timer,
				timeoutVal = 1000; // time it takes to wait for user to stop typing in ms

				const typer = document.getElementById('shpno');

				typer.addEventListener('keyup', handleKeyUp);

				function handleKeyUp(e) {
				clearTimeout(timer); // prevent errant multiple timeouts from being generated
					timer = setTimeout(function(){
						Updateshipto();
					}, timeoutVal);
					
				}


				
				$('#shpno').removeAttr("disabled");
				$('#itnbr').removeAttr("disabled");
				$('#shipto').attr("disabled", true);
				$('#txtshpaddr').attr("disabled", true);
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
				curcd.removeClass( "ui-state-error" );
				$("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0529")//Add New Record?>'); 
				$( "#dialog-form" ).dialog( "open" );
				vaction='add';
			});
			
			$.urlParam = function(name){
				var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
				if (results == null){
				return null;
				}
				else {
				return decodeURI(results[1]);
				}
			}
			
			$('#ConvExcel').click(function(){
				//let searchParams = new URLSearchParams(window.location.search)
				let cuscode =  $.urlParam('vcusno');
				let partno =  $.urlParam('vprtno');
				//alert(cuscode + partno)
				url= 'supgettblslsXLS.php?cuscode='+cuscode + '&partno=' +partno,
				window.open(url);	
				//alert(url);	
		 	});

			 function Updateshipto(){
				// alert("update");
				 $(".ui-dialog-buttonset").show();
				 $("#shiptostatus").text("");
				 getshipno($("#shpno").val(),"");
			 }



			 $("#shiptocus").change(function () {
				 //	alert("ddl change"+this.value );
					getshipaddress( $("#shpno").val(),this.value );
			 });


			 function getshipno(cusno, shiptoselected)
			 {
				// alert("getshipno" +shiptoselected);
				 if(shiptoselected != ""){//$("#shipto").empty();
					$.ajax({
					url: 'getcusship.php',
					type: 'post',
					dataType: 'JSON',
					data:{'cusno':cusno},
					success: function(response){
						$("#shiptocus").empty();
						var data=$.parseJSON(response);
						var html = "";
						var shipto ="";
						if(data.length != 0){
							$.each(data, function(index, value){
								html += "<option volve="+value.shipto+">"+value.shipto+"</option>";
								if(index==0){
									shipto = value.shipto;
								}
								//console.log( "Key: " + index + ", Value: " + value.shipto );
							});
							$("#shiptocus").append(html);
							$("#shiptocus").removeClass( "ui-state-error" );
							getshipaddress( $("#shpno").val(),shiptoselected);
							$("#shiptocus").val(shiptoselected);
							
							return true;
						}
						else{
							//html += "not found shipto";
							//$("#shiptostatus").text(html);

							$("#shiptocus").addClass( "ui-state-error" );
							updateTips( "<?php echo get_lng($_SESSION["lng"], "E0063"); //not found shipto"?>");
							return false;
							//$(".ui-dialog-buttonset").hide();
						}

					}
					});
				 }
				 else{
					//$("#shipto").empty();
					$.ajax({
					url: 'getcusship.php',
					type: 'post',
					dataType: 'JSON',
					data:{'cusno':cusno},
					success: function(response){
						$("#shiptocus").empty();
						var data=$.parseJSON(response);
						var html = "";
						var shipto ="";
						if(data.length != 0){
							$.each(data, function(index, value){
								html += "<option volve="+value.shipto+">"+value.shipto+"</option>";
								if(index==0){
									shipto = value.shipto;
								}
								//console.log( "Key: " + index + ", Value: " + value.shipto );
							});
							$("#shiptocus").append(html);
							$("#shiptocus").removeClass( "ui-state-error" );
							getshipaddress( $("#shpno").val(),shipto);
							
							return true;
						}
						else{
							//html += "not found shipto";
							//$("#shiptostatus").text(html);

							$("#shiptocus").addClass( "ui-state-error" );
							updateTips( "<?php echo get_lng($_SESSION["lng"], "E0063"); //not found shipto"?>");
							return false;
							//$(".ui-dialog-buttonset").hide();
						}

					}
					});
				 }
			 }

			 function getshipaddress(cusno,shipto)
			 {
				//alert(shipto);
				$("#shiptocus").removeClass( "ui-state-error" );
				$(".validateTips" ).empty()
				$(".validateTips" ).removeClass( "ui-state-highlight" );
				///alert("shipto" + shipto + "selected =" + shiptoseleted);
				$.ajax({
				url: 'getshipaddr.php',
				type: 'post',
				dataType: 'JSON',
    			data:{'cusno':cusno, 'shipto':shipto},
				success: function(response){
					var personObject = JSON.parse(response); 
					$("#shipto").val(personObject[0].shipto);
					$("#txtshpaddr").text(personObject[0].Address);
					shiptocus
				}
				});
			 }	
				
	});
</script>
</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
        <?php 
			  	$_GET['step']="1";
				include("supnavhoriz.php");
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
               <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="supmainSlsAdm";
				include("supnavAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        
  
       
        <?
		  require('../db/conn.inc');

		//Supplier
		$query="select * from supmas where Owner_Comp='$comp' and supno='$supno'  ";
		$sql=mysqli_query($msqlcon,$query);	
		//echo $query;
		while($hasil = mysqli_fetch_array ($sql)){
			
			$supno=$hasil['supno'];	
			$supnm=$hasil['supnm'];
			$suplogo= $hasil['logo'];
			$inpsupcode= $supno;	
			$inpsupname= $supnm;		
			
		}

		 $xcusno='';
		 $xprtno='';
		  if(isset($_GET["vcusno"])){
				$xcusno=$_GET["vcusno"];
				$xprtno=$_GET["vprtno"];
		  }
		 	$inpcusno= '<select name="vcusno" id="vcusno" class="arial11blue" style="width:200px;">';
			$inpcusno= $inpcusno .   ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
			$query="select distinct cusmas.Cusno, cusmas.Cusnm  from cusmas join supprice on cusmas.Cusno = supprice.Cusno and cusmas.Owner_Comp = supprice.Owner_Comp ".
			" where supprice.Owner_Comp='$comp' and supprice.supno='$supno'  order by cusmas.Cusno";
			//echo $query;
			$sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
				$ycusno=$hasil['Cusno'];
				$ycusnm=$hasil['Cusnm'];
				
				if(trim($ycusno)==trim($xcusno)){
				   	 $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'" selected>'.$ycusno.' - '. $ycusnm. ' </option>';
				}else{
					  	 $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'">'.$ycusno.' - '. $ycusnm. ' </option>';		
				}
				
			}
        			$inpcusno= $inpcusno . ' </select>';
			
			$inpprtno="<input type=\"text\" name=\"vprtno\"  value ='" . $xprtno. "' class=\"arial11black\" maxlength=\"30\" size=\"30\" style=\"width:200px;\"></input>";
		  ?>
		
        
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		<tr class="arial11blackbold" style="vertical-align: top;">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td width="10%" class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "M006");?></td>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
                <td rowspan="3" align="right" width="47%"><img src='<?php echo "../sup_logo/".$suplogo; ?>'  height="80" width="200" /></td>
            </tr>
			<tr class="arial11blackbold">
				<td colspan="2">
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0451");?></span>
					<span class="arial12Bold">:</span>
				</td>
				<td>
					<span class="arial12Bold"><? echo $supno?></span>	
				</td>
				<td>
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0452");?></span>
					<span class="arial12Bold">:</span> 
				</td>
                <td>
					<span class="arial12Bold"align="left"><? echo $supnm?></span>	
				</td>
       	 	</tr>
            <tr class="arial11blackbold">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>   
           
   <form name ="searchprice" method="get">
     <fieldset>
<legend> &nbsp;<?php echo get_lng($_SESSION["lng"], "L0456");?></legend>
 <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td ><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0037");?></span></div></td>
    <td><div align="center"><span class="arial12Bold">:</span></div></td>
    <td><span class="arial12Bold"><? echo $inpcusno?></span></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tbl1">
    <td class="arial12BoldGrey"><div align="right"></div></td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="16%" ><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0069");?> </span></div></td>
    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
    <td width="18%"><span class="arial12Bold"><? echo $inpprtno ?></span></td>
    <td width="3%" alidn="right"><input type="submit" name="button" id="button" value="<?php echo get_lng($_SESSION["lng"], "L0105");?>"" class="arial11"></td>
    <td width="19%"></td>
    <td width="15%">&nbsp;</td>
    <td width="20%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 </table>
   </fieldset>
   </form>
   <!--
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th scope="col" height="24">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"></th>
    <th colspan="2"  align="right" scope="col">
	<button type="button" class="btn btn-maroon btn-xs arial11white" onclick="window.location.href = 'supimslsprice.php'"><?php echo "IMPORT"; ?> </button>
    <button type="button" class="btn btn-maroon btn-xs arial11white" id="ConvExcel" title="<?php echo get_lng($_SESSION["lng"], "L0346")/* Export to XLS */; ?>"><?php echo get_lng($_SESSION["lng"], "L0346")/* Export to XLS */; ?></button>
    <button type="button" class="btn btn-maroon btn-xs arial11white" id="new" title="<?php echo get_lng($_SESSION["lng"], "L0371")/* Add New */; ?>"> <?php echo get_lng($_SESSION["lng"], "L0371")/* Add New */; ?> </button>


	</th>
  </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
</table>

-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                <th scope="col">&nbsp;</th>
                <th width="90" scope="col">
					<a href="supimslsprice.php" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0005"); ?></font>
                        </div>
                    </a>
                </th>
				<th width="10"></th>
                <th width="140" scope="col">
                    <div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
                        <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                        <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
                    </div>
                </th>
				<th width="10"></th>
                <th width="90" scope="col">
					<a id="new" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/new.png" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0006"); ?></font>
                        </div>
                    </a>
                </th>
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
            </table>


<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11whitebold" bgcolor="#AD1D36" >
  	<th width="15%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0458"); //Cuustomer?></th>
    <th width="25%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0464"); //Item Number?></th>
    <th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0459"); //Shipto?></th>
    <th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0465"); //Currency?></th>
    <th width="20%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0466"); //Sales Price?></th>
    <th width="20%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0467"); //action?></th>
    </tr>
<?
		
	$per_page=10;
	$num=5;

	$criteria=" where Owner_Comp='$comp' and supno='$supno' ";
	if(isset($_GET["vcusno"])){
		$xcusno=$_GET["vcusno"];
		$xprtno=$_GET["vprtno"];
		if(trim($xcusno)!=''){
			$criteria .= ' and Cusno="'.$xcusno.'"';
			if(trim($xprtno)!=''){
				$criteria .= ' and partno="'.$xprtno.'"';
			}
		}else{
			if(trim($xprtno)!=''){
				$criteria .= ' and partno="'.$xprtno.'"';
			}
		}
	}
	$query="select supprice.* "
	.", (select adrs1 from shiptoma where supprice.Cusno = shiptoma.Cusno and supprice.shipto= shiptoma.ship_to_cd "
	." and supprice.Owner_comp = shiptoma.Owner_Comp) as shipaddr from supprice ". $criteria;
	//echo $query ."<br/><br/>";
	
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	//echo $count;
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	

	$query1="select supprice.* , (select adrs1 from shiptoma where supprice.Cusno = shiptoma.Cusno and supprice.shipto= shiptoma.ship_to_cd"
	." and supprice.Owner_comp = shiptoma.Owner_Comp) as shipaddr from supprice" . $criteria . " order by cusno, partno".		
	       " LIMIT $start, $per_page";
  //echo $query1;
	$sql=mysqli_query($msqlcon,$query1);	
		
	if( ! mysqli_num_rows($sql) ) {
		echo "<tr height=\"30\"><td colspan=\"8\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... */ . "</td></tr>";
	}
			while($hasil = mysqli_fetch_array ($sql)){
				$vcomp=$hasil['Owner_comp'];
				$vcusno=$hasil['Cusno'];
				$vcurcd=$hasil['curr'];
				$vitnbr=$hasil['partno'];
				$vpshipto=$hasil['shipto'];
				$vprice=$hasil['price'];
				
			echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vcusno."</td><td>".$vitnbr."</td>";
			echo "<td>".$vpshipto."</td><td>".$vcurcd."</td><td>".$vprice."</td>" ;
			
			echo "<td class=\"lasttd\">";
			echo "<a href='getslsprice.php?action=delete&shpno=$vcusno&itnbr=$vitnbr&shipto=$vpshipto' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			
			echo "<a class='edit' id='".$vcusno."||".$vitnbr."||".$vcurcd."||".$vprice."||".$vpshipto."'> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
			
			echo "<td ></tr>";
			
			}
			
			require('pager.php');
			if($count>$per_page){		
				echo "<tr height=\"30\"><td colspan=\"8\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
				//echo $query;
				$fld="page";
				paging($query,$per_page,$num,$page);
				echo "</div></td></tr>";
			}
		
		
		  ?>
 
 <tr>
    <td colspan="8"  align="right" class="lasttd" >
		<img src="../images/delete.png" width="20" height="20"><span class="arial11redbold"> = delete</span>
		<img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span>
	</td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>

<div id="dialog-form" title="Add Order Detail" >
<p class="validateTips">All form fields are required.</p>


<form>
<table width="500" border="0">
  <tr>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0208"); //Customer Number ?></span> :</td>
    <td><input type="text" size="12" maxlength="10" name="shpno" id="shpno"  /></td>
    <td>&nbsp;</td>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0464"); //Item Number?></span></td>
    <td><input type="text" size="18" maxlength="15" name="itnbr" id="itnbr"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0465"); //Currency Code?></span></td>
    <td><input type="text" size="3" maxlength="2" name="curcd" id="curcd"  /></td>
    <td>&nbsp;</td>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0466"); //Sales Price?></span></td>
    <td><input type="text" name="slsprice" id="slsprice"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0459"); //Shipto?></span></td>
   <!--<td><input type="text" name="shipto" id="shipto"  /></td>-->
   	<td>
	   <select id="shiptocus"></select>
	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span id="shiptostatus"></span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0394"); //Ship to Address?></span></td>
    <td><span id="txtshpaddr" name="txtshpaddr" ></span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>
            
</form>
</div>

<div id="footerMain1">
	<ul>
      <!--
     
          
	 -->
      </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>
</div>
	</body>
</html>
