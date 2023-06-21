<?php 
	session_start();
	require_once('./../../core/ctc_init.php');  //add by CTC

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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='m'){
			header("Location:../main.php");
		}
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
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

 <style type="text/css">
<!--
#example tbody {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
}
#example thead{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	
}
#example thead tr th{
	border-bottom-width: 2px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
	background-image: url(images/thbg.png);
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #B00;
}

#example tbody tr td table tr td{
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
		
}

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#0063DC;
	text-decoration: none;
	background-color: #E8E8E8;
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
-->
    </style>

<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
 <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">	
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

	<link rel="stylesheet" href="../css/demos.css">  
     <script type="text/javascript" charset="utf-8">
			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
$(document).ready(function() {
									   
		$( "#orderdatefrom" ).datepicker({
			changeMonth: true,
			changeYear: true
		});					   
		$( "#orderdateto" ).datepicker({
			changeMonth: true,
			changeYear: true
		});									   
									   
			var choose = $( "#OptChoose" ),
			field= $( "#OptField" ),
			desc= $( "#description" ),
			allFields = $( [] ).add(field).add(choose).add(desc),
			tips = $( ".validateTips" );
		
				$('#thnprd').change(function(){updatetbl('','','','','')});		
				
				$('#cmdGo').click(function(){
					
					updatetbl('','','','','');

				
				})
				
				$('#Partno').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="partno";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="partno";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				
				$('#Pono').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="Corno";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="Corno";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				
				$('#ConvExcel').click(function(){
					var fromd=$("#prdfrom").val();						   
					var tod=$("#prdto").val();
					var m=fromd.substr(4,2);
					var y=fromd.substr(0,4);
					var d=00;
					var txtDateFrom=y+m+d;
					var m=tod.substr(4,2);
					var y=tod.substr(0,4);
					var d=31;
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('Date to should be greater than date from!');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}

					var edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&search="+globalsearch+"&choose="+globalchoose+"&desc="+globaldesc;
					url= 'gettblorderXLSprd.php?'+edata;
					window.open(url);	
					
			  })
			  


			  function updatetbl(namafield, order, searchfield, choose, desc){

				var fromd=$( "#orderdatefrom" ).val();
					var tod=$( "#orderdateto" ).val();
					var m=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var d=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var m=tod.substr(0,2);
					var y=tod.substr(6,4);
					var d=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('Date to should be greater than date from!');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		

	
				edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&namafield="+namafield+"&sort="+order+"&search="+searchfield+"&choose="+choose+"&description="+desc;
						//alert(edata);
						$('#result1').empty().html('<div align="center"><img src="../images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'gettblproductsumall.php',
							data: edata,
							success: function(data) {
								var xdata=data.split("||");
								var qty=xdata[0];
								var amount=xdata[1];
								var amountsg=xdata[2];
								$('#txtGrandQty').val(qty);
								$('#txtGrandAmount').val(amount);
								$('#txtGrandAmountSD').val(amountsg);
								
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'gettblproductall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						
						
						$.ajax({
							type: 'GET',
							url: 'gettblproductpgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
					
				}
			
		$("#search").click(function(){
			$( "#dialog-form" ).dialog( "open" );							
		})
	
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height:200,
			width: 450,
			modal: true,
			buttons: {
				"Search": function() {
				var data;					
				var edata;
				var bValid;
				var vfield=$('#OptField').val();
				var vchoose=$('#OptChoose').val();
				var vdesc=$('#description').val();
				if(vfield==""){
					data="Please choose field to search";
					$('#optfield').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					bValid="";
					return false;
				}else{
					$('#optfield').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				if(vchoose==""){
					data="Please choose criteria to search";
					$('#optchoose').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					return false;
					bValid="";
				}else{
					$('#optchoose').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				if(vdesc==""){
					data="Please fill description to search";
					$('#description').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					return false;
					bValid="";
				}else{
					$('#description').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				
				
			   if ( bValid =="1") {
					globalsearch=vfield;
					globalchoose=vchoose;
					globaldesc=vdesc;	
					updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
									
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
									   
								   
		   });
			
			 function paging(x){
				
				var fromd=$( "#orderdatefrom" ).val();
					var tod=$( "#orderdateto" ).val();
					var m=fromd.substr(0,2);
					var y=fromd.substr(6,4);
					var d=fromd.substr(3,2);
					var txtDateFrom=y+m+d;
					var m=tod.substr(0,2);
					var y=tod.substr(6,4);
					var d=tod.substr(3,2);
					var txtDateTo=y+m+d;
					if(txtDateTo==''){
						alert('please fill date To!');
						return;
					}

					if(txtDateTo<txtDateFrom){
						alert('Date to should be greater than date from!');
						return;
					}	
					
					
					if(txtDateFrom==''){
						txtDateFrom='0';
					}		


				edata="datefrom="+txtDateFrom+"&dateto="+txtDateTo+"&page="+x+"&namafield="+globalfield+"&sort="+globalsort+"&search="+globalsearch+"&choose="+globalchoose+"&description="+globaldesc;

						$('#result1').empty().html('<div align="center"><img src="images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'gettblproductsumall.php',
							data: edata,
							success: function(data) {
								var xdata=data.split("||");
								var qty=xdata[0];
								var amount=xdata[1];
								var amountsg=xdata[2];
								$('#txtGrandQty').val(qty);
								$('#txtGrandAmount').val(amount);
								$('#txtGrandAmountSD').val(amountsg);
								
								}
						})
						
						
						$.ajax({
							type: 'GET',
							url: 'gettblproductall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'gettblproductpgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
						
				

			 }
				
	</script>

        
    
 
	</head>
	<body>
		<?php require_once('../../core/ctc_cookie.php');?>
		<?php ctc_get_logo(); ?>

		<div id="mainNav">
       
        
			  <ul>  
  				<li id="current"><a href="mainRFQ.php" target="_self">Marketing</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        
        <div id="twocolRight1">
    <b>Sales Plan vs Actual</b>
    <form method="post" name="frmseach">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td width="16%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="26%">&nbsp;</td>
    <td width="2%"></td>
    <td width="9%">&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="45%">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td>Customer (CUST3)</td>
    <td>:</td>
    <td><label>
      <select name="cust3" id="cust3" class="arial11black">
      	<option></option>
        <option value ="All"> All Customer </option>
        <?
		require('../db/conn.inc');
		$query1="select CUST3 from cusmas  where custype!='' and Owner_Comp='$comp' group by CUST3 order by CUST3 ";	
	    $sql=mysqli_query($msqlcon,$query1);	
		while($hasil = mysqli_fetch_array ($sql)){
		echo "<option value='".$hasil['CUST3']."'>".$hasil['CUST3']."</option>";
		
		}
		?>
      </select>
    </label></td>
    <td></td>
    <td>Report by</td>
    <td>&nbsp;</td>
    <td><label>
      <input type="radio" name="Optby" id="OptDate" value="DueDate" checked> By Due Date
      <input type="radio" name="Optby" id="OptDate" value="orderdate"> input date
    </label></td>
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
    <td>Periode From</td>
    <?
		$query1="select YYYYMM from slsplan where Owner_Comp='$comp'  group by YYYYMM order by YYYYMM ";	
	    $sql=mysqli_query($msqlcon,$query1);	
		while($hasil = mysqli_fetch_array ($sql)){
		$optval=$optval. "<option value='".$hasil['YYYYMM']."'>".$hasil['YYYYMM']."</option>";
		
		}
	
	?>
    
    <td>:</td>
    <td>
    
    
     <select name="prdfrom" id="prdfrom" class="arial11black">
      	<option></option>
        <?
		echo $optval;
		?>
     </select>   
    </td>
    <td></td>
    <td>Periode To</td>
    <td>:</td>
    <td>
    <table>
    <tr><td width="90%">
    <select name="prdto" id="prdto" class="arial11black">
      	<option></option>
        <?
		echo $optval;
		?>
     </select></td>
    <td width="10%">
    <input name="cmdGo" type="submit" class="arial11" id="cmdGo" value="Find "> 
    </td>
    </tr> 
   </table> 
  </tr>
        
   </table>   
   </form>  
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11" height="30">
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"></th>
    <th colspan="2" scope="col" align="right"><a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a></th>
  </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="17%" valign="middle" scope="col"></th>
    <th width="23%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
</table>



<table cellpadding="0" cellspacing="0" border="0"  width="97%">

	<tbody>
	
        
        
        
	</tbody>
	
</table>



<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead >
		
		<tr align="center" height="20" >
		  <th width="7%" rowspan="2">Company Code</th>
		  <th width="13%" rowspan="2">Product</th>
		  <th colspan="2">Plan</th> <th colspan="2">Actual</th>
		  <th width="11%" rowspan="2">Actual/Plan</th>
		  <th width="11%" rowspan="2">Qty Act - Plan</th>
		  <th width="11%" rowspan="2" class="lastth">Amount Act- Plan</th>
	  </tr>
		<tr align="center" height="20" >
		   <th width="12">Qty</th> <th width="15%">Amount (SGD)</th>
			<th width="12%">Qty</th><th width="15%">Amount(SGD</th>
		</tr>
	</thead>
	<tbody>
		<tr>
       <?
	  
	  
	  if(isset($_POST["cust3"]) && $_POST["cust3"]!="" && $_POST['prdfrom']!=""){
		if($_POST['prdto']!="" && $_POST['prdto']<$_POST['prdfrom']){
			echo "<tr><td colspan='9' align='center' class='arial21redbold'>Please choose Customer 3 </td></tr>";					  
		 }else{
			if($_POST['prdto']==""){
				$prdto=$_POST['prdfrom'].'31';
				$prdtoac=$_POST['prdfrom'];
			}else{
				$prdto=$_POST['prdto'].'31';
				$prdtoac=$_POST['prdto'];
			}
			$cust3=$_POST['cust3'];
			$prdfromac=$_POST['prdfrom'];
			$prdfrom=$_POST['prdfrom'].'00';
			$find=$_POST['Optby'];
			echo  "<span class='arial12Bold '> Seach Result   : </span><span class='arial11grey'> CUST3 = ". $cust3 . " and periode from =". $prdfrom . " and periode to=".$prdto ."</span>";
	  	if($cust3=='All'){
				$query="SELECT Bm008pr.Owner_Comp,Bm008pr.Product, sum(`qty`)as totalqty,sum(qty *bprice *SGPrice) as harga FROM `orderdtl` inner join bm008pr on orderdtl.partno=bm008pr.itnbr and orderdtl.Owner_Comp=bm008pr.Owner_Comp where $find >= $prdfrom and  $find <= $prdto and orderdtl.Owner_Comp='$comp' group by Bm008pr.product order by Bm008pr.product  ";
		}else{
				$query="SELECT Bm008pr.Owner_Comp,Bm008pr.Product, sum(`qty`)as totalqty,sum(qty *bprice *SGPrice) as harga FROM `orderdtl` inner join bm008pr on orderdtl.partno=bm008pr.itnbr and orderdtl.Owner_Comp=bm008pr.Owner_Comp where $find >= $prdfrom and  $find <= $prdto  and CUST3 ='$cust3' and orderdtl.Owner_Comp='$comp' group by Bm008pr.product order by Bm008pr.product  ";	
		}
	
		//echo $query;
	    $sql=mysqli_query($msqlcon,$query);	
		while($hasil = mysqli_fetch_array ($sql)){
			$owner_comp=$hasil['Owner_Comp'];
			$product=$hasil['Product'];
			$totalqty=$hasil['totalqty'];
			$totalamt=$hasil['harga'];
			$dspqty=number_format($totalqty,4);
			$dspamt=number_format($totalamt,4);
			if($cust3=='All'){
					$query2="SELECT PROD, SUM(QTY) as planqty, SUM(AMOUNT)as planamt from slsplan where YYYYMM >= $prdfromac and  YYYYMM <= $prdtoac   and PROD='$product' and Owner_Comp='$comp' group by PROD order by PROD";
			}else{
					$query2="SELECT PROD, SUM(QTY) as planqty, SUM(AMOUNT)as planamt from slsplan where YYYYMM >= $prdfromac and  YYYYMM <= $prdtoac  and CUST3 ='$cust3' and PROD='$product' and Owner_Comp='$comp' group by PROD order by PROD";		
			}
		
			//echo $query2;
			$sql2=mysqli_query($msqlcon,$query2);
			if($hasil2 = mysqli_fetch_array ($sql2)){
				$planqty=$hasil2['planqty'];
				$planamt=$hasil2['planamt'];
			}else{
				$planqty=0;
				$planqty=0;
				
			}
			if($planqty==0){
				$actvsplan='-';
				$qtyactplan='-';
				$amtactplan='-';
			}else{
				$actvsplan=$totalamt/$planamt;
				$dspactvsplan=number_format($actvsplan,6);
				$qtyactplan=$totalqty-$planqty;
				$amtactplan=$totalamt-$planamt;
			
			}
			echo '<tr><td>'.$owner_comp.'</td><td>'.$product.'</td><td align="right">'.$planqty.'</td><td align="right">'.$planamt.'</td><td align="right">'.$totalqty.'</td><td align="right">'.$dspamt.'</td><td align="right">'.$dspactvsplan.'</td><td align="right">'.$qtyactplan .'</td><td align="right">'.$amtactplan.'</td></tr>';
			
			
		  }		
		}
									  
	
					  
	  }else{
		if(isset($_POST["cust3"])) {  
	  	if($_POST["cust3"]==""){
			echo "<tr><td colspan='9' align='center' class='arial21redbold'>Please choose Customer 3 </td></tr>";
		}else{
			if($_POST["prdfrom"]=="") 
			echo "<tr><td colspan='9' align='center' class='arial21redbold'>Please fill Periode From </td></tr>";
			
		}
		}
	  
	  }
	   
	   ?>
        </tr>
        
	</tbody>
	
</table>


<div id="loading" style="display:none;" align="center"><img src="images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title="Search" >
				<p class="validateTips">Search Option</p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="partno" >Part Number</option>
        	<option value="ITDSC">Description</option>
            <option value="corno">PO Number</option>
        </select>
         <select name="choose" id="OptChoose" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="eq" >equals</option>
        	<option value="like">contains</option>
          </select>
			
        <input type="text" name="descrip" id="description"  class="arial11blackbold" maxlength="30" size="30">
     

                
          </div>   
    </div> 
                <p><br>
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

	</body>
</html>
