<? session_start() ?>
<?
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
		if($type!='a')header("Location:../main.php");
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
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
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
   
		   
		   
    
		
	
	var vaction="";
	var res="";
	var curcd = $( "#curcd" ),
			excrate = $( "#excrate" ),
			efdate = $( "#efdate" ),
			allFields = $( [] ).add( curcd ).add( excrate ).add( efdate ),
			tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min ) {
			if (o.val().length != min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be " +
					min  + " character." );
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
		
		
		$( "#efdate" ).datepicker({
			changeMonth: true,
			changeYear: true
			
		  });
		
		function checkTgl(duedate){
			var tgl=duedate;
			if(tgl.length == 10){
					var m=parseInt(tgl.substr(0,2)-1);
					var y=parseInt(tgl.substr(6,4));
					var d=parseInt(tgl.substr(3,2));
					var mo=tgl.substr(6,4) +tgl.substr(0,2);
					var ymd=new Date();
					ymd.setFullYear(y,m,d);
					
					var skr=new Date();	
					if(skr>ymd){
						$("#efdate").addClass( "ui-state-error" );
						updateTips( "cannot create or edit on previous date");
						return false;
					}else{
						return true;
						
					}
			}else{
					$("#efdate").addClass( "ui-state-error" );
					updateTips( "wrong format date");
					return false;
		}

	}
		
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
				"save cut Of Date": function() {
					var bValid = true;
					var vefdate= $("#efdate" ).val();
				
					
					allFields.removeClass( "ui-state-error" );
					//alert(excrate);

					bValid = bValid && checkLength( curcd, "currency code", 2 );
					bValid = bValid && checkRegexp( excrate, /^[\d ]+([.,][\d ]+)?$/, "Exchange rate should be Numeric value" );
					bValid = bValid && checkTgl(vefdate);
					
				
			//alert(bValid);
					/** check part number **/
				
				vcurcd=curcd.val();
				vexcrate=excrate.val();
				var edata;
				
				edata="curcd="+vcurcd +"&exrate="+vexcrate+"&efdate="+vefdate+"&action="+vaction;
				//alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getexrate.php',
						//data: "curcd="+ecurcd,
						data: edata,
						success: function(data) {
							xdata=jQuery.trim(data);
							
							if(xdata.substr(0,5)=='Error'){
								//alert(data);
								curcd.addClass( "ui-state-error" );
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
				Cancel: function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});

	
	
	
	
	
	
	$( ".edit" ).click(function() {
				pos =$( this ).attr("id");
				//alert(pos);
				var xdata=pos.split("||");
				var curcd=xdata[0];
				var exrate=xdata[1];
				var tgl=xdata[2];
				$('#curcd').val(curcd);
				$('#curcd').attr("disabled", true);
				$('#excrate').val(exrate);
				$('#efdate').val(tgl);
				$('#efdate').attr("disabled", true);
				vaction='edit';
					$( "#dialog-form" ).attr("title","change record");
					//var xtitle=$( "#dialog-form" ).attr('title')
					$("span.ui-dialog-title").text('Edit Record'); 
					//alert(xtitle);
					$( "#dialog-form" ).dialog( "open" );
						
			
			});



	$( "#new" ).click(function() {
				$('#curcd').removeAttr("disabled");
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
				curcd.removeClass( "ui-state-error" );
				$("span.ui-dialog-title").text('Add New Record'); 
				$( "#dialog-form" ).dialog( "open" );
				vaction='add';
			});
			

	
		$('#ConvExcel').click(function(){
						url= 'gettblexcXLS.php',
						window.open(url);	
					
		 })			
			
				

		 
	});
</script>



	</head>
	<body >
   		<div id="header">
        <img src="../images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="mainRFQ.php" target="_self">Marketing</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
               <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="mainExcAdm";
				include("navAdm.php");
			  ?>
        </div>
        <div id="twocolRight">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td width="19%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="3%"></td>
    <td width="19%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
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
    <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
    <td colspan="6" class="arial21redbold">Exchange Rate to Sin Dolar Maintenance</td>
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
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th scope="col" height="24">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"></th>
    <th scope="col"><a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></th>
    <th scope="col" align="right"><a href="#" id="new"><img src="../images/newtran.png" width="80" height="20"></a></th>
  </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col" height="24">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" valign="middle" scope="col"></th>
    <th width="20%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
</table>

     <table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11grey" bgcolor="#AD1D36" >
  	<th width="20%" scope="col">Currency</th>
    <th width="30%" scope="col">Exchange Rate</th>
    <th width="30%" scope="col">Effective Date From</th>
    <th width="20%" scope="col">action</th>
    </tr>
    
     <?
		  require('../db/conn.inc');
		  
		  $per_page=10;
		  $num=5;
		  
	$query="select * from excrate";
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	$query1="select * from excrate   order by EfDateFr DESC".		
	       " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query1);	
		
			while($hasil = mysqli_fetch_array ($sql)){
				$vcurcd=$hasil['CurCD'];
				$vefdate=$hasil['EfDateFr'];
				$vRate=$hasil['Rate'];
				$efdate=substr($vefdate,4,2)."/".substr($vefdate,-2)."/".substr($vefdate,0,4);
		echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vcurcd."</td><td>".$vRate."</td><td>".$efdate."</td>" ;
			
			echo "<td class=\"lasttd\">";
			echo "<a href='getexrate.php?action=delete&curcd=".$vcurcd. "&efdate=".$efdate."' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			
			echo "<a href='#' class='edit' id=".$vcurcd."||".$vRate."||".$efdate."> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
			echo "<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"5\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";
		}
		
		
		  ?>
 
 <tr>
    <td colspan="4"  align="right" class="lasttd" ><img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span></td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>

<div id="dialog-form" title="Add Order Detail" >
				<p class="validateTips">All form fields are required.</p>


					<form>
						<fieldset>
						  <label for="CurCD" class="arial11redbold">Currency Code</label><input type="text" size="3" maxlength="2" name="curcd" id="curcd"  /> 
							<br>
                            <label for="excrate" class="arial11redbold">Exchange Rate</label>
                            <input type="text" name="excrate" id="excrate"  /> 
							<br>
        <label for="EfDate" class="arial11redbold">Effective Date From</label>
        <br>
						  <input type="text" name="efdate" id="efdate" /> 
						<br>
                       
        
        
        
						</fieldset>
					</form>
    			
                
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
