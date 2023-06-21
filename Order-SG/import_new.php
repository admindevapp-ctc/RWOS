<?php 
session_start();
require_once('../language/Lang_Lib.php');
//if (session_is_registered('cusno'))
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

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
		$imptable=$_SESSION['imptable'];
		//echo $imptable;
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
include('chklogin.php');

// $arrdata=explode('|', $_POST['txtShpNo']);
// $shpno=$arrdata[0];
// $oecus=$arrdata[1];
$shpno=$_POST['txtShpNo'];
$oecus=$_POST['txtShpNoHidden'];
$shipment=$_POST['shipment'];
$shpCd=$_POST['shipToCd'];
$ordertype=$_POST['ordertype'];
$requestDate=$_POST['requestDate']!=null?$_POST['requestDate']:"";
$txtnote=$_POST['txtnote']; //zia added Note
require("crypt.php");
if($ordertype=='Normal'){
	$_GET['selection']="main";
}
else if($ordertype=='Urgent'){
	$_GET['selection']="urgentOrder";
}
else if($ordertype=='Request'){
	$_GET['selection']="requestDueDate";
}
?>
<link rel="stylesheet" type="text/css" href="css/dnia.css">
<link rel="stylesheet" href="themes/ui-lightness/jquery-ui-red.css">
<script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script>
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
<script src="lib/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="css/demos.css">
<style>

    .ui-dialog .ui-state-error { padding: .3em; }

    

</style>
<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<!--start-- 03/10/2019 Prachaya inphum CTC -->
<script type="text/javascript">
 $(function() {

 $("#dialog-timeout").dialog({
    autoOpen: false,
            width: 300,
<?php if ($ordertype == 'Request') { ?>
        height:'auto',
<?php } else { ?>
        height:'auto',
<?php } ?>
    modal: true,
            position: {
            my: "center",
                    at: "center",
                    of: $("body"),
                    within: $("body")
            },
            buttons: [
                        {
                            text:  "<?php echo get_lng($_SESSION["lng"], "L0317"); ?>",
                            click: function() {
								<!--start-- 28/10/2019 Prachaya inphum CTC -->
								$cusno = '<?echo $cusno;?>';
								$alias = '<?echo $alias;?>';
								$.ajax({
											type: 'GET',
											url: 'deletetemp.php',
											data: "alias=" + $alias + "&cusno=" + $cusno,
											success: function(data) {
													}

										});
								<!--end-- 28/10/2019 Prachaya inphum CTC -->
								$( this ).dialog( "close" );
                            }
                        }
                    ],
            close: function() {
				<!--start-- 31/10/2019 Prachaya inphum CTC -->
				$cusno = '<?echo $cusno;?>';
				$alias = '<?echo $alias;?>';
				$.ajax({
					type: 'GET',
					url: 'deletetemp.php',
					data: "alias=" + $alias + "&cusno=" + $cusno,
					success: function(data) {
					}
				});
				<!--end-- 31/10/2019 Prachaya inphum CTC -->
                window.location.href = "main.php";
            }
    });
    
 });
</script>
 <!-- 03/10/2019 Prachaya inphum CTC --end-->
</head>
<body >
		
		<?php ctc_get_logo() ?> <!-- add by CTC -->

		<div id="mainNav">
       
        <?
				//$_GET['selection']="main";
				include("navhoriz.php");
			
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           		<?
			  	//$_GET['current']="main";
				if($ordertype=='Normal'){
					$formtitle=get_lng($_SESSION["lng"], "L0164")/*'Normal Order Upload'*/;
					$_GET['current']="main";
				}
				else if($ordertype=='Urgent'){
					$formtitle=get_lng($_SESSION["lng"], "L0291")/*'Urgent Order Upload'*/;
					$_GET['current']="urgentOrder";
				}
				else if($ordertype=='Request'){
					$formtitle=get_lng($_SESSION["lng"], "L0290")/*'Requested Due Date Order Upload'*/;
					$_GET['current']="requestDueDate";
				}
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
    <tr class="arial21redbold">
    <td><?php echo $formtitle;?></td>
    <td>&nbsp;</td>
   <td colspan="5"><?php if($ordertype=='Urgent'){include('countdown.php');}?></td>
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
    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0191"); ?><!--Customer Number--></td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0192"); ?><!--Customer Name--></td>
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
<!-- Zia Added Note --Stert -->
					<tr class="arial11blackbold">
						<td><?php echo get_lng($_SESSION["lng"], "L0334"); ?></td>
						<td>:</td>
						<td colspan="5"><? echo $txtnote ?></td>
					</tr>
<!-- Zia Added Note --End -->
        
   </table>     
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th  scope="col">&nbsp;</th>
    <th width="90" scope="col">&nbsp;</th>
    <th width="90" scope="col" align="right">&nbsp;</th>
  </tr>
  <tr height="5"><td colspan="5"></td><tr>
</table>

       
     <?php
	// echo "session imptable =" . $_SESSION['imptable'];
		
		if(basename($_FILES['file']['name'])==""){
			echo "<script>document.location.href='imregorder.php';</script>";
		}
			
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Error: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{			 
  		$filename=basename($_FILES['file']['name']);
  		$ext = substr($filename, strrpos($filename, '.') + 1);
		/**echo $ext;
		echo "<br>";
		echo $_FILES["file"]["type"];
		echo "<br>";
		echo $_FILES["file"]["size"];**/
	if (($ext == "xls") && ($_FILES["file"]["size"] < 2000000)) {
			
			//Order Number and Date 
			$cyear=date('Y');
			$cmonth=date('m');
			$cdate=date('d');
			$cymd=date('Ymd');
			$YYYYmm=date('Ym');
			$tglorder=date('Ymd');

			require('db/conn.inc');
			include "excel_reader2.php";
			require('checkPhaseOut.php');
			require('checkMTO.php');
			require('checkStock.php');
			require('getDueDate.php');
			require('getUrgentDueDate.php');
			require('getUrgentOrderQty.php');
			require('sendemail.php');
			require('getRequestDueDate.php');
			//find cut of date
				$query="select * from cutofdate where Period='$YYYYmm' and Owner_Comp='$comp' ";
				//echo $query;
				$sql=mysqli_query($msqlcon,$query);		
				if($hasil = mysqli_fetch_array ($sql)){
				    $cutdate=$hasil['CutOfDate'];
				}		
		
			// find customer Group
			$qrycusmas="select * from cusmas where cusno= '$shpno' and Owner_Comp='$comp' ";
			$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
			if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
				$cusgr=$hslcusmas['CusGr'];
				$route=$hslcusmas['route'];
			}
			
		
		$count=0;
		$data = new Spreadsheet_Excel_Reader($_FILES['file']['tmp_name'], true);
			//read the number of rows from excel data
			$count = $data->rowcount($sheet_index=0);
			$sukses = 0;//success
			$failed = 0; // failed
			$warning=0;
			$error=0;
			$flag="";
			//date_default_timezone_set('Asia/Bangkok'); // CDT
			$today = getdate();
			$hour = $today['hours'];
			$min = $today['minutes'];
			$sec = $today['seconds'];
			$currenttime=$hour.":".$min.":".$sec;
			$time="";
			$setdayQry="select * from duedate where ordtype='U' and Owner_Comp='$comp' ";
			$setdaysql=mysqli_query($msqlcon,$setdayQry);
			if($result = mysqli_fetch_array ($setdaysql)){
				$time=$result['setday'];
			}
			$errorCount=0;
			for ($i=2; $i<=$count; $i++)
			{	
				$errorMsg='';
				$eachError=0;
				$exrate=1;
				$dlrprice=0;
				$bprice=0;
				$qty=0;
				$bprice=0;
				$igroup='';
				/*if(trim($data->val($i, 1))==""){
				   break;
				}*/
				$PO='';
				$PO = $data->val($i, 1);
				$ch = strlen($PO); $len = 0;
			 	for ($inx = 0; $inx < $ch; ++$inx){
			 		if ((ord($PO[$inx]) & 0xC0) != 0x80) ++$len;
			 	}
				if($flag==""){
					$vPO=$PO;
					$flag="1";
				}
				//echo $PO ."-". strlen($PO)."<br>";
				$PartNo= strtoupper($data->val($i, 2));
				$qty = $data->raw($i, 3)!=null?trim($data->raw($i, 3)):0;
				$price=0;
				$lot=1;
				$ordsts='';
				
				if(trim($PO)!="" && $len<=10 )
				{     //PO tidak sama debgan kosong
				
					//$PO = $data->val($i, 1);
					//if ( preg_match('/\s/',$PO) || preg_match('/\\\\/',$PO) || strpos($PO, '/') != false || preg_match('/[ก-ฮ*|\":<>[\]{}#^`\\%().;@&$]/u',$PO)===1 ){
						//if ( preg_match('/\s/',$PO) || preg_match('/\\\\/',$PO) || strpos($PO, '/') != false || preg_match('/[ก-๙*|\"\/,!+=~?฿:<>[\]{}#\'^`\\%().;@&$]/u',$PO)===1 ){//02/13/2019 P.Pawan CTC fix check Thai language and special character
						if ( preg_match('/\s/',$PO) || preg_match('/\\\\/',$PO) || strpos($PO, '/') != false || preg_match('/[^a-zA-Z_\-0-9]/i',$PO)===1) {//25/04/2019 P.Pawan CTC fix check Thai language and special character
						//$errorMsg.="Error: PO Number can not contain space, / , \\\\ <br>";
						$errorMsg.=get_lng($_SESSION["lng"], "E0034");
						$ordsts='E';
						$error++;
						$eachError++;
					}
				    
					//$query="select * from bm008pr where trim(ITNBR) = '$PartNo' and Owner_Comp='$comp'";

					if(ctc_get_session_erp() =='0'){
						$query="select itdsc as ITDSC, (select Lotsize from bm008pr where trim(ITNBR) = '$PartNo' and Owner_Comp='$comp') as Lotsize from qbm008pr where salavl='YES' and trim(prtno) = '$PartNo' and Owner_Comp='$comp' limit 1";
					}else{
						$query="select ITDSC,Lotsize from bm008pr where trim(ITNBR) = '$PartNo' and Owner_Comp='$comp'";
					}

					$sql=mysqli_query($msqlcon,$query);		
					if($hasil = mysqli_fetch_array ($sql)){

						$partdes=addslashes($hasil['ITDSC']);
						$lot=$hasil['Lotsize'];
						// $ittyp=$hasil['ITTYP'];
						// $itcat=$hasil['MTO'];	// 20191028 // Disable Aug 2020
						// $ordsts=$ordertype; // Disable Aug 2020
						if($ordsts!='E'){ //02/13/2019 P.Pawan CTC fix check Thai language and special character
							$ordsts=$ordertype; //02/13/2019 P.Pawan CTC fix check Thai language and special character
						}	 //02/13/2019 P.Pawan CTC fix check Thai language and special characte

						if($lot == null || $lot == 0){
							$errorMsg.=get_lng($_SESSION["lng"], "E0056")./*Error : Lot size was not found. Please contact DENSO PIC*/", Lot=".number_format($lot);
							$ordsts='E';
							$error++;
							$eachError++;
						}else{
							$mod=$qty%$lot;
							if($mod!=0){
								$errorMsg.=get_lng($_SESSION["lng"], "E0020")./*Error: Not in Lot size*/", Lot=".number_format($lot);
								$ordsts='E';
								$error++;
								$eachError++;
							}else{
								if(!is_numeric($qty) || $qty <= 0){
									$errorMsg.=get_lng($_SESSION["lng"], "E0021")/*"Error: Qty should be filled by numeric and greater than 0!"*/;
									$ordsts='E';
									$error++;
									$eachError++;
								}else
								{
									if(trim($PO)!=trim($vPO)){
										$errorMsg.=get_lng($_SESSION["lng"], "E0003")/*"Error : Only 1 PO Number allowed!"*/;
										$ordsts='E';
										$error++;		
										$eachError++;									
									} else
									{
									
										$qry="select * from orderhdr where cust3='".$cusno."' and Corno='".trim($PO)."' and Owner_Comp='$comp'";
										$sqle=mysqli_query($msqlcon,$qry);
										$hsle = mysqli_fetch_array ($sqle);
										if($hsle){
												$errorMsg.=get_lng($_SESSION["lng"], "E0004")/*"Error : PO Number has been found in order history!"*/;
												$ordsts='E';
												$error++;	
												$eachError++;
									 //echo $partdes;
										} else{
											/* Block for DGAMS
											$saleCount=0;
											$saleQry="select * from qbm008pr where prtno='$PartNo' and salavl='YES' "; (Zia modify)
						
											$saleSql=mysqli_query($msqlcon,$saleQry);	
											$saleCount = mysqli_num_rows($saleSql);
											if($saleCount==0){
												$errorMsg.=get_lng($_SESSION["lng"], "E0033"); //"Error : This partno can not be sold!<br>"
												$ordsts='E';
												$error++;	
												$eachError++;
												
											} */
											
											$flag='1';
											if(strtoupper($route)=='N'){
												$query="select * from sellpriceaws where trim(Itnbr) = '$PartNo' and cusno= '$shpno' and Owner_Comp='$comp' ";
												$flag='2';
											}else{
												$query="select * from sellprice where trim(Itnbr) = '$PartNo' and cusno= '$shpno' and Owner_Comp='$comp' and Shipto='$shpCd' ";
											}
											//echo $query."<br>";
											 $sql=mysqli_query($msqlcon,$query);		
											 if($hasil = mysqli_fetch_array ($sql))
											 {
												if($flag=='1'){
													$curcd=$hasil['CurCD'];
													$price=$hasil['Price'];
													$dlrcurcd=$hasil['CurCD'];
													$dlrprice=$hasil['Price'];
												}else{
													$curcd=$hasil['CurCDAWS'];
													$price=$hasil['PriceAWS'];
													$dlrcurcd=$hasil['CurCD'];
													$dlrprice=$hasil['Price'];
												}
						
													// Exchange Rate
													$qrycur="select * from excrate  where trim(CurCD) ='$curcd' and EfDateFr<='$cymd' and Owner_Comp='$comp' order by EfDateFr desc ";
													$sqlcur=mysqli_query($msqlcon,$qrycur);		
													if($hslcur = mysqli_fetch_array ($sqlcur)){
														$exrate=$hslcur['Rate'];
													}
													if(strtoupper($oecus)!='Y'){
													
													//due date
															
														if($ordertype=='Normal'){
															$dueArray=getDueDate();
															$duedate_format1=$dueArray[0];
															$duedate_format2=$dueArray[1];
															$err=$dueArray[2];
															$desc=$dueArray[3];
															if($err==1){
															$errorMsg.=$desc; 
															$ordsts='E';
															$error++; 
															$eachError++;
															}
														}
														else if ($ordertype=='Urgent'){
															
																$urgentDueArray=getUrgentDueDate($time);
																$duedate_format1=$urgentDueArray[0];
																$duedate_format2=$urgentDueArray[1];
																$err=$urgentDueArray[2];
																$desc=$urgentDueArray[3];
																if($err==1){
																$errorMsg.=$desc; 
																$ordsts='E';
																$error++; 
																$eachError++;
																}
															if(strtotime($currenttime)<strtotime($time)){
																$errArr=checkLimitedUrgentOrderQty($shpno,$qty,$PartNo);
																if($errArr[0]=='E'){
																	$ordsts='E';
																	$errorMsg.=$errArr[1]."<br/>";
																	$error++;
																	$eachError++;																	
																}
																
															}
															
														}
														else if($ordertype=='Request'){
															$duedate_format1=date('Ymd', strtotime(".$requestDate."));
														}
													
													//due date
													}else{  //oe due date
														if(strtoupper($shipment=='A')){
															//add 45 days
																$addDays=45;	
															}else{
																$addDays=60;	
														}
													$duedate_format1= date('Ymd', strtotime("+".$addDays." days"));
													$duedate_format2= date('d/m/Y', strtotime("+".$addDays." days"));
														
													}
													
													$warningArray=checkStock($PartNo,$qty,$ordertype);
													if($warningArray[0]=='W'){
														$errorMsg.=$warningArray[1]."<br/>"; 
														$ordsts='W';
														$warning++;
													}
													else if($warningArray[0]=='E'){
														$errorMsg.=$warningArray[1]."<br/>"; 
														$ordsts='E';
														$error++;
														$eachError++;
													}
													
													$warningArray=checkMto($PartNo,$ordertype);
													if($warningArray[0]=='W'){
														$errorMsg.=$warningArray[1]."<br/>"; 
														$ordsts='W';
														$warning++;
													}
													else if($warningArray[0]=='E'){
														$errorMsg.=$warningArray[1]."<br/>"; 
														$ordsts='E';
														$error++;
														$eachError++;
													}
													
													if(ctc_get_session_erp() == 0){
														$errorArray=checkPhaseOut($PartNo);
														if($errorArray[0]=='E'){
															$errorMsg.=get_lng($_SESSION["lng"], "E0007")/*'Error: This is a Phase Out Part. Please contact DSTH'*/; 
															$ordsts='E';
															$error++;
															$eachError++;
														}
													}
											 }else{
												 $errorMsg.=get_lng($_SESSION["lng"], "E0008")/*'Error: Sales Price was not found. Please contact DSTH'*/;
												 $ordsts='E';
												 $error++;
												 $eachError++;
											}
											//-------------------------------------------
										}	
											
											
									}
									
								}
							}
						}				
						
					}else{
						$errorMsg.= get_lng($_SESSION["lng"], "E0009")/*"Error: Item master was not found. Please contact DSTH<br/>"*/;
						$ordsts='E';
						$error++;
						$eachError++;
					
					}
										
				}else { // po tidak sama dengan kosong

						$errorMsg.=get_lng($_SESSION["lng"], "E0010")/*"Error: PO should be filled and not more than 10 digits !<br/>"*/;
						$ordsts='E';
						$error++;
						$eachError++;
				}	
				$qryimp="select * from $imptable where cust3='".$cusno."' and Corno='".trim($PO)."' and partno='$PartNo' and Owner_Comp='$comp'";
				//echo $qryimp;
        		$sqleimp=mysqli_query($msqlcon,$qryimp) or die(mysqli_error());
   				$hsle = mysqli_fetch_array ($sqleimp);
        		if($hsle){
						$errorMsg.=get_lng($_SESSION["lng"], "E0011")/*"Error: Duplicate Part Number !"*/;
						$ordsts='E';
						$error++;
						$eachError++;
				}
				if($error>0 || $warning>0){
					$partdes = $errorMsg;
				}

				if($eachError>0){
					$errorCount++;
				}
				
				$query = "INSERT INTO $imptable(Cust3, Corno, orderdate,duedate, ordprd, cusno,partno,partdes, ordsts, qty, CurCD, bprice,SGCurCD, SGPrice, impgrp, DlrCurCD, DlrPrice, OECus, Shipment,Owner_Comp) VALUES ('$cusno', '$PO', '$cymd','$duedate_format1', '$YYYYmm', '$shpno','$PartNo','$partdes','$ordsts',$qty,'$curcd',  $price,'SD', $exrate, '$igroup', '$dlrcurcd', $dlrprice, '$oecus', '$shipment','$comp')";
				//echo $query . "<br>";
				$hasil = mysqli_query($msqlcon,$query);
				if($hasil)
				{
					$sukses++;
				}else{ 
					$failed++;
				//echo $query;
				}
			
						
				}//for
 
		// tampilan status sukses dan failed
		$amend='1';
		
		if($error>0 || $failed>0 || $warning>0){
			$amend='2';
			$total=$count-1;
			echo "<h3>".get_lng($_SESSION["lng"], "L0193")/*Import finished.*/."</h3>";
			echo get_lng($_SESSION["lng"], "L0194")/*Total Record*/.": ".$total."</p>";
			echo get_lng($_SESSION["lng"], "W0021")/*Warning (can be uploaded) */.": ".$warning."<br>";
			echo get_lng($_SESSION["lng"], "E0017")/*Error (can not be uploaded) */.": ".$error."<br>";
			echo "<table width=\"100%\" class=\"tbl1\" cellspacing=\"0\" cellpadding=\"0\">";
  			echo "<tr class=\"arial11white\" bgcolor=\"#AD1D36\">";
    		echo "<th width=\"9%\" height=\"30\" >".get_lng($_SESSION["lng"], "L0197")/*Order Date*/."</th>";
    		echo "<th width=\"23%\" >".get_lng($_SESSION["lng"], "L0198")/*Po Number*/."</th>";
    		echo "<th width=\"23%\" >".get_lng($_SESSION["lng"], "L0199")/*Part Number*/."</th>";
		    echo "<th width=\"17%\" >".get_lng($_SESSION["lng"], "L0200")/*Qty*/."</th>";
   			echo "<th width=\"28%\" class=\"lastth\">".get_lng($_SESSION["lng"], "L0201")/*Error Description*/."</th>";
    		echo "</tr>";
			
			$query="select * from ".$imptable." where (ordsts ='E' or ordsts ='W') and Owner_Comp='$comp' ";
			//echo $query;
			$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno']!=''?$hasil['partno']:"-";
				$corno=$hasil['Corno']!=''?$hasil['Corno']:'-';
				$partdes=$hasil['partdes'];
				$orddt=substr($hasil['orderdate'],-2)."/".substr($hasil['orderdate'],4,2)."/".substr($hasil['orderdate'],0,4);
				$qty=$hasil['qty'];
				echo "<tr class=\"arial11black\" align=\"center\" height=\"25\">";
				echo "<td>".$orddt."</td>";
				echo "<td>".$corno."</td>";
				echo "<td>".$partno."</td>";
				echo "<td>".$qty."</td>";
				echo "<td>".$partdes."</td>";
				echo "</tr>";
			
			} // end while
			
		
		}
		
		// get order No
		   $xperiode=substr($YYYYmm,-4);
		   $query="select * from tc000pr where trim(cusno) = '$cusno' and Owner_Comp='$comp' ";
			$sql=mysqli_query($msqlcon,$query);		
			$hasil = mysqli_fetch_array ($sql);
			$order=$hasil['ROrdno'];
			if(strlen(trim($order))!=7){
				$vordno=$xperiode."001";
			}else{
				$ordprd=substr($order,0,4);
				if($ordprd!=$xperiode){
					$vordno=$xperiode."001";
				}else{
					$ordval=(int)substr($order,-3);
					$ordval1=$ordval+1;
					$strordval=str_pad((int) $ordval1,3,"0",STR_PAD_LEFT);
					$vordno=$xperiode.$strordval;
				}
			}
			$xordno=$alias.$vordno.substr($ordertype,0,1);
			
			
			
			// insert Temporary Import Table to Temporary order Table
			
			$query="select a.*, b.ITDSC from ".$imptable." a, bm008pr b where a.partno=b.itnbr and a.Owner_Comp=b.Owner_Comp and a.ordsts!='E' and a.partno not in (select partno from $imptable where ordsts='E' and Owner_Comp='$comp') and a.Owner_Comp='$comp' " ;
			// echo $query;
			$sql=mysqli_query($msqlcon,$query);		
			$x=0;
			while($hasil = mysqli_fetch_array ($sql)){
					$cdate=$hasil['orderdate'];
					$duedt=$hasil['duedate'];
					$partno=$hasil['partno'];
					$partdesc=$hasil['ITDSC'];
					$disc=0;
					$dlrdisc=0;
					$ordstst=$hasil['ordsts'];
					$qty=$hasil['qty'];
					$bprice=$hasil['bprice'];
					$slsprice=$bprice-(($bprice*$disc)/100);
					$corno=$hasil['Corno'];
					$vcusno=$hasil['cusno'];
					$curcd=$hasil['CurCD'];
					$SGPrice=$hasil['SGPrice'];
					$dlrcurcd=$hasil['DlrCurCd'];
					$dlrprice=$hasil['DlrPrice'];
					$query2="insert into ".$table. " (CUST3, orderno, orderdate,	cusno, partno,	partdes, ordstatus, qty, CurCD, bprice,SGCurCD, SGPrice,  disc, dlrdisc, slsprice, Corno, duedate, DlrCurCD, DlrPrice, OECus, Shipment,Owner_Comp) values('$cusno', '$xordno','$cdate','$vcusno','$partno','$partdesc','$ordertype',$qty,'$curcd', $bprice, 'SD',$SGPrice, $disc, $dlrdisc,$slsprice,'$corno', '$duedt', '$dlrcurcd', $dlrprice,'$oecus', '$shipment','$comp')";
					//echo $query2."<br>";
					mysqli_query($msqlcon,$query2);
				$x=$x+1;
			}
		
		$action="add";
		//redirect to edit
		//include "crypt.php";
		$url="order2_new.php?".paramEncrypt("action=$action&ordno=$xordno&cusno=$cusno&shpno=$cusno&orddate=$cdate&corno=$corno&oecus=$oecus&shipment=$shipment&ordertype=$ordertype&requestDate=$requestDate&shpCd=$shpCd&txtnote=$txtnote"); //Zia added tetnote
		$url2="import_order_new.php?".paramEncrypt("ordertype=$ordertype&action=delete&ordno=$xordno&cusno=$cusno&corno=$corno");
		
		if ($errorCount==$total){
			//echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><p><br></td></tr><tr><td align=\"right\"><p><a href='".$url2."'><img src=\"images/Cancel.png\" width=\"80\" height=\"20\"></><p><tr><td></table>";
			?>
			<table width="100%" cellspacing="0" cellpadding="0" style='margin-top:20px;'>
				<td width="85%"></td>
				<td align="right">
					<a href='<?php echo $url2; ?>' style='text-decoration-line: none;'>
						<div style='background-color: #AD1D36;color: #FFFFFF;width: 80px;height:22px;'>
							<font style='font-size:12pt;line-height:22px;margin-right:18px;'><?php echo get_lng($_SESSION["lng"], "L0203"); ?></font>
						</div>
					</a> 
				</td>
			</table>
			<?php
		}
		else{
			/*
			 echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><p><br></td></tr><tr><td align=\"right\">
			 <p>
			 <a href='".$url."'><img src=\"images/proceed.png\" width=\"124\" height=\"20\"></a> 
			 <a href='".$url2."'><img src=\"images/Cancel.png\" width=\"80\" height=\"20\"></a>
			 <p><tr><td></table>";
			 */
			?>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style='margin-top:20px;'>
				<tr>
					<td width="85%"></td>
					<td align="right">
						<a href='<?php echo $url; ?>' style='text-decoration-line: none;'>
							<div style='background-color: #AD1D36;color: #FFFFFF;width: 140px;height:22px;'>
								<font style='font-size:10pt;line-height:22px;margin-right:8px;'><?php echo get_lng($_SESSION["lng"], "L0202"); ?></font> 
							</div>
						</a> 
					</td>
					<td width="2%"></td>
					<td width="9%" align="right">
						<a href='<?php echo $url2; ?>' style='text-decoration-line: none;'>
							<div style='background-color: #AD1D36;color: #FFFFFF;width: 80px;height:22px;'>
								<font style='font-size:10pt;line-height:22px;margin-right:22px;'><?php echo get_lng($_SESSION["lng"], "L0203"); ?></font>
							</div>
						</a>
					</td>
				<tr>
			</table>
			<?php
		}
			
		if ($error==0 && $warning==0){
			echo "<script>document.location.href='".$url. "';</script>";
		}
	
			
		}else{
			echo "<h3>".get_lng($_SESSION["lng"], "E0013")/*You should select excel file and not more than 2 mb*/."</h3>";
		}
  }
		
		// membaca file excel yang diupload
?>
		
		
		
 
 <tr>
    <td colspan="6" class="lasttd" align="right"></td>
    </tr> 
</table>
<?php
if($amend=='2'){
	echo "<p>";
 echo '<table width="90%" border="0" align="center" bgcolor="#AD1D36">';
 echo '<tr  class="arial11whitebold">';
 echo '<td width="80px" rowspan="3"><img src="images/Exclam.png"></td>';
 echo '<td>'.get_lng($_SESSION["lng"], "E0014").'</td>';
 echo '</tr>';
echo '<tr  class="arial11whitebold">';
echo '<td>'.get_lng($_SESSION["lng"], "E0018").'</td>';
echo '</tr><tr class="arial11whitebold"><td>'.get_lng($_SESSION["lng"], "E0019").'</td></tr></table>';
}
?>
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
<?php include('timecheck.php'); ?> <!-- 03/10/2019 Prachaya inphum CTC-->