<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

//if (session_is_registered('cusno'))
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
		$comp = ctc_get_session_comp();
		if($type!='m')header("Location:../main.php");
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
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

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
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.ui.effect.js"></script>
<link rel="stylesheet" href="../css/demos.css">   
<script type="text/javascript">

$(function() {
	
	//dialog message
	var vaction="";
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
			var res="";
			var partno = $( "#txtpartno" ),
			desc = $( "#txtdescription" ),
			allFields = $( [] ).add(partno),
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
			
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				"save RFQ": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					bValid = bValid && checkLength( partno, "Part nunber Number", 10, 15 );
				
					
				var edata;
				var vpartno=partno.val();
				var vdesc=$('#txtdescription').val();
				var vorddate=$('#orddate').val();
				var vaction="new";
				edata="partno="+vpartno+"&desc="+vdesc+"&orddate="+vorddate+"&action="+vaction;
				
				
			   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'RFQadd.php',
						data: edata,
						success: function(data) {
							//alert(data);
							if(data.substr(0,5)=='Error'){
								
								corno.addClass( "ui-state-error" );
								$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
								return false;
							}else{
								alert(data);
								//$('#result').html(data);
								//window.location.href=data;
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
					$( ".validateTips" ).text('All form fields are required.').removeClass( "ui-state-highlight" );		   
					//$( "#dialog-form" ).dialog( "open" );
				
		});
	
	
	// excel convert
	$('#ConvExcel').click(function(){
					//alert('test');			   
					url= 'rfqXLS.php';
				   window.open(url);	
					
	 })
	
	
		
	});
</script>

    
    
    
</head>
<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->

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
		  		$_GET['current']="mainRFQ";

			  	include("navAdm.php");
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
    <td></td>
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
  <?
  	if(isset($_GET["status"])){
		$srcstatus=$_GET["status"];
		$sortby=$_GET["sortby"];
		$srcpart=$_GET["srcpart"];
		$srccust3=$_GET["srccust3"];
		 switch($sortby){
		  case 'PRTNO':
		  	$selprt='selected';
		  break;
		  case 'CUST3':
		  	$selcust='selected';
		  break;
		  case 'RFQNO':
		 	$selrfq='selected';
		  break;
		
	  }
	   switch($srcstatus){
		  case 'P':
		  	$spending='selected';
		  break;
		  case 'C':
		  	$sclose='selected';
		  break;
		 
		
	  }
	  
	}
  ?>
  
  
   <form name="form1" method="get" action="mainRFQ.php">
  <tr class="arial11blackbold">
 
    <td colspan="3">
      <label> Sort by   : 
        <select name="sortby" id="sortby" class="arial11black"  style="width: 200px">
          <option value=""></option>
         <?
          echo '<option value="PRTNO"'. $selprt. '>Part Number</option>';
          echo '<option value="CUST3"'. $selcust. '>Customer </option>';
          echo '<option value="RFQNO"'. $selrfq.'>RFQ Number</option>';
          
		 ?>
         </select> 
      </label>    </td>
    <td></td>
    <td colspan="3">
      <label> Filter :
         <select name="status" id="select" class="arial11black"  style="width: 200px">
           <option value=""></option>
           <?
          echo '<option value="P"'. $spending.'>Pending</option>';
          echo '<option value="C"'. $sclose.'>Close</option>';
           ?>
		   </select>
        </label>
      <label>
        <input type="submit" name="button" id="button" value="Go" class="arial11">
      </label>
    </td>
    
    </tr>
  <tr class="arial11blackbold">
    <td colspan="3">&nbsp;</td>
    <td></td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td colspan="3">Filter Customer</td>
    <td></td>
    <td colspan="3">Search by Part Number</td>
  </tr>
  <tr class="arial11blackbold">
    <td colspan="3">
     <label>
     <?
      echo '<input type="text" name="srccust3" value="'.$srccust3.'" class="arial11black"  style="width: 200px">';
    ?>
    </label>
    </td>
    <td></td>
    <td colspan="3">
    <label>
      <?
      echo '<input type="text" name="srcpart" value="'. $srcpart. '" class="arial11black"  style="width: 200px">';
	  ?>

    <input type="submit" name="button" id="button" value="Go" class="arial11">  </label>
    </td>
  </tr>
    </form>   
    
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr valign="middle" class="arial11">
    		<th  scope="col">&nbsp;</th>
    		<th width="90" scope="col"></th>
    		<th width="90" scope="col" align="right"></th>

	</tr>
	<tr height="5"><td colspan="5"></td><tr>
</table>

        
   </table>     
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="middle" class="arial11">
    			<th  scope="col">&nbsp;</th>
    			<th width="90" scope="col"></th>
    			<th width="90" scope="col" align="right">
    <a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a>
				</th>
  			</tr>
  			<tr height="5"><td colspan="5"></td><tr>
</table>


<table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
	 <tr class="arial11white" bgcolor="#AD1D36" >
<?

	//	echo '<th width="9%" height="30" scope="col">Inquiry Date</th>';
		echo '<th width="6%" height="30" >Company Code</th>';
		echo '<th width="10%" height="30" >Customer</th>';
        echo '<th width="10%" height="30" >RFQ Number</th>';
        echo ' <th width="10%" >RFQ Date</th>';
    	echo '<th width="15%" >Part Number</th>';
		echo '<th width="10%" >Reply</th>';
		echo '<th width="15%" >Denso Part No</th>';
		 echo '<th width="15%" class="lastth">Answer</th>';
		echo '<th width="5%" >Status</th>';
        echo '<th width="5%" class="lastth">action</th>';
		$colnum=10;

	echo "</tr>";
	require('../db/conn.inc');
    include "../crypt.php";
		  
		  $mpage=trim($_GET['mpage']);
		  $mper_page=10;
		  $num=5;
		  
		  if($mpage){ 
			$start = ($mpage - 1) * $mper_page; 			
			}else{
				$start = 0;	
				$mpage=1;
			}
	$criteria=" where Owner_Comp='$comp' and trim(STSMKT)=''";	  // edit by CTC
	if(isset($_GET["status"])){
		$srcstatus=$_GET["status"];
		$srcpart=$_GET["srcpart"];
		$srccust3=$_GET["srccust3"];
		
		
		if($srcstatus!=""){
			$criteria=$criteria . " and  STS='$srcstatus'";
			if($srcpart!=""){
				$criteria=$criteria. " and PRTNO like '" . $srcpart . "%'";		
			}
			if($srccust3!=""){
				$criteria=$criteria. " and CUST3 like '" . $srccust3 . "%'";		
			}
			
		} else{
			if($srcpart!=""){
				$criteria=$criteria." and  PRTNO like '" . $srcpart . "%'";		
				if($srccust3!=""){
					$criteria=$criteria. " and CUST3 like '" . $srccust3 . "%'";		
				}			
			
			}else{
				if($srccust3!=""){
					$criteria=$criteria. " and  CUST3 like '" . $srccust3 . "%'";		
				}else{
				//	$criteria=$criteria;
				}
			}
		}
	} else {
			//$criteria="";
	}
	
		switch(trim($srcstatus)){
				
			case "P":
				$xsts="Pending";
				break;
			case "C":
				$xsts="Closed";
				break;
			case "U":
				$xsts="Uncompleted";
				break;
			
			default :
				$xsts="All Status";
				
			}
	
	if(isset($_GET["sortby"])){
		$sortby=$_GET["sortby"];
		if($sortby==""){
			$vsortby=" order by RFQNo Desc, RFQDT Desc, CUST3";
		} else{
			$vsortby=" order by " . $sortby . ",RFQDT Desc";
		}
	}else{
		
		$vsortby=" order by RFQNo Desc, RFQDT Desc, CUST3";
	}

	if($srcstatus!="" || $sortby!=""){
		if( $sortby!=""){
			echo "<b> Sort by   :</b>". $sortby ;
			echo " and  <b>search status  =</b>". $xsts;
		}else{
			echo "<b>search status  = </b>".  $xsts;
		}
	}
	$fld='sortby='.$sortby.'&status='.$srcstatus.'&srccust3='.$srccust3.'&srcpart='.$srcpart;
    //echo $fld;
	//echo $criteria;
	$query="select * from Rfqdtl" ; 
	$query=$query . $criteria;
	//$query=$query . " order by RFQNo Desc, RFQDT Desc, CUST3";
	$query=$query .$vsortby;
 //echo $query;
	$sql=mysqli_query($msqlcon,$query);
	$mcount = mysqli_num_rows($sql);
	$query1=$query . " LIMIT $start, $mper_page";
	
		$sql=mysqli_query($msqlcon,$query1);		
			while($hasil = mysqli_fetch_array ($sql)){
				$ownerComp=$hasil['Owner_Comp'];
				$cust3=$hasil['CUST3'];
				$rfqno=$hasil['RFQNO'];
				$RFQDT=$hasil['RFQDT'];
				$periode=$hasil['RFQYM'];
				$orddate=substr($RFQDT,-2)."/".substr($RFQDT,4,2)."/".substr($RFQDT,0,4);
				$status=$hasil['STS'];
				$prtno=$hasil['PRTNO']; 
				
				$desc=$hasil['ITDSC']; 	
				
				$rpldt=$hasil['RPLDT'];
				if($rpldt=='')$rpldt='-';
				$remark=$hasil['DIASRMK'];
				if($remark=='')$remark='-';
				$answer=$hasil['DIASANS'];
				if($answer=='')$answer='-';
				
		$urlprint= "<a href='prtorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
		
		echo "<tr class=\"arial11black\" align=\"center\" height=\"25\">";
		
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
			echo "<td>".$ownerComp."</td>";
			echo "<td>".$cust3."</td>";
			echo "<td>".$rfqno."</td>";
			echo "<td>".$orddate."</td>";
			echo "<td>".$prtno."</td>";
			echo "<td>".$rpldt."</td>";
			echo "<td>".$remark."</td>";
			echo "<td>".$answer."</td>";
			echo "<td>".$sts."</td>";		
			echo "<td class=\"lasttd\">";
			if($type!="v"){
				
				if(($custype=="A" & $ordflg=="")|| $custype!="A" || ($custype=="A" & $ordflg!=""&$route=='D')){
					$edit=paramEncrypt("action=edit&rfqno=$rfqno&custi3=$cust3&prtno=$prtno&rfqdt=$RFQDT&mpage=$mpage&sortby=$sortby&status=$srcstatus&srccust3=$srccust3&srcpart=$srcpart");
					$move=paramEncrypt("action=move&rfqno=$rfqno&custi3=$cust3&prtno=$prtno&rfqdt=$RFQDT&mpage=$mpage&sortby=$sortby&status=$srcstatus&srccust3=$srccust3&srcpart=$srcpart");
					$delete=paramEncrypt("action=delete&rfqno=$rfqno&custi3=$cust3");
					if($status=='C'){	
						echo "<a href='RFQproc.php?".$move."' > <img src=\"../images/trash.png\" width=\"20\" height=\"20\" border=\"0\"></a>";	
					}
					echo "<a href='RFQHdr.php?".$edit."' > <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
					
				}
				
				
			}
			
			
				
				
			//echo $urlprint;
			echo "<td >";
			}
		include("pager.php");
		if($mcount>$mper_page){
		  echo "<tr height=\"30\"><td colspan=\"".$colnum."\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  //$fld="mpage";
		  pagingfldx($query,$mper_page,$num,$mpage,$fld);
		  echo "</div></td></tr>";
		}
		 
 	echo "<tr>";
    echo '<td colspan="'.$colnum.'" class="lasttd" align="right"><img src="../images/trash.png" width="20" height="20"><span class="arial11redbold"> = move to history</span><img src="../images/tipsy.gif" width="20" height="20"><img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span></td>';
	?>
    </tr> 
</table>


<div id="dialog-form" title="New RFQ" style="display: none;"  >
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
    <td class="arial11redbold">Inquiry Date</td>
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
    <td><span class="arial11redbold">Part Number </span></td>
    <td>:</td>
    <td  class="arial11blackbold"><input type="text" name="txtpartno" id="txtpartno" class="arial11blackbold" maxlength="15" size="20"></td>
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
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td><span class="arial11redbold">Description</span></td>
    <td>:</td>
    <td colspan="5"  class="arial11blackbold"><input type="text" name="txtdescription" id="txtdescription" class="arial11blackbold" maxlength="30" size="40"></td>
    </tr>
  
        </table>
</form>
    			
                
          </div>



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

	</body>
</html>
