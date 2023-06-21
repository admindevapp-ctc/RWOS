<? session_start() ?>
<?
//if (session_is_registered('cusno'))
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
 <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">
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
	
	
	$( "#tglkirim" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	
		/**$('.add').click(function(){
			$('#myTable tr:last').clone(true).insertAfter('#myTable tr:last');
		});**/
	var vaction="";
	
	
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var res="";
	var corno = $( "#txtCorno" ),
			ordertype = $( "#OptOrderType"),
			shpno = $( "#txtShpNo"),
			allFields = $( [] ).add(corno).add(ordertype),
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
		
			width: 700,
			modal: true,
			buttons: {
				"save Order Header": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
				
					bValid = bValid && checkLength( corno, "Order Number", 2, 20 );
					//bValid = bValid && checkLength( orddate, "Order date", 10, 10 );
					bValid = bValid && checkVal( ordertype, "Order Type");
					//bValid = bValid && checkVal( shpno, "ship To number");
					if($( "#txtShpNo").val()==''){
						$( "#txtShpNo").addClass( "ui-state-error" );
						updateTips(   " ship to no should be selected!" );
						return false;
					}
						
					/** check part number **/
				
					
				var edata;
				
				var vcorno=corno.val();
				var vorderno=$('#orderno').val();
				var vordertype=ordertype.val();
				var vorddate=$('#orddate').val();
				var vshpno=$( "#txtShpNo").val();
			
				var vaction="newAdd";
				edata="ordno="+vorderno+"&corno="+vcorno+"&ordtype="+vordertype+"&orddate="+vorddate+"&shpno="+vshpno+"&action="+vaction;
				//alert(edata);
				
			   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'orderadd.php',
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
					$( "#dialog-form" ).dialog( "open" );
				
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
			  	$_GET['current']="mainAdd";
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
        
   </table>     
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  			<tr valign="middle" class="arial11">
    			<th  scope="col">&nbsp;</th>
    			<th width="90" scope="col">
    			<? 
	if($type!="v") echo "<a href=\"imregorderadd.php\"><img src=\"images/importxls.gif\" width=\"80\" height=\"20\"></a>";
	?>
				</th>
    			<th width="90" scope="col" align="right">
    <?
    if($type!="v") echo "<a href=\"#\" id=\"new\"><img src=\"images/newtran.png\" width=\"80\" height=\"20\"></a>";
	?>
				</th>
  			</tr>
  			<tr height="5"><td colspan="5"></td><tr>
</table>
        
        

   <table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
    <tr class="arial11grey" bgcolor="#AD1D36" >
     <?
	 
	 if($custype=="A"){
		$colnum=7;
		echo '<th width="12%" height="30">Status</th>';
        echo '<th width="9%" height="30" scope="col">Order Date</th>';
        echo '<th width="20%" >Po Number</th>';
        echo '<th width="20%" >Denso Order Number</th>';
        echo ' <th width="13%" >Ship To</th>';
    	echo '<th width="9%" >Type</th>';
        echo '<th width="18%" class="lastth">action</th>';
    	 
	}else{
		echo '<th width="9%" height="30" scope="col">Order Date</th>';
        echo '<th width="23%" >Po Number</th>';
        echo '<th width="23%" >Denso Order Number</th>';
        echo ' <th width="17%" >Ship To</th>';
    	echo '<th width="10%" >Type</th>';
        echo '<th width="18%" class="lastth">action</th>';
		$colnum=6;
	}

	echo "</tr>";
	      include "crypt.php";
		  require('db/conn.inc');
		  $page=trim($_GET['page']);
		  $per_page=10;
		  $num=10;
		  
		  if($page){ 
			$start = ($page - 1) * $per_page; 			
			}else{
				$start = 0;	
				$page=1;
			}
		  
		  
	$query="select * from orderhdr  where ordtype!='R' and Trflg!='1' and trim(CUST3) ='".$cusno. "'";
	$query=$query . " order by Trflg";
	$sql=mysqli_query($msqlcon,$query);
	$mcount = mysqli_num_rows($sql);
	$query1=$query . " LIMIT $start, $per_page";
	
		$sql=mysqli_query($msqlcon,$query1);		
			while($hasil = mysqli_fetch_array ($sql)){
				$ordno=$hasil['orderno'];
				$corno=$hasil['Corno'];
				if($corno=='')$corno= "-";
				$orderstatus=$hasil['ordtype'];
				$ordflg=$hasil['ordflg'];
				$orderdate=$hasil['orderdate'];
				$shpno=trim($hasil['cusno']);
				$periode=$hasil['periode'];
				$trflg=$hasil['Trflg'];
				$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
				switch($orderstatus){
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
				$querycus="select route from cusmas where cusno='$shpno'";
				$sqlcus=mysqli_query($msqlcon,$querycus);		
				if($hasilcus = mysqli_fetch_array ($sqlcus)){
					$route=$hasilcus['route'];
				}
				
				/**switch(strtoupper($dlvby)){
				case 'A':
					$delivered='By Air';
					break;
					
				case 'N':
					$delivered='Normal';
					break;	
				case 'H':
					$delivered='Hand Carry';
					break;	
					
				}**/
			
			//		echo $duedt;
			$urlprint= "<a href=\"prtaddorderpdf.php?ordno=$ordno&corno=$corno&shpno=$shpno\" target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			echo "<tr align=\"center\">";
			if($custype=="A"){
			switch(trim($ordflg)){
			case "":
				$sts="Pending";
				break;
			case "1":
				$sts="Completed";
				break;
			case "U":
				$sts="Uncompleted";
				break;
			}
			echo "<td class=\"arial11redbold\">".$sts."</td>";	
		}
		
			
			
			echo "<td>".$orddate."</td><td>".$corno."</td>" ;
			echo "<td>".$ordno."</td>";
			echo "<td>".$shpno."</td><td>".$ordsts."</td>";
			echo "<td class=\"lasttd\">";
			
			/** Pembatasan **/
			
			if($type!="v"){
				
				if(($custype=="A" & $ordflg=="")|| $custype!="A" || ($custype=="A" & $ordflg!="" &$route=='D')){
					$edit=paramEncrypt("action=editAddition&ordno=$ordno&shpno=$shpno&corno=$corno");
					$delete=paramEncrypt("action=deleteAddition&ordno=$ordno&shpno=$shpno&corno=$corno");
					if($ordflg!="R"){
					echo "<a href='orderreg.php?".$edit."' > <img src=\"images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
					}
					echo "<a href='orderreg.php?".$delete."' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
				}
				
				
			}
			
			if($custype=="A" & $ordflg!=""){
					if($route!='D'){
					$view=paramEncrypt("action=ViewAdd&ordno=$ordno&shpno=$shpno&corno=$corno&orddate=$orderdate&ordtype=$ordsts");
					echo "<a href='orderreg.php?".$view."' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
					}
				}
			echo $urlprint;
			echo "<td >";
			}
			
			
			
			
			
			
			/**********************************/
			
			
			
			
			
			
		if($mcount>$per_page){	
		  echo "<tr height=\"30\"><td colspan=\"6\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  require('pager.php');
		  paging($query,$per_page,$num,$page);
		  echo "</div></td></tr>";
		  
		}
 
 		echo "<tr>";
    	echo '<td colspan="'.$colnum.'" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= Print</span><img src="images/edit.png" width="20" height="20"><span class="arial11redbold" >= edit</span>, <img src="images/delete.png" width="20" height="20"><span class="arial11redbold" >= delete</span></td>';
	?>
    </tr> 
</table>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <div class="demo">
        
        
        
   	  <div id="dialog-form" title="New Additional Order" style="display: none;" >
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
    <td><span class="arial11greybold">Denso Order Number</span></td>
    <td>:</td>
    <td><input type="text" name="orderno" id="orderno" readonly="true" class="arial11greybold"></td>
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
    <td class="arial11redbold">Po Number</td>
    <td>:</td>
    <td><input type="text" name="txtCorno" id="txtCorno" class="arial11blackbold" maxlength="20" size="20"></td>
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
    <td  class="arial11blackbold"><select name="OptOrderType" id="OptOrderType" class="arial11blackbold" style="width: 100px" >
      <option value="" ></option>
      <option value="C" >Campaign</option>
      <option value="U"> Urgent</option>
      <option value="S">Special</option>
    </select></td>
    <td  class="arial11blackbold">&nbsp;</td>
    <td  class="arial11redbold">&nbsp;</td>
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
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><span class="arial11redbold">Ship To</span></td>
    <td>:</td>
    <td colspan="5"><?
	$qryshp="SELECT cusmas.Cusno, cusrem.curcd, cusrem.remark FROM `cusmas` INNER JOIN cusrem ON cusmas.cusno = cusrem.cusno where  trim(cust3) ='".$cusno. "' order by cusno" ;
	//echo $qryshp;
	$sqlshp=mysqli_query($msqlcon,$qryshp);
	$mcount = mysqli_num_rows($sqlshp);
	//echo $mcount;
	if($mcount==1){
		if($hasil = mysqli_fetch_array ($sqlshp)){
			$vcusno=$hasil['Cusno'];
		}
		//echo $vcusno;
		echo "<input name=\"txtShpNo\" type=\"text\"  id=\"txtShpNo\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"20\" size=\"20\" value=".$vcusno.">";
			
	}else{
		echo '<select name="txtShpNo" id="txtShpNo" " style="width: 300px">';
		echo '<option value="" ></option>';
		while($hasil = mysqli_fetch_array ($sqlshp)){
			$vcusno=$hasil['Cusno'];
			$vremark=$hasil['remark'];
			$vcurcd=$hasil['curcd'];
			$gabung=$vcusno . ' - '. $vremark . '  (' .$vcurcd.')' ;
	       	echo '<option value='.$vcusno .'>' .$gabung.'</option>';
          
      
		}
		//echo '<div id="combo1" class="combo"></div>';
	}
		  echo '</select>';
	?></td>
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
