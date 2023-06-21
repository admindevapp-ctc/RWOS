<? session_start() ?>
<?
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
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
    <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">

	<script src="lib/jquery-1.4.2.min.js"></script>
    <script src="lib/jquery.ui.core.js"></script>
	<script src="lib/jquery.ui.widget.js"></script>
	<script src="lib/jquery.ui.mouse.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.draggable.js"></script>
	<script src="lib/jquery.ui.position.js"></script>
	<script src="lib/jquery.ui.resizable.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.dialog.js"></script>
	<script src="lib/jquery.ui.effect.js"></script>
<link rel="stylesheet" href="css/demos.css">   
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
			  	$_GET['current']="RFQHis";
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
    			<th width="90" scope="col">
                <? 
	if($type!="v") echo "<a href=\"imRFQ.php\"><img src=\"images/importxls.gif\" width=\"80\" height=\"20\"></a>";
	?>
                </th>
    			<th width="90" scope="col" align="right">
    <?
    if($type!="v") echo "<a href=\"RFQproc.php\" id=\"new\"><img src=\"images/newtran.png\" width=\"80\" height=\"20\"></a>";
	?>
				</th>
  			</tr>
  			<tr height="5"><td colspan="5"></td><tr>
</table>


<table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
	 <tr class="arial11grey" bgcolor="#AD1D36" >
<?
	//echo 'user type =' . $type."<br>"; 
	//echo 'customer type ='.$custype;

	//	echo '<th width="9%" height="30" scope="col">Inquiry Date</th>';
        echo '<th width="30%" height="30" >RFQ Number</th>';
   
        echo ' <th width="22%" >RFQ Date</th>';
		echo '<th width="20%" >Updated By DIAS</th>';
    	echo '<th width="10%" >Status</th>';
        echo '<th width="18%" class="lastth">action</th>';
		$colnum=5;

	echo "</tr>";
	require('db/conn.inc');
     include "crypt.php";
		  
		  $mpage=trim($_GET['mpage']);
		  $mper_page=10;
		  $num=10;
		  
		  if($mpage){ 
			$start = ($mpage - 1) * $mper_page; 			
			}else{
				$start = 0;	
				$mpage=1;
			}
		  
		  
	$query="select * from Rfqhdr  where trim(CUST3) ='".$cusno. "' and STS='X'" ;
	$query=$query . " order by RFQDT Desc, RFQNo Desc ";
	$sql=mysqli_query($msqlcon,$query);
	$mcount = mysqli_num_rows($sql);
	$query1=$query . " LIMIT $start, $mper_page";
	
		$sql=mysqli_query($msqlcon,$query1);		
			while($hasil = mysqli_fetch_array ($sql)){
				$rfqno=$hasil['RFQNO'];
				$RFQDT=$hasil['RFQDT'];
				$rfqupddt=$hasil['RFQUPDDT'];
				$periode=$hasil['RFQYM'];
				$orddate=substr($RFQDT,-2)."/".substr($RFQDT,4,2)."/".substr($RFQDT,0,4);
				if($rfqupddt!="" ){
					$rfqupd=substr($rfqupddt,-2)."/".substr($rfqupddt,4,2)."/".substr($rfqupddt,0,4);
				}else{
					$rfqupd="-";
				}
				$status=$hasil['STS'];
				$query1="select count(*) as jmlclose from Rfqdtl  where trim(CUST3) ='".$cusno. "' and RFQNo='".$rfqno."' and STS='C'" ;
				$sql1=mysqli_query($msqlcon,$query1);
				if($hasil1 = mysqli_fetch_array ($sql1)){
					$jmlclose=$hasil1['jmlclose'];
				}
		$urlprint= "<a href='prtorderpdf.php?ordno=".$ordno."' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
		
		echo "<tr class=\"arial11black\" align=\"center\" height=\"25\">";
		
			switch(trim($status)){
			case "P":
				$sts="Pending";
				break;
			case "C":
				$sts="Completed";
				break;
			case "X":
				$sts="Completed";
				break;	
			case "U":
				$sts="Uncompleted";
				break;
			}
			echo "<td>".$rfqno."</td>";
			echo "<td>".$orddate."</td><td>".$rfqupd."</td><td>".$sts."</td>";
			echo "<td class=\"lasttd\">";
			if($type!="v"){
				
			/**	if( $custype!="A" || ($custype=="A" & $ordflg!=""&$route=='D')){**/
					$edit=paramEncrypt("action=edit&rfqno=$rfqno&history=Y");
					
					echo "<a href='RFQproc.php?".$edit."' > <img src=\"images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
					
					
				//}
				
				
			}
			
			/**if($custype=="A" & $ordflg!=""){
					if($route!='D'){
					$view=paramEncrypt("action=edit&rfqno=$rfqno");
					echo "<a href='RFQproc.php?".$view."' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";

					}
				}
				
				if($type=="v" && $custype!="A"){
						$edit=paramEncrypt("action=edit&rfqno=$rfqno");
					echo "<a href='RFQproc.php?".$view."' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
			}**/
			//echo $urlprint;
			echo "<td >";
			}
		include("pager.php");
		if($mcount>$mper_page){
		  echo "<tr height=\"30\"><td colspan=\"".$colnum."\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  $fld="mpage";
		  pagingfld($query,$mper_page,$num,$mpage,$fld);
		  echo "</div></td></tr>";
		}
		 
 	echo "<tr>";
    echo '<td colspan="'.$colnum.'" class="lasttd" align="right"><img src="images/edit.png" width="20" height="20"><span class="arial11redbold"> = view</span></td>';
	?>
    </tr> 
</table>


<div id="dialog-form" title="New RFQ"  style="display: none;">
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
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>

	</body>
</html>
