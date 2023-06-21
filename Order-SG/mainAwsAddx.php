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
    <script src="lib/jquery.ui.datepicker.js"></script>

	<link rel="stylesheet" href="css/demos.css">   
    <script type="text/javascript">

$(function() {
	
	//dialog message
	var vaction="";
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
			var res="";
			var corno = $( "#txtCorno" ),
			allFields = $( [] ).add(corno),
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

		/** check order type and Transport **/
		
		function checkVal( o, n) {
			if ( o.val().length ==0 ) {
				o.addClass( "ui-state-error" );
				if(max!=min){	
					updateTips(  n + " should be selected!" );
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
			height:600,
			width: 650,
			modal: true,
			buttons: {
				"save Order Detail": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					bValid = bValid && checkLength( corno, "Order Number", 2, 20 );
				
					
				var edata;
				var vcorno=corno.val();
				var vorderno=$('#orderno').val();
				var vorddate=$('#orddate').val();
				var vprdmonth=$('#prdmonth').val();
				var vprdyear=$('#prdyear').val();
				var vaction="new";
				edata="ordno="+vorderno+"&corno="+vcorno+"&orddate="+vorddate+"&prdmonth="+vprdmonth+"&prdyear="+vprdyear+"&action="+vaction;
				
				
			   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'orderreg.php',
						data: edata,
						success: function(data) {
							//alert(data);
							if(data.substr(0,5)=='Error'){
								
								corno.addClass( "ui-state-error" );
								$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
								return false;
							}else{
								//alert(data);
								//$('#result').html(data);
								window.location.href=data;
							}
								
						
								$( "#dialog-form").dialog( "close" );
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

	
	
	$( "#new" ).click(function() {
					$.ajax({
						type: 'GET',
						url: 'getordnoprd.php',
						success: function(data) {
						if(data.substr(0,5)=='Error'){
							}else{
								var rcv=data.split("||");
								var prdyear=rcv[0];
								var prdmonth=rcv[1];
								var ord=rcv[2];
								$('#orderno').val(ord);
								$('#prdmonth').val(prdmonth);
								$('#prdyear').val(prdyear);
								
							}
						}
					});
					   
					 $( ".validateTips" ).text('All form fields are required.').removeClass( "ui-state-highlight" );		   
					$( "#dialog-form" ).dialog( "open" );
				
		});
	
	
		
	});
</script>

    
    
    
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
        
        <div id="twocolLeft">
           	<?
			  	$_GET['current']="mainAwsAdd";
				include("navUser.php");
			  ?>
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
       
    <!-- ini tambahan untuk membaca Status approval AWS Order -->
    
         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                        <th  scope="col">&nbsp;</th>
                        <th width="90" scope="col"></th>
                        <th width="90" scope="col" align="right"></th>
            
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
         </table>

      <table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
      	<tr class="arial11grey" bgcolor="#AD1D36" >
            <th width="9%" height="30" scope="col">Order Date</th>
            <th width="20%" >A W S</th>
            <th width="18%" >Po Number</th>
            <th width="15%" >Denso Order No</th>
            <th width="10%" >Type</th>
            <th width="10%" >Status</th>
            <th width="18%" class="lastth">action</th>
      </tr>
      
    
     <?
	include "crypt.php";
		  require('db/conn.inc');
		  $page=trim($_GET['page']);
		  $per_page=5;
		  $num=5;
		  
		  if($page){ 
			$start = ($page - 1) * $per_page; 			
			}else{
				$start = 0;	
				$page=1;
			}
		  
		  
	$query="select * from orderhdr inner join cusmas on orderhdr.cusno=cusmas.Cusno where ordtype!='R' and dealer ='".$cusno. "' and Trflg!='1' and orderhdr.cusno!=orderhdr.dealer";
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	$query=$query . " order by ordflg, orderdate desc, Trflg";
	$query1=$query . " LIMIT $start, $per_page";
	$sql=mysqli_query($msqlcon,$query1);	
			while($hasil = mysqli_fetch_array ($sql)){
				$xcusno=$hasil['cusno'];
				$xcusnm=$hasil['Cusnm'];
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				$orderflg=$hasil['ordflg'];
				$dlvby=$hasil['DlvBy'];
				if($corno==""){
					$vcorno="-";
				}else{
					$vcorno=$corno;
				}
				$orderstatus=$hasil['ordtype'];
				$orderdate=$hasil['orderdate'];
				$otype=$hasil['ordtype'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				switch(trim($orderflg)){
					case "":
						$ordsts='Pending';
						break;
					case "1":
						$ordsts='Completed';
						break;
					case "U":
						$ordsts='uncompleted';
						break;	
						
				}
				
				switch(trim($otype)){
					case "C":
						$ordty='Campaign';
						break;
					case "U":
						$ordty='Urgent';
						break;
					case "S":
						$ordty='Special';
						break;	
						
				}
					
					
					
				
        		$trflg=$hasil['Trflg'];
			
			
		$urlprint= "<a href='prtorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
		
		echo "<tr class=\"arial11black\" align=\"center\" height=\"25\"><td>".$orddate."</td>";
		echo "<td>".$xcusno. " - ".$xcusnm."</td>" ;
		echo "<td>".$vcorno."</td>" ;
			
			echo "<td>".$ordno."</td>";
			echo "<td>".$ordty."</td><td class=\"arial11redbold\">".$ordsts."</td>";
			echo "<td class=\"lasttd\">";
			if($type!="v"){
			$edit=paramEncrypt("action=editAWSadd&ordno=$ordno&shpno=$xcusno&corno=$corno&orddate=$orderdate&ordtype=$type");	
			echo "<a href='orderreg.php?".$edit."' > <img src=\"images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			
			}
			echo $urlprint;
			echo "<td >";
			}
			
		  require('pager.php');	
		if($count>$per_page){
			echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  $fld="page";
		  pagingfld($query,$per_page,$num,$page,$fld);
		   echo "</div></td></tr>";
		}
		  ?>
 
 <tr>
    <td colspan="7" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= print</span>,<img src="images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span> </td>
 </tr> 
</table>

<table>
    
    <tr height="30"><td colspan="6" align="right" class="lasttd">
</table>
    
    
    
    
    
    
   
        
        
        
  
  

<div id="dialog-form" title="New Regular Order" style="display: none;">
				<p class="validateTips">All form fields are required.</p>


					<form>
						<table width="97%" border="0"   cellspacing="0" cellpadding="0">
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
    <td class="arial11redbold">Order Date</td>
    <td>:</td>
    <td><? 
		$orddt= date("d-m-Y");
		echo "<input name=\"orddate\" type=\"text\"  id=\"orddate\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"10\" size=\"10\" value=".$orddt.">";
			 
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
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td class="arial11redbold">Denso Order Number</td>
    <td>:</td>
    <td><input type="text" name="orderno" id="orderno" readonly="true" class="arial11blackbold"></td>
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
    <td class="arial11redbold">Order Periode</td>
    <td>:</td>
    <td colspan="2"  class="arial11blackbold"><span class="arial11redbold">Month :</span> 
    <input name="prdmonth" type="text"  id="prdmonth" class="arial11blackbold" readonly="true"  maxlength="4" size="4" ></td>
    <td colspan="3"  class="arial11blackbold"><span class="arial11redbold"> Year : </span>
      <input name="prdyear" type="text"  id="prdyear" class="arial11blackbold" readonly="true"  maxlength="5" size="5"></td>
    </tr>
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
    <td><span class="arial11redbold">Po Number</span></td>
    <td>:</td>
    <td  class="arial11blackbold"><input type="text" name="txtCorno" id="txtCorno" class="arial11blackbold" maxlength="20" size="20"></td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11blackbold">&nbsp;</td>
  </tr>
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
