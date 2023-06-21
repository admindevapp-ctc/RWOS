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
	var  shpno=$( "#shpno" ),
	 itnbr=$( "#itnbr" ),	
	 curcd = $( "#curcd" ),
	 slsprice = $( "#slsprice" ),
	 cust2 = $( "#cust2" ),
	 cust3 = $( "#cust3" ),
	 allFields = $( [] ).add( shpno ).add( curcd ).add( itnbr).add( slsprice ).add( cust2 ).add( cust3 ),
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

function checkLengthmax( o, n, min, max ) {
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
			width: 600,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				"save Sales Price": function() {
					var bValid = true;
					var vprice=$("#slsprice" ).val();
					
					
					allFields.removeClass( "ui-state-error" );
					//alert(excrate);
					bValid = bValid && checkLengthmax( shpno, "Customer Number", 3,8 );
					bValid = bValid && checkLengthmax( itnbr, "Item Number", 8,15 );
					bValid = bValid && checkLength( curcd, "currency code", 2 );
					bValid = bValid && checkRegexp( slsprice, /^[\d ]+([.,][\d ]+)?$/, "Price should be Numeric value" );
					
				
			//alert(bValid);
					/** check part number **/
				
				var vcurcd=curcd.val();
				var vshpno=shpno.val();
				var vitnbr=itnbr.val();
				var edata;
				
				edata="shpno="+vshpno +"&itnbr="+vitnbr+"&curcd="+vcurcd+"&price="+vprice+"&action="+vaction;
				//alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getslsprice.php',
						//data: "curcd="+ecurcd,
						data: edata,
						success: function(data) {
							xdata=jQuery.trim(data);
							//alert(data);
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
				var shpno=xdata[0];
				var itnbr=xdata[1];
				var curcd=xdata[2];
				var price=xdata[3];
				
				$('#shpno').val(shpno);
				$('#shpno').attr("disabled", true);
				$('#itnbr').val(itnbr);
				$('#itnbr').attr("disabled", true);
				$('#curcd').val(curcd);
				$('#slsprice').val(price);
				
				vaction='edit';
					$( "#dialog-form" ).attr("title","change record");
					//var xtitle=$( "#dialog-form" ).attr('title')
					$("span.ui-dialog-title").text('Edit Record'); 
					//alert(xtitle);
					$( "#dialog-form" ).dialog( "open" );
						
			
			});



	$( "#new" ).click(function() {
				$('#shpno').removeAttr("disabled");
				$('#itnbr').removeAttr("disabled");
		
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
				curcd.removeClass( "ui-state-error" );
				$("span.ui-dialog-title").text('Add New Record'); 
				$( "#dialog-form" ).dialog( "open" );
				vaction='add';
			});
			

	$('#ConvExcel').click(function(){
						url= 'gettblslsXLS.php',
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
			  	$_GET['current']="mainSlsAdm";
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
    <td colspan="6" class="arial21redbold">Sales Price Maintenance</td>
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
    <th valign="middle" scope="col"><a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a></th>
    <th scope="col"  align="right">
    <? 
	if($type!="v") echo "<a href=\"imsales.php\"><img src=\"../images/importxls.gif\" width=\"80\" height=\"20\"></a>";
	?>
    </th>
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
  	<th width="20%" scope="col">Customer</th>
    <th width="20%" scope="col">Item Number</th>
    <th width="10%" scope="col">Currency</th>
    <th width="20%" scope="col">Sales Price</th>
    <th width="10%" scope="col">CUST3</th>
    <th width="20%" scope="col">action</th>
    </tr>
    
     <?
		  require('../db/conn.inc');
		  
		  $per_page=10;
		  $num=5;
		  
	$query="select * from sellprice";
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
	
	$query1="select * from sellprice   order by cusno, itnbr".		
	       " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query1);	
		
			while($hasil = mysqli_fetch_array ($sql)){
				$vcusno=$hasil['Cusno'];
				$vcurcd=$hasil['CurCD'];
				$vitnbr=$hasil['Itnbr'];
				$vprice=$hasil['Price'];
				$vcust2=$hasil['CUST2'];
				$vcust3=$hasil['CUST3'];
				
		echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vcusno."</td><td>".$vitnbr."</td><td>".$vcurcd."</td><td>".$vprice."</td><td>".$vcust3."</td>" ;
			
			echo "<td class=\"lasttd\">";
			echo "<a href='getslsprice.php?action=delete&shpno=$vcusno&itnbr=$vitnbr' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			
			echo "<a href='#' class='edit' id=".$vcusno."||".$vitnbr."||".$vcurcd."||".$vprice."||".$vcust2."||".$vcust3."> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
			
			echo "<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"6\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";
		}
		
		
		  ?>
 
 <tr>
    <td colspan="6"  align="right" class="lasttd" ><img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span></td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>

<div id="dialog-form" title="Add Order Detail" >
				<p class="validateTips">All form fields are required.</p>


					<form>
                    <table width="500" border="0">
  <tr>
    <td><span class="arial11redbold">Customer Number</span> :</td>
    <td><input type="text" size="12" maxlength="10" name="shpno" id="shpno"  /></td>
    <td>&nbsp;</td>
    <td><span class="arial11redbold">Item Number</span></td>
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
    <td><span class="arial11redbold">Currency Code</span></td>
    <td><input type="text" size="3" maxlength="2" name="curcd" id="curcd"  /></td>
    <td>&nbsp;</td>
    <td><span class="arial11redbold">Sales Price</span></td>
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
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
                    </table>
            
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
