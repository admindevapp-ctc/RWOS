<? session_start() ?>
<?
require_once('../language/Lang_Lib.php');
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
		$imptable=$_SESSION['imptable'];
		//echo $imptable;
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


</head>
<body >
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        <?
				$_GET['selection']="advancedOrder";
				include("navhoriz.php");
			
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           		<?
			  	$_GET['current']="advancedOrder";
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
    <td>Advance Order Upload</td>
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
        
   </table>     
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11">
    <th  scope="col">&nbsp;</th>
    <th width="90" scope="col">&nbsp;</th>
    <th width="90" scope="col" align="right">&nbsp;</th>
  </tr>
  <tr height="5"><td colspan="5"></td><tr>
</table>

       
     <?
	// echo "session imptable =" . $_SESSION['imptable'];
		$arrdata=explode('|', $_POST['txtShpNo']);
		$shpno=$arrdata[0];
		$oecus=$arrdata[1];
		$shipment=$_POST['shipment'];
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
			//find cut of date
				$query="select * from cutofdate where Period='$YYYYmm'";
				//echo $query;
				$sql=mysqli_query($msqlcon,$query);		
				if($hasil = mysqli_fetch_array ($sql)){
				    $cutdate=$hasil['CutOfDate'];
				}		
		
			// find customer Group
			$qrycusmas="select * from cusmas where cusno= '$shpno' ";
			$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
			if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
				$cusgr=$hslcusmas['CusGr'];
				$route=$hslcusmas['route'];
			}
			
		
		
		$data = new Spreadsheet_Excel_Reader($_FILES['file']['tmp_name'], true);
			//read the number of rows from excel data
			$baris = $data->rowcount($sheet_index=0);
			//echo $baris; //line
			$sukses = 0;//success
			$gagal = 0; // failed
			$error=0;
			$flag="";
			$exrate=1;
			$dlrprice=0;
			$bprice=0;
			$qty=0;
			for ($i=2; $i<=$baris; $i++)
				{
				if(trim($data->val($i, 1))==""){
				   break;
				}
				$PO = $data->val($i, 1);
				if($flag==""){
					$vPO=$PO;
					$flag="1";
				}
				//echo $PO ."-". strlen($PO)."<br>";
				if(trim($PO)!="" && strlen($PO)<=10 ){     //PO tidak sama debgan kosong
				
				$PO = $data->val($i, 1);
				$PartNo= strtoupper($data->val($i, 2));
				$qty = trim($data->raw($i, 3));
				$price=0;
				$lot=1;
				$query="select * from bm008pr where trim(ITNBR) = '$PartNo'";
				$sql=mysqli_query($msqlcon,$query);		
				if($hasil = mysqli_fetch_array ($sql)){;
					$partdes=addslashes($hasil['ITDSC']);
					$lot=$hasil['Lotsize'];
					$ittyp=$hasil['ITTYP'];
					$itcat=$hasil['ITCAT'];	
					$ordsts='R';	
					$sisa=$qty%$lot;
					if($sisa!=0){
						$partdes=get_lng($_SESSION["lng"], "E0002")./*Error: Not in Lot size*/", Lot=".number_format($lot);
						$ordsts='E';
						$error++;
					}else{
					if(!is_numeric($qty)){
						$partdes="Qty should be filled by numeric!";
						$ordsts='E';
						$error++;
					}else{
						if(trim($PO)!=trim($vPO)){
							$partdes=get_lng($_SESSION["lng"], "E0003")/*"Only 1 PO Number allowed!"*/;
							$ordsts='E';
							$error++;			   
					  	} else{
						
							$qry="select * from orderhdr where cust3='".$cusno."' and Corno='".trim($PO)."'";
        					$sqle=mysqli_query($msqlcon,$qry);
   							$hsle = mysqli_fetch_array ($sqle);
        					if($hsle){
                     				$partdes="PO NO has already found in DB!";
									$ordsts='E';
									$error++;	
						 //echo $partdes;
            				} else{
								//---------------------------------------
								 $flag='1';
								if(strtoupper($route)=='N'){
									$query="select * from sellpriceaws where trim(Itnbr) = '$PartNo' and cusno= '$shpno' ";
									$flag='2';
								}else{
									$query="select * from sellprice where trim(Itnbr) = '$PartNo' and cusno= '$shpno' ";
								}
								//echo $query."<br>";
								 $sql=mysqli_query($msqlcon,$query);		
								 if($hasil = mysqli_fetch_array ($sql)){;
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
										$qrycur="select * from excrate  where trim(CurCD) ='$curcd' and EfDateFr<='$cymd' order by EfDateFr desc ";
										$sqlcur=mysqli_query($msqlcon,$qrycur);		
										if($hslcur = mysqli_fetch_array ($sqlcur)){
											$exrate=$hslcur['Rate'];
										}
										
										if(strtoupper($oecus)!='Y'){
										
										//due date
										$qrydue="select * from crduedate where CUSGR='".$cusgr ."' and ITTYP='".$ittyp."' and ITCAT='".$itcat."'" ;
								  		$sqldue=mysqli_query($msqlcon,$qrydue);
								  		if($hasildue = mysqli_fetch_array ($sqldue)){
								      		if($cutdate>=$tglorder){
										  		$blndue=$hasildue['RBCUT'];
										  		$tgldue=$hasildue['DBCUT'];
										   	}else{
										   		$blndue=$hasildue['RACUT'];
										   		$tgldue=$hasildue['DACUT'];
									  		}
										  
											$cyear=date('Y');
											$cmonth=date('m'); 
											$cday=date('d');
											$pbln=(int)$cmonth+(int)$blndue;
											if($pbln>12){
												$pbln=$pbln-12;
												$pbln=str_pad((int) $pbln,2,"0",STR_PAD_LEFT);
												$pyear=(int)$cyear+1;
											}else{
													$pbln=str_pad((int) $pbln,2,"0",STR_PAD_LEFT);	
													$pyear=$cyear;
											}
											if($tgldue=="-"){
												$tgldue=str_pad((int) $cday,2,"0",STR_PAD_LEFT);
											}
											$cduedt=$pyear.$pbln.$tgldue;
											$tduedt=$tgldue."/".$pbln."/".$pyear;
										
										}else{
											$partdes="profile not found!";
											$ordsts='E';
											$error++;	
										}
										
										//due date
										}else{  //oe due date
											if(strtoupper($shipment=='A')){
												//add 45 days
													$addDays=45;	
												}else{
						   							$addDays=60;	
											}
										$cduedt= date('Ymd', strtotime("+".$addDays." days"));
										$tduedt= date('d/m/Y', strtotime("+".$addDays." days"));
											
										}
								 }else{
									 
									
									$jawab=checkPhaseOut($PartNo);
									if($jawab[0]=='E'){
										$partdes='Error : '. $jawab[1]; 
										$ordsts='E';
										$error++;
									}else{
										if($jawab[0]==''){
											$partdes='Error :Sales price not found!';			
											$ordsts='E';
											$error++;
										}else{
										$xsub=$jawab[1];
										if(strtoupper($route)=='N'){
											$query="select * from sellpriceaws where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
											$flag='2';
										}else{
											$query="select * from sellprice where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
										}
										//echo $query;
										$sql=mysqli_query($msqlcon,$query);		
										if($hasil = mysqli_fetch_array ($sql)){
											$partdes='For order use :'.$xsub;
											$ordsts='E';
											$error++;
										}else{
											$partdes='Sales Price Not Found!';
											$ordsts='E';
											$error++;			
										}
										}
									//	echo $partdes;
								}
									 
									 
									//Check with Phase out number
											/**
											$qryphase="select * from phaseout where trim(ITNBR) ='$PartNo'" ;
											$sqlphase=mysqli_query($msqlcon,$qryphase);
											if($hslphase = mysqli_fetch_array ($sqlphase)){	
												$xsub=$hslphase['SUBITNBR'];
												$xdesc=$hslphase['ITDSC'];
												if($xsub==""){
													$partdes='Error : '. $xdesc; 
													$ordsts='E';
													$error++;
												}else{
													$flag='1';
													if(strtoupper($route)=='N'){
														$query="select * from sellpriceaws where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
														$flag='2';
													}else{
														$query="select * from sellprice where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
													}
													$sql=mysqli_query($msqlcon,$query);		
													if($hasil = mysqli_fetch_array ($sql)){
														$partdes='For order use :'.$xsub;
														$ordsts='E';
														$error++;
													}else{
														$partdes='Error :Sales price not found!';			
														$ordsts='E';
														$error++;													
													}
								
								
												}			
											}else{
   												$partdes='Error :Sales price not found!';			
												$ordsts='E';
												$error++;
	
											}
											**/
											// phase out											

	
									
									
									}	
								
								
							}
						
						}
					
					}
					
					}
										
				}else{
					$bprice=0;
					$igroup='';
					
					
					$jawab=checkPhaseOut($PartNo);
					if($jawab[0]=='E'){
						$partdes='Error : '. $jawab[1]; 
						$ordsts='E';
						$error++;
						
						
					}else{
						if($jawab[0]==''){
								$partdes='Part Number not found';
								$ordsts='E';
								$error++;
						}else{
								$xsub=$jawab[1];
								if(strtoupper($route)=='N'){
									$query="select * from sellpriceaws where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
									$flag='2';
								}else{
									$query="select * from sellprice where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
								}
								//echo $query;
								$sql=mysqli_query($msqlcon,$query);		
								if($hasil = mysqli_fetch_array ($sql)){
										$partdes='For Order use:'.$xsub;
										$ordsts='E';
										$error++;
								}else{
										$partdes='Sales Price not found!';
										$ordsts='E';
										$error++;			
								}
						}
				}
				/**
					//Check with Phase out number
					$qryphase="select * from phaseout where trim(ITNBR) ='$PartNo'" ;
					$sqlphase=mysqli_query($msqlcon,$qryphase);
					if($hslphase = mysqli_fetch_array ($sqlphase)){	
						$xsub=$hslphase['SUBITNBR'];
						$xdesc=$hslphase['ITDSC'];
						if($xsub==""){
								$partdes='Error : '. $xdesc; 
								$ordsts='E';
								$error++;
							
						}else{
							$flag='1';
							if(strtoupper($route)=='N'){
								$query="select * from sellpriceaws where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
								$flag='2';
							}else{
								$query="select * from sellprice where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
							}
							$sql=mysqli_query($msqlcon,$query);		
							if($hasil = mysqli_fetch_array ($sql)){
								$partdes='For Order use:'.$xsub;
								$ordsts='E';
								$error++;
							}else{
								$partdes='Error :Sales price not found!';			
								$ordsts='E';
								$error++;													
							}
								
								
						}			
					}else{
   						$partdes='Part Number not found';
						$ordsts='E';
						$error++;
				
					}
				**/
				// phase out
				}
				} else { // po tidak sama dengan kosong

						$partdes="PO should be filled and not more than 10 digits !";
						$ordsts='E';
						$error++;
				}	
				$qryimp="select * from $imptable where cust3='".$cusno."' and Corno='".trim($PO)."' and partno='$PartNo'";
				//echo $qryimp;
        		$sqleimp=mysqli_query($msqlcon,$qryimp) or die(mysqli_error());
   				$hsle = mysqli_fetch_array ($sqleimp);
        		if($hsle){
						$partdes="Duplicate Part Number !";
						$ordsts='E';
						$error++;
				}
 				$query = "INSERT INTO $imptable(Cust3, Corno, orderdate,duedate, ordprd, cusno,partno,partdes, ordsts, qty, CurCD, bprice,SGCurCD, SGPrice, impgrp, DlrCurCD, DlrPrice, OECus, Shipment) VALUES ('$cusno', '$PO', '$cymd','$cduedt', '$YYYYmm', '$shpno','$PartNo','$partdes','$ordsts',$qty,'$curcd',  $price,'SD', $exrate, '$igroup', '$dlrcurcd', $dlrprice, '$oecus', '$shipment')";
 				//echo $query . "<br>";
					$hasil = mysqli_query($msqlcon,$query);
					if($hasil)
					{
						$sukses++;
					}else{ 
						$gagal++;
					//echo $query;
					}
						
				}//for
 
		// tampilan status sukses dan gagal
		$amend='1';
		
		if($error>0 || $gagal>0){
			$amend='2';
			echo "<h3>Import finished. (Failure Item)</h3>";
			echo "<p>Number Item can be imported : ".$sukses."<br>";
			echo "Number of Failed Item : ".$gagal."</p>";
			echo "Number of Problem Record : ".$error."</p>";
			
			echo "<table width=\"100%\" class=\"tbl1\" cellspacing=\"0\" cellpadding=\"0\">";
  			echo "<tr class=\"arial11grey\" bgcolor=\"#AD1D36\">";
    		echo "<th width=\"9%\" height=\"30\" >Order Date</th>";
    		echo "<th width=\"23%\" >Po Number</th>";
    		echo "<th width=\"23%\" >Part Number</th>";
		    echo "<th width=\"17%\" >Qty</th>";
   			echo "<th width=\"28%\" class=\"lastth\">Error Description</th>";
    		echo "</tr>";
			
			$query="select * from ".$imptable. "  where trim(ordsts) ='E'";
			$sql=mysqli_query($msqlcon,$query);		
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$corno=$hasil['Corno'];
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
			
		
		}else{
			   // get order No
			   $xperiode=substr($YYYYmm,-4);
			   $query="select * from tc000pr where trim(cusno) = '$cusno'";
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
				$xordno=$alias.$vordno."R";
				
				
				
				// insert Temporary Import Table to Temporary order Table
				
				$query="select * from ".$imptable ;
				$sql=mysqli_query($msqlcon,$query);		
				$x=0;
				while($hasil = mysqli_fetch_array ($sql)){
						$cdate=$hasil['orderdate'];
						$duedt=$hasil['duedate'];
						$partno=$hasil['partno'];
						$partdesc=$hasil['partdes'];
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
						$query2="insert into ".$table. " (CUST3, orderno, orderdate,	cusno, partno,	partdes, ordstatus, qty, CurCD, bprice,SGCurCD, SGPrice,  disc, dlrdisc, slsprice, Corno, duedate, DlrCurCD, DlrPrice, OECus, Shipment )				values('$cusno', '$xordno','$cdate','$vcusno','$partno','$partdesc','$ordstst',$qty,'$curcd', $bprice, 'SD',$SGPrice, $disc, $dlrdisc,$slsprice,'$corno', '$duedt', '$dlrcurcd', $dlrprice,'$oecus', '$shipment')";
						//echo $query2."<br>";
						mysqli_query($msqlcon,$query2);
					$x=$x+1;
				}
			
			$action="add";
			//redirect to edit
			include "crypt.php";
            $lokasi="order2.php?".paramEncrypt("action=$action&ordno=$xordno&cusno=$cusno&shpno=$vcusno&orddate=$cdate&corno=$corno&oecus=$oecus&shipment=$shipment");
         echo "<script>document.location.href='".$lokasi. "';</script>";
			
			
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
 echo '<td>'.get_lng($_SESSION["lng"], "E0014")/*PLEASE AMEND YOUR PO BY REMOVING ABOVE PART NUMBER FROM EXCEL FILE AND TRY TO UPLOAD AGAIN*/.'</td>';
 echo '</tr>';
echo '<tr  class="arial11whitebold">';
echo '<td>'.get_lng($_SESSION["lng"], "E0018")/*SYSTEM WILL ONLY PROCESS YOUR PO WHEN ALL PART NUMBER ARE REGISTERED IN DIAS WITH SALES PRICE*/.'</td>';
echo '</tr><tr class="arial11whitebold"><td>'.get_lng($_SESSION["lng"], "E0019")/*PLEASE TAKE NOTE, ONLY 1 PO NUMBER ALLOWED FOR 1 UPLOAD FILE*/.'</td></tr></table>';
}
?>
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
