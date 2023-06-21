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
		$erp = $_SESSION['erp'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
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
	<link rel="stylesheet" href="css/demos.css">   
	<link rel="stylesheet" href="css/ui/jquery.ui.button.css">   
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
		.ui-button_new{
			height: 29px;
			width: 125px;
			background-color: #990033;
			color :white;
			font-size: 11px;
			border-width: 0px !important;
		}
		.ui-button_new:hover{
			  box-shadow: 5px 5px 10px #888888;
		}
		.ui-button_new:disabled{
			background-color: #990033b5;
		}
		.ui-button_approve{
			height: 30px;
			width: 100px;
			color :white;
			margin: 0 10px;
			font-size: 11px;
			border-width: 0px !important;
		}
		.ui-button_approve:disabled{
			box-shadow: 0px 0px 0px #888888;
			opacity: 0.6;
		}
		.ui-button_approve:disabled:hover{
			background-color: #990033b5;
			box-shadow: 0px 0px 0px #888888;
		}
		.ui-button_approve:hover{
			background-color: #990033b5;
			  box-shadow: 5px 5px 10px #888888;

		}
		.vertical-center {
            margin: 0;
            position: absolute;
            top: 24px;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
	</style>
    
     

</head>
<body>   

<?
	include "crypt.php";
	$var = decode($_SERVER['REQUEST_URI']);
	$xordno=trim($var['ordno']);
	$vshpno=trim($var['shpno']);
	$vcdate=trim($var['orddate']);
	$vcorno=trim($var['corno']);
	$action=trim($var['action']);
	$table =trim($var['table']);
	$supno =trim($var['supno']);
	$shipto2 =trim($var['shipto']);
	require('db/conn.inc');
	//echo $table;

	$query="select cusmas.CUST3, cusmas.Cusnm, '' curcd, '' remark from cusmas where trim(cusmas.cusno) ='".$vshpno. "' and cusmas.Owner_Comp='$comp'";
	// echo $query;
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
	 	$vcusnm=$hasil['Cusnm'];  
		$vcust3=$hasil['CUST3'];  
		$vremark=$hasil['remark'];
		$vcurcd=$hasil['curcd'];
		$alamat=$vremark . '  (' .$vcurcd.')' ;
	}
	//echo $query;
	
	
	$query_aws_data = "
		SELECT * from awscusmas where 1 AND cusno2 = '$vshpno' and Owner_Comp = '$comp' AND ship_to_cd2 = '$shipto2' limit 1
	";
	//echo $query_aws_data ;
	$sql_aws_data=mysqli_query($msqlcon,$query_aws_data);		
	if($row_aws = mysqli_fetch_array ($sql_aws_data)){
	 	$rcusno1=$row_aws['cusno1'];  
	 	$ship_to_cd1 =$row_aws['ship_to_cd1'];
		$rcusno2=$row_aws['cusno2'];  
	 	$ship_to_cd2 =$row_aws['ship_to_cd2'];  
	 	$ship_to_adrs1 =$row_aws['ship_to_adrs1'];  
	 	$ship_to_adrs2 =$row_aws['ship_to_adrs2'];  
	 	$ship_to_adrs3 =$row_aws['ship_to_adrs3'];  
		
		$cus2_addr = ''.$ship_to_adrs1.' <br> '.$ship_to_adrs2.' <br> '.$ship_to_adrs3.' ';
	}
	
	$txtcusno="<input name=\"txtcusno\" type=\"text\"  id=\"txtcusno\" class=\"arial11blackbold\"  maxlength=\"10\" size=\"10\" readonly=\"true\" value=".$vcust3.">";
  	$inputshpno="<input type=\"hidden\" name=\"shpno\" type=\"text\"  id=\"shpno\" class=\"arial11blackbold\"  value=".$vshpno.">";
	   
	echo "<input type=\"hidden\" name=\"action\" id=\"action\" value=".$action.">";
	echo "<input type=\"hidden\" name=\"table\" id=\"table\" value=".$table.">";
	echo "<input type=\"hidden\" name=\"shipto1\" id=\"shipto1\" value=".$ship_to_cd1.">";
	   
	$inputmonth="<input name=\"prdmonth\" type=\"text\"  id=\"prdmonth\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"4\" size=\"4\" value=".$xmonth.">";
	$inputyear="<input name=\"prdyear\" type=\"text\"  id=\"prdyear\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"5\" size=\"5\" value=".$cyear.">";
	$txtcorno="<input name=\"corno\" type=\"text\"  id=\"corno\" class=\"arial11blackbold\"  maxlength=\"20\" size=\"20\" readonly=\"true\" value=".$vcorno.">";
	$inputordno="<input name=\"orderno\" type=\"text\"  id=\"orderno\" class=\"arial11blackbold\" readonly=\"true\" value=".$xordno.">"; 
		
	//echo ">>>" .$table;
?>
<?php ctc_get_logo() ?>

<div id="mainNav">
	<?
		$_GET['selection']="main";
		include("navhoriz.php");			
	?>
	
</div> 
<div id="isi">
    <div id="twocolRight1">
	
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
				<td width="22%"><?php echo get_lng($_SESSION["lng"], "L0050"); ?><!--Customer Number--></td>
				<td width="2%">:</td>
				<td width="26%"><? echo $txtcusno ?></td>
				<td width="4%"></td>
				<td width="20%"><?php echo get_lng($_SESSION["lng"], "L0055"); ?><!--Customer Name--></td>
				<td width="2%">:</td>
				<td width="25%"><? echo $vcusnm ?></td>
			</tr>
			<tr class="arial11blackbold">
				<td><? echo $inputshpno ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="arial11blackbold">
				<td><?php echo get_lng($_SESSION["lng"], "L0051"); ?><!--ship to--></td>
				<td>:</td>
				<td colspan="5">
					<table width="97%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="11%" class="arial11redbold"><? echo  $shipto2 ?> </td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr class="arial11blackbold">
				<td><?php echo get_lng($_SESSION["lng"], "L0311"); ?><!--Ship To Address--> :</td>
				<td>:</td>
				<td colspan="5">
					<table width="97%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>
                                <td width="5%"style="padding-top: 27px;" class="arial11blackbold" id="shipToAddress"><? echo  $cus2_addr ?></td>
                            </tr>
                        </tbody></table>
				</td>
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
				<td><?php echo get_lng($_SESSION["lng"], "L0052"); ?><!--Order Date--></td>
				<td>:</td>
				<td>
					<? echo substr($vcdate,-2)."-".substr($vcdate,4,2)."-".substr($vcdate,0,4) ?>
				</td>
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
				<td><?php echo get_lng($_SESSION["lng"], "L0056"); ?><!--Denso Order Number--></td>
				<td>:</td>
				<td  class="arial11blackbold"><? echo $inputordno ?></td>
				<td></td>
				<td><?php echo get_lng($_SESSION["lng"], "L0054"); ?><!--Order Type--></td>
				<td>:</td>
				<td><input name="status" type="text"  id="ordstatus" class="arial11blackbold" readonly="true" value="Request"></td>
			</tr>
			<tr class="arial11blackbold">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td  class="arial11blackbold">&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="arial11blackbold">
				<td><?php echo get_lng($_SESSION["lng"], "L0053"); ?><!--PO Number--></td>
				<td>:</td>
				<td  class="arial11blackbold">
				<? echo $txtcorno ?>
				</td>
				<td></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
        </table>
        <p>&nbsp;</p>
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr align="right">
            <td width="10%">
			<div style="display:flex; margin: 0 5px;">
				<!--<input name="btn_save" type="submit" class="ui-button" id="btn_save" value="Save">-->
				<button class="ui-button_new" type="submit" id="btn_save"  style="margin:0 5px;"><?php echo get_lng($_SESSION["lng"], "L0057"); ?></button>
				<button class="ui-button_new" type="submit" id="btn_close"  style="margin:0 5px;"><?php echo get_lng($_SESSION["lng"], "L0058"); ?></button>

			</div>
				
			</td>
            
			<td>
				<div style="width: 463px;background-color: white;border-radius: 5px;height: 44px;">
					<div style="float:left; position:relative; width:20%; height: 40px;">
						<div class="vertical-center" style="right:10px;font-size: 12px;font-weight: 700;"><?php echo get_lng($_SESSION["lng"], "L0567");/*Grand Total*/ ?></div>
					</div>
					<div style="width:80%;">
						<div style="float:left; text-align:center; margin-right:5px;">
							<div style="font-size: 12px;font-weight: 600;color: #ad1d36;"><?php echo get_lng($_SESSION["lng"], "L0568");/*QTY*/ ?></div>
							<div><input style="text-align:right;border-radius: 5px;" type="text" class="amt-txt" readonly /></div>
						</div>
						<div style="text-align:center;">
							<div style="font-size: 12px;font-weight: 600;color: #ad1d36;"><?php echo get_lng($_SESSION["lng"], "L0569");/*Amount*/ ?></div>
							<div><input style="text-align:right;border-radius: 5px;" type="text" class="ttl-txt" readonly /></div>
						</div>
					</div>
				</div>
			</td>
            <td width="25%" style="text-align: center;">
				<div style="
					display: flex;
					flex-direction: row;
					justify-content: center;
				">
					<div style="margin: 0 10px;">
						<div>
							<button class="ui-button_approve" type="submit" id="approve1"  style="background-color:#ef923d; padding:5px;"><?php echo get_lng($_SESSION["lng"], "L0579"); ?></button>
						</div>
						<div style="white-space: nowrap;">
							<span style="color:red;"><?php echo get_lng($_SESSION["lng"], "L0577"); ?></span>
						</div>
					</div>
					<?php
						if($comp != 'IN0'){
							echo '<div style="margin: 0 10px;">';
								echo '<div>';
									echo '<button class="ui-button_approve" type="submit" id="approve2"  style="background-color:#579df0; padding:5px;">'.get_lng($_SESSION["lng"], "L0580").'</button>';
								echo '</div>';
								echo '<div  style="white-space: nowrap;">';
									echo '<span style="color:red;">'.get_lng($_SESSION["lng"], "L0578").'</span>';
								echo '</div>';
							echo '</div>';

						}
					?>
					<div style="margin: 0 10px;">
						<button class="ui-button_approve" type="submit" id="reject"  style="background-color:#ababab; padding:5px;"><?php echo get_lng($_SESSION["lng"], "L0581"); ?></button>				
					</div>
				</div>
			</td>
          </tr>
        </table>
		<!-- End comment Change for AWS Dec 2022 -->
		
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr class="arial11redbold"  align="center" >
            <td width="7%" height="10"></td>
            <td width="37%"></td>
            <td width="46%"></td>
            <td width="10%"></td>
          </tr>
        </table>
        <table class="tblorder"  width="97%" border="0" cellpadding="0" cellspacing="0"  id="myTable">
        <tbody>
          <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
          <th width="35%" >
          <table class="tblorder"  width="100%" border="0" cellpadding="0" cellspacing="0"  id="myTable2">
            <tbody>
              <tr align="center" valign="middle"  bgcolor="#990033" class="arial11whitebold" >
            	 <th width="5%" height="30"><?php echo get_lng($_SESSION["lng"], "L0553"); ?><!--All-->
					<input type="checkbox" name="chk_all_approve"/>
				 </th>
            	<th width="22%" ><?php echo get_lng($_SESSION["lng"], "L0060"); ?><!--Part Number--></th>
                <th width="8%" ><?php echo get_lng($_SESSION["lng"], "L0559"); ?><!--Status--></th>
            	<th width="10%" ><?php echo get_lng($_SESSION["lng"], "L0568"); ?><!--Quantity--></th>
                <th width="5%" ><?php echo get_lng($_SESSION["lng"], "L0572"); ?><!--AWS Curr--></th>
            	<th width="15%" ><?php echo get_lng($_SESSION["lng"], "L0573"); ?><!--Aws Price--></th>
            	<th width="5%" ><?php echo get_lng($_SESSION["lng"], "L0574"); ?><!--Dealer Curr--></th>
            	<th width="10%"><?php echo get_lng($_SESSION["lng"], "L0575"); ?><!--Dealer Price--></th>
            	<th width="20%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0576"); ?><!--Error Message--></th>
              </tr>

              
<?
	require('db/conn.inc');
//check denso or non denso
//Non denso
if($table == "supawsorderhdr"){
	$query="SELECT  
	supawsorderhdr.shipto,
    supawsorderdtl.*,
    supawsorderhdr.*,
    supawsorderdtl.ordflg AS OrderFlag,
	supprice.curr as 'dealer_curr',
    supprice.price as 'dealer_price'
	FROM
		supawsorderhdr
	INNER JOIN supawsorderdtl ON supawsorderhdr.orderno = supawsorderdtl.orderno AND supawsorderhdr.corno = supawsorderdtl.corno AND supawsorderhdr.Owner_Comp = supawsorderdtl.Owner_Comp AND supawsorderhdr.supno = supawsorderdtl.supno
	LEFT JOIN awscusmas ON supawsorderhdr.Dealer = awscusmas.cusno1 AND supawsorderhdr.Owner_Comp = awscusmas.Owner_Comp AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto
	LEFT JOIN supprice ON supawsorderdtl.partno = supprice.partno AND supawsorderdtl.Owner_Comp = supprice.Owner_comp AND supawsorderdtl.supno = supprice.supno AND supprice.Cusno = awscusmas.cusno1 ";
	$query= $query . "WHERE trim(supawsorderhdr.orderno) = '$xordno' AND supawsorderhdr.dealer = '$cusno'   
		AND trim(supawsorderhdr.corno) = '$vcorno'  AND supawsorderhdr.Owner_Comp = '$comp'  and supawsorderdtl.supno='$supno'
	ORDER BY supawsorderdtl.partno";
	// echo $query;
	$sql=mysqli_query($msqlcon,$query);		
	while($hasil = mysqli_fetch_array ($sql)){
		$msg = '';
		$partno=$hasil['partno'];
		$partdes=$hasil['itdsc'];
		$qty=$hasil['qty'];
		$disc=$hasil['disc'];
		$curcd=$hasil['CurCD'];
		$bprice=$hasil['slsprice'];
		$dlrcurcd=$hasil['dealer_curr'];
		$dlrprice=$hasil['dealer_price'];
		$ordflg=$hasil['OrderFlag'];
		$cusno2=$hasil['CUST3'];
		$cusno1=$hasil['Dealer'];
		$shipto=$hasil['shipto'];
		$ordtype=$hasil['ordtype'];
		$supno=$hasil['supno'];

		switch(trim($ordflg)){
			case "1":
				$status ="Complete(supplier)";
				break;
			case "2":
				$status ="Complete(warehouse)";
				break;
			case "U":
				$status ="Unomplete";
				break;
			case "R":
				$status="Rejected";
				break;
			default:
				$status="Pending";
				break;
		}
		
		//GET LOT SIZE & price
		$SQLlotsize="
			SELECT Lotsize, price, awscusmas.*
			FROM supcatalogue AS cat 
				LEFT JOIN supprice on cat.ordprtno = supprice.partno and cat.Owner_Comp = supprice.Owner_Comp
				LEFT JOIN awscusmas ON awscusmas.cusno1 = supprice.Cusno AND awscusmas.Owner_Comp = supprice.Owner_comp AND awscusmas.ship_to_cd1 = supprice.shipto
			WHERE cat.Owner_Comp = '$comp'  AND cusno = '$cusno' and trim(ordprtno) = '$partno'  and awscusmas.cusno2 = '$cusno2' and awscusmas.ship_to_cd2 = '$shipto'
				and cat.Supcd = '$supno'
			ORDER BY CarMaker asc";
		   // echo $SQLlotsize;
		//Pasakorn Add 
		$Querylotsize=mysqli_query($msqlcon,$SQLlotsize);	
		if (mysqli_num_rows($Querylotsize) == 0) {
			// $msg="Error: Sales Price was not found. Please contact DENSO PIC";
			$msg = get_lng($_SESSION["lng"], "E0008"); 
		} else {
			$row = mysqli_fetch_array ($Querylotsize);
			if($row['ship_to_cd1'] == ''){
				//Error : customer ship to code [1st customer ship to code] not found in aws customer master. Please contact Denso PIC
				$msg = get_lng($_SESSION["lng"], "E0083"); 
			}
			if(intval($row['price']) == "0"){
				// $msg="Error: Sales Price was not found. Please contact DENSO PIC";			
				$msg = get_lng($_SESSION["lng"], "E0008"); 

			}
			if((int)$row['Lotsize'] ==  "0"){
				// $msg="Error : Lot size was not found. Please contact DENSO PIC";
				$msg = get_lng($_SESSION["lng"], "E0057");
			}
			else{
				if((int)$qty < 0){
					// $msg="Error: Qty should be filled by numeric and greater than 0!";
					$msg = get_lng($_SESSION["lng"], "E0021");

				}
				else{
					if(fmod((int)$qty, (int)$row['Lotsize']) != 0){
						// $msg="Error : Not in Lot size, Lotsize = " . $row['Lotsize'];
						$msg = get_lng($_SESSION["lng"], "E0001") . $row['Lotsize'];
					}
				}
			}
		}	
		// CHECK Shipto
		$SQLShipto="
			SELECT * 
			FROM awscusmas 
			where cusno1 = '$cusno'and cusno2 = '$cusno2' and ship_to_cd2 = '$shipto' and Owner_Comp = '$comp'
		";
		// echo $SQLShipto;
		$QueryShipto=mysqli_query($msqlcon,$SQLShipto);	
		if (mysqli_num_rows($QueryShipto) == 0) {
			// $msg="Error : customer ship to code not found in aws customer master. Please contact Denso PIC";
			$msg = get_lng($_SESSION["lng"], "E0081");
			echo '<input type="hidden" class="dis_all_approve" value="1"/> ';
		} else {
			$row = mysqli_fetch_array ($QueryShipto);
			//Check ship to code in supref
			$SQLshiptocode="Select * from awscusmas 
			INNER JOIN supref ON supref.cusno = awscusmas.cusno1 and supref.Owner_comp = awscusmas.Owner_Comp
			INNER JOIN shiptoma ON shiptoma.ship_to_cd = supref.shipto and shiptoma.ship_to_cd = awscusmas.ship_to_cd1 and shiptoma.Owner_Comp = awscusmas.Owner_Comp

			where awscusmas.cusno1 = '$cusno' AND supref.supno = '$supno' and awscusmas.Owner_Comp='".$comp."' and awscusmas.ship_to_cd2 = '$shipto'";
			// echo $SQLshiptocode;
			$Queryshiptocode=mysqli_query($msqlcon,$SQLshiptocode);	
			if (mysqli_num_rows($Queryshiptocode) == 0) {
				$msg="Error: Ship to code  '".$shipto. "' not found in ship to master. Please contact Denso PIC";
				echo '<input type="hidden" class="dis_all_approve" value="1"/> ';
			} 
		}
	//echo ">>".$shipto;
		$disco=number_format(($bprice*$disc)/100,0,".",",");
		$dlrdisco=number_format(($bprice*$dlrdisc)/100,0,".",",");
		$ttl=number_format($slsprice*$qty,0,".",",");
		$vbprice=number_format($bprice * $qty,2,".",",");
		$vdlrprice=number_format($dlrprice,2,".",",");

		/*
		$ttl = ($ttl=="0.00") ? "" :  $ttl;
		$vbprice = ($vbprice=="0.00") ? "" :  $vbprice;
		$vdlrprice = ($vdlrprice=="0.00") ? "" :  $vdlrprice;
		*/	

		echo "<tr>";
		echo "<td align=\"center\"><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" . $partno . "></td>" ;
		echo "<td style=\"padding-left:5px;\">".$partno." - ". $partdes."</td>";
		echo "<td class=\"status\" align=\"Center\">" . $status . "</td>";
		echo "<td class=\"qty\" align=\"Center\" >".$qty."</td>";
		echo "<td class=\"curcd\" align=\"Center\">" . $curcd . "</td>";								            
		echo "<td  style=\"padding-right:5px;\" class=\"price\" align=\"right\" value=\"$vbprice\">" . $vbprice . "</td>" ;
		echo "<td class=\"curcda\" align=\"Center\">" . $dlrcurcd . "</td>" ;
		echo "<td style=\"padding-right:5px;\" id=\"dlrprice\" align=\"right\">". $vdlrprice;
		echo "<input id=\"ordtype\" type=\"hidden\" value=\"". $ordtype  . "\"/></td>";
		echo "<input id=\"supno\" type=\"hidden\" value=\"". $supno  . "\"/></td>";
		echo "<td id=\"error\" class=\"lasttd arial11redbold\">".$msg."</td>";
		echo "</tr>";
	}
}
else{
	 $query="SELECT *
	FROM awsorderhdr
	INNER JOIN awsorderdtl ON awsorderhdr.orderno = awsorderdtl.orderno AND awsorderhdr.corno = awsorderdtl.corno AND awsorderhdr.Owner_Comp = awsorderdtl.Owner_Comp 
	left join bm008pr on partno=ITNBR and awsorderdtl.Owner_Comp=bm008pr.Owner_comp ";
	$query= $query . "WHERE trim(awsorderhdr.orderno) = '$xordno' AND awsorderhdr.dealer = '$cusno'  AND trim(awsorderhdr.corno) = '$vcorno'  AND awsorderhdr.Owner_Comp = '$comp' ORDER BY partno";
	$sql=mysqli_query($msqlcon,$query);		
	while($hasil = mysqli_fetch_array ($sql)){
		$msg = '';
		$partno=$hasil['partno'];
		$partdes=$hasil['ITDSC'];
		$qty=$hasil['qty'];
		$disc=$hasil['disc'];
		$curcd=$hasil['CurCD'];
		$bprice=$hasil['bprice'];
		$dlrcurcd=$hasil['DlrCurCd'];
		$dlrprice=$hasil['DlrPrice'];
		$ordflg=$hasil['ordflg'];
		$cusno2=$hasil['cusno'];
		$cusno1=$hasil['Dealer'];
		$shipto=$hasil['shipto'];
		$ordtype=$hasil['ordtype'];


		switch(trim($ordflg)){
			case "":
				$status="Pending";
				break;
			case "1":
				$status ="Complete(supplier)";
				break;
			case "2":
				$status ="Complete(warehouse)";
				break;
			case "U":
				$status ="Unomplete";
				break;
			case "R":
				$status="Reject";
				break;
			default:
				$status="";
				break;
		}

			//GET LOT SIZE
			$SQLlotsize="SELECT ITDSC,Lotsize 
			FROM bm008pr WHERE trim(ITNBR) = '".$partno."' and  Owner_Comp='$comp' ";
			$Querylotsize=mysqli_query($msqlcon,$SQLlotsize);	
			if (mysqli_num_rows($Querylotsize) == 0) {
				// $msg="Error: Sales Price was not found. Please contact DENSO PIC";
				$msg = get_lng($_SESSION["lng"], "E0008");
			} else {
				$row = mysqli_fetch_array ($Querylotsize);
				
				if((int)$row['Lotsize'] ==  "0"){

					// $msg="Error : Lot size was not found. Please contact DENSO PIC";
					$msg = get_lng($_SESSION["lng"], "E0056");
				}
				else{
					if(fmod((int)$qty, (int)$row['Lotsize']) != 0){
						// $msg="Error : Not in Lot size, Lotsize = " . $row['Lotsize'];
						$msg = get_lng($_SESSION["lng"], "E0001") . $row['Lotsize'];
					}
				}
			}	


		
			//Check ship to 
			$SQLshipto="SELECT *
			FROM awscusmas WHERE cusno1 = '".$cusno."' and cusno2 = '".$cusno2."' and Owner_Comp='$comp' and ship_to_cd2 = '$shipto'";
			$Queryshipto=mysqli_query($msqlcon,$SQLshipto);	
			//echo $SQLshipto."<br/>";
			if (mysqli_num_rows($Queryshipto) == 0) {
				// $msg="Error : customer ship to code [2nd customer ship to code] not found in aws customer master. Please contact Denso PIC";
				$msg = get_lng($_SESSION["lng"], "E0082");
				echo '<input type="hidden" class="dis_all_approve" value="1"/> ';
			} else{
				$SQLshipto="SELECT *
				FROM awscusmas
				inner join shiptoma on shiptoma.ship_to_cd = awscusmas.ship_to_cd1 AND awscusmas.cusno1 = shiptoma.Cusno AND awscusmas.Owner_Comp = shiptoma.Owner_Comp
				WHERE cusno1 = '".$cusno."' and cusno2 = '".$cusno2."' and awscusmas.Owner_Comp='$comp' and ship_to_cd2 = '$shipto'";
				$Queryshipto=mysqli_query($msqlcon,$SQLshipto);	
				if (mysqli_num_rows($Queryshipto) == 0) {
					// $msg="Error : customer ship to code [1st customer ship to code] not found in ship to master. Please contact Denso PIC";
					$msg = get_lng($_SESSION["lng"], "E0083");
					echo '<input type="hidden" class="dis_all_approve" value="1"/> ';

				}
				else{
					$row = mysqli_fetch_array ($Queryshipto);
					$shiptox = $row['ship_to_cd1'];
				}
			}
			//echo $SQLshipto."<br/>";
			if($shiptox != ''){
				//check price
				if ($erp != '0' && $comp != 'IN0') {
					
					$tf_table = "tf_snd_web_item_ma_".strtolower($comp);
					//fix here
					// echo $query="
					// select * 
					// from tf_snd_web_item_ma_".strtolower($comp)." 
					// where CST_ORDR_ITEM_NO_DSP =  '".$partno."'  and OWNER_COMP = '$comp'
					// and CST_CD = '$cusno'";
					$query="
					SELECT
						*
					FROM
						sellprice
					LEFT JOIN $tf_table ON $tf_table.OWNER_COMP = sellprice.Owner_Comp AND $tf_table.CST_CD = sellprice.Cusno AND $tf_table.CST_CD = sellprice.Itnbr
							AND $tf_table.SHP_TO_CD = sellprice.Shipto
					WHERE
						sellprice.Itnbr = '".$partno."' AND sellprice.Owner_Comp = '$comp' AND sellprice.Cusno = '$cusno' and sellprice.Shipto = '$shiptox';";
					//echo $query;
					$QueryPRICE=mysqli_query($msqlcon,$query); 
					$row = mysqli_fetch_array ($QueryPRICE);

					if (mysqli_num_rows($QueryPRICE) == 0) {
						// $msg="Error : Sales Price was not found. Please contact DENSO PIC";
						$msg = get_lng($_SESSION["lng"], "E0008"); 
					} 
					else{
						$row = $row;
						$dlrcurcd = $row['CRNCY_CD'] == null ? $row['CurCD']: $row['CRNCY_CD'];
						$vdlrprice = $row['SLS_AMNT'] == null ?  $row['Price']: $row['SLS_AMNT'];
					}
					
				}else if($comp == 'IN0'){
					$query="
						SELECT
						   awsexc. *
						FROM
							awsexc
						JOIN awscusmas ON awscusmas.Owner_Comp = awsexc.Owner_Comp AND awscusmas.cusno1 = awsexc.cusno1 
						WHERE
							awsexc.Owner_Comp = '$comp' AND awscusmas.cusno2 = '$cusno2' and awsexc.itnbr = '$partno'";
					//echo $query;
					$QueryPRICE=mysqli_query($msqlcon,$query); 
					if (mysqli_num_rows($QueryPRICE) == 0) {
						// $msg="Error : Sales Price was not found. Please contact DENSO PIC";
						$msg = get_lng($_SESSION["lng"], "E0008");
					} 
					else{
						$row = mysqli_fetch_array ($QueryPRICE);
						$dlrcurcd = $row['curr'];
						$vdlrprice = $row['price'];
					}
				}

				else {
					
					$SQLprice="SELECT *
					FROM sellprice WHERE trim(Itnbr) = '".$partno."' 
					and  cusno= '".$cusno."' and Owner_Comp='".$comp."'and Shipto='".$shiptox. "'";
					$QueryPRICE=mysqli_query($msqlcon,$SQLprice); 
					if (mysqli_num_rows($QueryPRICE) == 0) {
						// $msg="Error : Sales Price was not found. Please contact DENSO PIC";
						$msg = get_lng($_SESSION["lng"], "E0008");
					} 
					else{
						$row = mysqli_fetch_array ($QueryPRICE);
						$dlrcurcd = $row['CurCD'];
						$vdlrprice = $row['Price'];
					}
				}
			}
			//Check QTY
			if((int)$qty < 0){
				// $msg="Error : Qty should be filled by numeric and greater than 0!";
				$msg = get_lng($_SESSION["lng"], "E0021");
			}
			
			 
			

			//Check phaseout
			include_once('checkPhaseOut.php');
			$Phout=checkPhaseOut($partno);
			if($Phout[0]=='E'){
				// $msg='Error : '. $Phout[1];
				// $msg='Error : This is a Phase Out Part. Please contact DENSO';
				$msg = get_lng($_SESSION["lng"], "E0058");
			}
			// else{
				// if(ctc_get_session_erp() == 0){
					// $SQLphaseout="SELECT *
					// FROM phaseout WHERE trim(ITNBR) = '".$partno."'   and Owner_Comp='".$comp."'";
					// $Queryphaseout=mysqli_query($msqlcon,$SQLphaseout);	
					// if (mysqli_num_rows($Queryphaseout) == 0) {
						// $msg="Error: This is a Phase Out Part. Please contact DENSO PIC";
					// } 
				// }
			// }


		$disco=number_format(($bprice*$disc)/100,0,".",",");
		$dlrdisco=number_format(($bprice*$dlrdisc)/100,0,".",",");
		$vbprice=number_format($bprice,2,".",",");
		$ttl=($bprice*$qty);

		$vdlrprice=number_format($vdlrprice,2,".",",");

		/*
		$ttl = ($ttl=="0.00") ? "" :  $ttl;
		$vbprice = ($vbprice=="0.00") ? "" :  $vbprice;
		$vdlrprice = ($vdlrprice=="0.00") ? "" :  $vdlrprice;
		*/
	// echo "shiptp = " .$shiptox;
		echo "<tr>";
		echo "<td align=\"center\"><input name=\"chkaction[]\" type=\"checkbox\" class=\"chkaction\" value=" . $partno . "></td>" ;
		echo "<td style=\"padding-left:5px;\">".$partno." - ". $partdes."</td>";
		echo "<td class=\"status\" align=\"Center\">" . $status . "</td>";
		echo "<td class=\"qty\" align=\"Center\">".$qty."</td>";
		echo "<td class=\"curcd\" align=\"Center\">" . $curcd . "</td>";								            
		echo "<td style=\"padding-right:5px;\" class=\"price\" align=\"right\">" . $vbprice . "</td>" ;
		echo "<td class=\"curcda\" align=\"Center\">" . $dlrcurcd . "</td>" ;
		echo "<td style=\"padding-right:5px;\" id=\"dlrprice\" align=\"right\">". $vdlrprice;
		echo "<input id=\"ordtype\" type=\"hidden\" value=\"". $ordtype  . "\"/></td>";
		echo "<input class=\"ttlaws\" type=\"hidden\" value=\"". $ttl  . "\"/></td>";
		echo "<td id=\"error\" class=\"lasttd arial11redbold\">".$msg."</td>";
		echo "</tr>";
	}
			
}
?>
    		</tbody>
        </table>         
		</tr>
    </tbody>
    </table>
    <p><div id="result"></div></p>
    <div class="demo"> 
   	    <div id="dialog-form" title="Reason">
			<p class="validateTips">Reason </p>
			<form>
				<fieldset>
					<label for="reason">
						<textarea cols="42" rows="4" maxlength="80" name="reason" class="text ui-widget-content ui-corner-all" id="reason" /></textarea>
					</label>
				</fieldset>
			</form>
    	</div>
		
  		<div id="dialog-confirm" title="Approved Selected Record?">
			<p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Approved selected item(s). Are you sure?</p>
		</div>
  		<div id="dialog-confirm2" title="Approved Selected Record?">
			<p id="confirm" class="arial11blackbold"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Approved selected item(s). Are you sure?</p>
		</div>
    </div>
    </div>
      
<div id="footerMain1">
	<ul>
		
		     
    </ul>

    <div id="footerDesc">
		<p>Copyright &copy; 2023 DENSO . All rights reserved  </p>
  	</div>
</div>

</body>
</html>
<script type="text/javascript">

function grandtotal() {
	var sumqty = 0;
	var sumttl = 0;
	$('.tblorder').find('td.qty').each(function() {
		sumqty += parseFloat($(this).text().replace(/,/g, ''));
	});
	$('.tblorder').find('.price').each(function() {
		sumttl += parseFloat($(this).text().replace(/,/g, ''));
	});
	$('input.amt-txt').val((sumqty));
	$('input.ttl-txt').val(number_format(sumttl));
}
function number_format(nStr) {
	nStr = parseFloat(nStr).toFixed(2);
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '.00';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
$(function() {
	
	$().ajaxStart(function() {
		$('#loading').show();
		$('#result').hide();
	}).ajaxStop(function() {
		$('#loading').hide();
		$('#result').fadeIn('slow');
	});	   
		   
	var count = 0;
	$('.status').each(function (i) {
		var $this = $(this);
			if($this.html().toLowerCase() == "pending"){
				count++;
			}
	});
	
	if(count >= 1){
		$("#btn_save").attr("disabled", true);
	}


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
			updateTips( "Length of " + n + " must be between " +
				min + " and " + max + "." );
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
		
	$('.chkaction').change(function() {
		
		if ($(this).is(':checked')) {
			var v_error = $(this).closest('tr').find('#error').html();
			console.log(v_error.length);
			// if(v_error.substring(0,5) == "Error"){
			if(v_error.length > 0){
				$("#approve1").attr("disabled", true);
			}
		}else{
			$("#approve1").attr("disabled", false);
			if($('.dis_all_approve').length >= 1){
				$(".ui-button_approve").attr("disabled", true);
			}
		}
	});
	
	$( "#reject" ).click(function() {
		mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
		if(mpart!==''){		
			$( "#dialog-form").dialog( "open" );
		}else{
			alert('please at least select 1 document to Reject!');	
		}
	});
	
	$( "#approve1" ).click(function() {
		mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
		if(mpart!==''){
			vaction='Approve';
			$( "#dialog-confirm" ).dialog('open');				
			
		}else{
			alert('please at least select 1 document to Approve!');		
		}
	});

	$( "#approve2" ).click(function() {
		mpart=$('input[name="chkaction[]"]:checked').map(function(){ return $(this).val(); }).get().join(",");			
		if(mpart!==''){
			vaction='Approve';
			$( "#dialog-confirm2" ).dialog('open');				
			
		}else{
			alert('please at least select 1 document to Approve!');		
		}
	});

	$( "#btn_save" ).click(function() {
		var answer = confirm("Do you want to confirm the order ?")		
		if (answer){
			var vaction="Save";
			var edata;
			var periode=$('#prdyear').val()+$('#prdmonth').val();
			var orderno=$('#orderno').val();
			var corno=$('#corno').val();
			var vcusno=$('#txtcusno').val();
			var ordtype=$('#ordtype').val();
			var supno=$('#supno').val();
			let shipto1= $('#shipto1').val();
			var table=$('#table').val();
					
			edata="vcusno="+vcusno+"&periode="+periode+"&shpno="+supno+"&orderno="+orderno+"&corno="+corno+"&action="+vaction+"&vordtype="+ordtype+"&reason="+reason+"&table="+table+"&shipto1="+shipto1;
			//alert("XX" + edata);
					
				
			$.ajax({
				type: 'GET',
				url: 'saveorder.php',
				data: edata,
				success: function(data) {
					console.log(data);
					if(data.substr(0,7)=='success'){
						alert('Order successfully saved ');
						sendmail();
					}
					else{
						alert('<?php echo get_lng($_SESSION["lng"], "E0027")?>');
						console.log(data);
					}
				}
			});
			
				
		}
	});

	$( "#btn_close" ).click(function() {
		var answer = confirm("Do you want to close without saving?")		
		if (answer){
			url= 'mainAws.php';
			window.location.replace(url);	
		}
	});

	var vaction="";
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
	var res="";
	var reason = $( "#reason" ),
		allFields = $( [] ).add( reason),
		tips = $( ".validateTips" );

	// dialog confirm
	//Approve1
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,					  
		resizable: false,
		height:200,
		modal: true,
		buttons: {
			"Approve selected": function() {
				$.each($('.chkaction:checked'),
					function() {
						$(this).closest('tr').children('td[class=status]').text('Complete(supplier)');							 						
						$(this).attr('checked', false);
					});
					
					var vaction="Approve1";
					var edata;
					var periode=$('#prdyear').val()+$('#prdmonth').val();
					var orderno=$('#orderno').val();
					var corno=$('#corno').val();
					var vcusno=$('#txtcusno').val();
					var ordtype=$('#ordtype').val();
					var table=$('#table').val();
					var supno=$('#supno').val();
					
					edata="vcusno="+vcusno+"&periode="+periode+"&shpno="+supno+"&orderno="+orderno+"&corno="+corno+"&action="+vaction+"&mpartno="+mpart+"&vordtype="+ordtype+"&reason="+reason+"&table="+table;
				//alert("XX" + edata);
					
				
					$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
								
							if (data.trim().length > 0){
								// alert(data);
								console.log(data);
							}
							else{
								location.reload();
							}
						}
					});
					
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});

	//Approve2
	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,					  
		resizable: false,
		height:200,
		modal: true,
		buttons: {
			"Approve selected": function() {
				$.each($('.chkaction:checked'),
					function() {
						$(this).closest('tr').children('td[class=status]').text('Complete(warehouse)');							 						
						$(this).attr('checked', false);
					});
					
					var vaction="Approve2";
					var edata;
					var periode=$('#prdyear').val()+$('#prdmonth').val();
					var orderno=$('#orderno').val();
					var corno=$('#corno').val();
					var vcusno=$('#txtcusno').val();
					var ordtype=$('#ordtype').val();
					var table=$('#table').val();
					var supno=$('#supno').val();
					
					edata="vcusno="+vcusno+"&periode="+periode+"&shpno="+supno+"&orderno="+orderno+"&corno="+corno+"&action="+vaction+"&mpartno="+mpart+"&vordtype="+ordtype+"&reason="+reason+"&table="+table;
				//alert("A2" + edata);
				
					$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
							if (data.trim().length > 0){
								alert(data);
								//console.log(data);
							}
							else{
								location.reload();
							}
						}
					});
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	//end dialog confirm

	//Reject
	$("#dialog-form").dialog({
		autoOpen: false,
		height: 250,
		width: 350,
		modal: true,
		open: function(event, ui){ $("#reason").focus(); }, 
			
		buttons: {
			"OK": function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );

				bValid = bValid && checkLength( reason, "Reason", 2, 200 );
				/** check reason**/
				
				ereason=reason.val();
				var edata;
				var vaction="RejectAWS";
				var periode=$('#prdyear').val()+$('#prdmonth').val();
				var orderno=$('#orderno').val();
				var corno=$('#corno').val();
				var vcusno=$('#txtcusno').val();
				var ordtype=$('#ordtype').val();
				var table=$('#table').val();
				var supno=$('#supno').val();
				
				edata="vcusno="+vcusno+"&periode="+periode+"&shpno="+supno+"&orderno="+orderno+"&corno="+corno+"&action="+vaction+"&mpartno="+mpart+"&vordtype="+ordtype+"&reason="+ereason+"&table="+table;
			    //alert("dialog" + edata);
				
				if ( bValid ) {
					$.ajax({
						type: 'GET',
						url: 'saveorder.php',
						data: edata,
						success: function(data) {
							if (data.trim().length > 0){
								// alert(data);
							}
							else{
								$.each($('.chkaction:checked'), function() {
									$(this).closest('tr').children('td[class=status]').text('Rejected');							 	
									$(this).attr('checked', false);
									$( "#dialog-form").dialog( "close" );
									location.reload();
								});	
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

	//sendmail
	function sendmail() {
		var orderno=$('#orderno').val();
		var corno=$('#corno').val();
		var vcusno=$('#txtcusno').val();
		var ordtype=$('#ordtype').val();
		var table=$('#table').val();
		var supno=$('#supno').val();
		edata="vcusno="+vcusno+"&orderno="+orderno+"&shpno="+supno+"&corno="+corno+"&vordtype="+ordtype+"&table="+table;
		//alert(edata);
		
		$.ajax({
			type: 'GET',
			url: 'aws_approve.php',
			data: edata,
			success: function(data) {
				// alert(data);
				console.log(data);
				url= 'mainAws.php';
				 window.location.replace(url);	
			}
		});
		
	}
	$('[name="chk_all_approve"]').change(function (){
		if ($('[name="chk_all_approve"]').is(':checked')) {
			$('.chkaction').attr('checked',true);
		}else{
			$('.chkaction').attr('checked',false);
		}
		
		$('.chkaction').each(function(){
			
			if ($(this).is(':checked')) {
			var v_error = $(this).closest('tr').find('#error').html();
			console.log(v_error.length);
			// if(v_error.substring(0,5) == "Error"){
			if(v_error.length > 0){
				$("#approve1").attr("disabled", true);
			}
			}else{
				$("#approve1").attr("disabled", false);
				if($('.dis_all_approve').length >= 1){
					$(".ui-button_approve").attr("disabled", true);
				}
				
			}
		});
	});
	grandtotal();
	
	
	if($('.dis_all_approve').length >= 1){
		$(".ui-button_approve").attr("disabled", true);
	}
});
</script>