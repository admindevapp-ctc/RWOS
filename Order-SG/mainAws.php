<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

include('chklogin.php');
include "crypt.php";

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

	<!-- start edit aws at Dec 2022 -->
	<style >
.blue-button
{
	background-image: linear-gradient(#42A1EC, #0070C9);
  border: 1px solid #0077CC;
  border-radius: 4px;
  box-sizing: border-box;
  color: #FFFFFF;
  cursor: pointer;

}

</style>
<!-- end edit aws at Dec 2022 -->
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
					bValid = bValid && checkLength( corno, "Order Number", 2, 10 );
				
					
				var edata;
				var vcorno=corno.val();
				var vorderno=$('#orderno').val();
				var vorddate=$('#orddate').val();
				var vprdmonth=$('#prdmonth').val();
				var vprdyear=$('#prdyear').val();
				var vaction="new";
				edata="ordno="+vorderno+"&corno="+vcorno+"&orddate="+vorddate+"&prdmonth="+vprdmonth+"&prdyear="+vprdyear+"&action="+vaction;
				
				//alert(edata);
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
<body>
		<?php
		// require('getRequestDueDate.php');
		// $are_duedate = AWSgetRequestedDueDateApp1_art();
		// echo 'Art DueDate Test : ';
		// echo $are_duedate;
		?>
		<?php ctc_get_logo() ?> <!-- add by CTC -->

		<div id="mainNav">
        
			<?
				$_GET['selection']="main";
				include("navhoriz.php");
			
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<?
			  	$_GET['current']="mainAws";
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
  <tr class="arial11white" bgcolor="#AD1D36" >
    <th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0040"); ?><!--Order Date--></th>
    <th width="10%" ><?php echo get_lng($_SESSION["lng"], "L0452"); ?><!--Supplier--></th>
    <th width="20%" ><?php echo get_lng($_SESSION["lng"], "L0570"); ?><!--AWS--></th>
    <th width="10%" ><?php echo get_lng($_SESSION["lng"], "L0053"); ?><!--PO number--></th>
    <th width="15%" ><?php echo get_lng($_SESSION["lng"], "L0452"); ?><!--denso order no--></th>
    <th width="10%" ><?php echo get_lng($_SESSION["lng"], "L0011"); ?><!--Ship to--></th>
    <th width="10%" ><?php echo get_lng($_SESSION["lng"], "L0559"); ?><!--Status--></th>
    <th width="15%" class="lastth">Action</th>
  </tr>
  
    
     <?
	
		  require('db/conn.inc');
		  $page=trim($_GET['page']);
		  $per_page=10;
		  $num=5;
		  
		  if($page){ 
			$start = ($page - 1) * $per_page; 			
			}else{
				$start = 0;	
				$page=1;
			}
		
	// start edit aws at Dec 2022
	//$query="select * from orderhdr inner join cusmas on orderhdr.cusno=cusmas.Cusno where ordtype='R' and dealer ='".$cusno. "' and Trflg!='1' and orderhdr.cusno!=orderhdr.dealer and orderhdr.Owner_Comp='$owner_comp'";
	$query="
	select a.*, cusmas.Cusnm
	from (
	select 'awsorderhdr' as 'table', Owner_Comp,orderdate, 'DENSO' as supno ,'DENSO' as supnm ,awsorderhdr.CUST3,awsorderhdr.cusno,corno, orderno,ordtype,ordflg,shipto
	from awsorderhdr 
	where dealer ='".$cusno. "'  ";
	
		$query .=" and ordflg ='' ";
	
	$query .= "  and awsorderhdr.cusno!=awsorderhdr.dealer and awsorderhdr.Owner_Comp = '".$owner_comp. "'
	UNION
	select 'supawsorderhdr' as 'table', supawsorderhdr.Owner_Comp,orderdate, supawsorderhdr.supno as supno ,supnm,supawsorderhdr.CUST3,supawsorderhdr.cusno,corno, orderno,ordtype,ordflg,shipto
	from supawsorderhdr 
	join supmas on supawsorderhdr.supno = supmas.supno and supawsorderhdr.Owner_Comp = supmas.Owner_Comp
	where dealer ='".$cusno. "' ";
	
		 $query .=" and ordflg ='' ";
	
	$query .= " and supawsorderhdr.cusno!=supawsorderhdr.dealer and supawsorderhdr.Owner_Comp ='".$owner_comp. "'
	) a
	inner join cusmas on a.cusno=cusmas.Cusno AND a.Owner_Comp=cusmas.Owner_Comp
	";
	
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	$query=$query . " order by orderdate desc";
	$query1=$query . " LIMIT $start, $per_page";
	// echo $query1;
	$sql=mysqli_query($msqlcon,$query1);	
			while($hasil = mysqli_fetch_array ($sql)){
				$table=$hasil['table'];
				$xcusno=$hasil['CUST3'];
				$xcusnm=$hasil['Cusnm'];
				$xsupno=$hasil['supno'];
				$xsupnm=$hasil['supnm'];
				$ordno=$hasil['orderno'];
				$corno=$hasil['corno'];
				$orderflg=$hasil['ordflg'];
				$shpto=$hasil['shipto'];
				if($corno==""){
					$vcorno="-";
				}else{
					$vcorno=$corno;
				}
				$orderstatus=$hasil['ordtype'];
				$orderdate=$hasil['orderdate'];
				$shpno=$hasil['cusno'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				switch(trim($orderflg)){
					case "":
						$ordsts= get_lng($_SESSION["lng"], "L0566");
						break;
					case "1":
						$ordsts='Completed';
						break;
					case "2":
						$ordsts='Reject';
						break;
					case "U":
						$ordsts='Urgent';
						break;	
					default:
						$ordsts='';
						break;	
						
				}
        		$trflg=$hasil['Trflg'];
			$text_cus_name= $xcusno."-".$xcusnm;
			$title_cus_name= '';
 			if(strlen($xcusno."-".$xcusnm) > 25){
				$title_cus_name = $xcusno."-".$xcusnm;
				$text_cus_name = substr($xcusno."-".$xcusnm,0,25) ."...";
			}else{
				$title_cus_name = '';
			}
			echo "<tr class=\"arial11black\" align=\"center\" height=\"25\"><td>".$orddate."</td>";
			echo "<td>".$xsupnm ."</td>" ;
			echo '<td title="' . $xcusnm . '">' . $text_cus_name . '</td>';
			echo "<td>".$corno."<input type='hidden' id='ordertable' value='".$table."'/></td>" ;
			echo "<td>".$ordno."</td>";
			echo "<td>".$shpto."</td><td class=\"arial11redbold\">".$ordsts."</td>";
			echo "<td class=\"lasttd\">";
			echo "<div style=\"display:flex; justify-content:center;\">";
			
			if($type!="v"){
				$edit=paramEncrypt("action=editAWS&ordno=$ordno&shpno=$shpno&corno=$corno&orddate=$orderdate&table=$table&supno=$xsupno&shipto2=$shpto");	
				//echo "<a href='orderreg.php?".$edit."' > <img src=\"images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
				echo "<a href='orderreg.php?".$edit."' ><input type=\"button\" style=\"margin:5px;\" class=\"blue-button\" value=\"confirm\" ><span class=\"arial11redbold\"></a>";
			}
			if($table == "awsorderhdr"){
				$urlprint= "<a href='aws_prtorderpdf.php?ordno=$ordno&shpno=$shpno&corno=$corno&orddate=$orderdate&table=$table' target=\"new\" style=\"padding-top: 3px;\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			}
			else{
				$urlprint= "<a href='aws_sup_prtorderpdf.php?ordno=$ordno&cusno1=$xcusno&supno=$xsupno&corno=$corno&orddate=$orderdate&table=$table' target=\"new\" style=\"padding-top: 3px;\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			}
			echo $urlprint;
			echo "</div>";
			echo "<td >";
			}
			
			require('pager.php');	
		if($count>$per_page){
			echo "<tr height=\"30\"><td colspan=\"8\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
			$fld="page";
			paging($query,$per_page,$num,$page,$fld);
		   echo "</div></td></tr>";
		}

	// end edit aws at Dec 2022
		  ?>
 
 <tr>
	<!-- start edit aws -->
    <td colspan="8" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= print</span>
	<!--,<img src="images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span>
	,<input type="button" class="blue-button" value="confirm"><span class="arial11redbold">=Confirm</span> --></td>
	<!-- end edit aws -->
 </tr> 
</table>

<table>
    
    <tr height="30"><td colspan="8" align="right" class="lasttd">
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
    <td class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0040"); ?><!--Order Date--></td>
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
<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
	
  </div>
</div>
          </div>

	</body>
</html>
