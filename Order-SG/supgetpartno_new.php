<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

require_once('../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']!='denso-sg'){
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
		$table = ctc_get_session_tablenamesup();
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
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

if (trim($_GET['partno']) == '') {
	$error = 'Error: Part No  should be filled';
}


if ($error) {
	echo $error;
} else {
	//$cdate=date('Ym');
	//date_default_timezone_set('Asia/Bangkok'); // CDT
	$today = getdate();
	$hour = $today['hours'];
	$min = $today['minutes'];
	$sec = $today['seconds'];
	$currenttime=$hour.":".$min.":".$sec;
	$partno=strtoupper(trim($_GET['partno']));
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	//$supno=trim($_GET['supno']);
	$action=trim($_GET['action']);
	$shpno=trim($_GET['shpno']);
	$oecus=trim($_GET['oecus']);
	$shipment=trim($_GET['shipment']);
	$qty=$_GET['qty'];
	$ordertype = trim($_GET['ordertype']);
	$shpCd = trim($_GET['shpCd']);
	require('db/conn.inc');
	$YMD=date('Ymd');
	$err='';
	
	// check order
	if($action =='edit'){
			$queryold="select * from  ".$table. " where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."' and Owner_Comp='$comp'";  // edit by CTC
			//echo $query2;
			$sqlold=mysqli_query($msqlcon,$queryold);
			if($hasilold = mysqli_fetch_array ($sqlold)){
				$oldqty=$hasilold['qty'];
				$duedate=$hasilold['DueDate'];
			}
			
	}
	
	$qrycusmas="select * from cusmas where cusno= '$shpno' and Owner_Comp='$comp' ";
	//echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
		$cusgr=$hslcusmas['CusGr'];
		$route=$hslcusmas['route'];
		$oecus=$hslcusmas['OECus'];
	}
	
	$flag='1';
	$query="select * from supprice where partno = '$partno' and Cusno= '$shpno' and Owner_Comp='$comp' and shipto='$shpCd' ";
	
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
		
		$curcd=$hasil['curr'];
		$bprice=$hasil['price'];
		$curcddlr=$hasil['curr'];
		$bpricedlr=$hasil['price'];
		
		/*
		$qrycur="select * from excrate  where trim(CurCD) = '$curcd' and EfDateFr<='$YMD' and Owner_Comp='$comp' order by EfDateFr desc ";
		$sqlcur=mysqli_query($msqlcon,$qrycur);		
		if($hslcur = mysqli_fetch_array ($sqlcur)){
			$exrate=$hslcur['Rate'];
		}*/
        $exrate = 1;
		
		$slsprice=$bprice;
		//$qrymaster="select * from supcatalogue where trim(Prtno) ='$partno' and Owner_Comp='$comp' " ;

		$qrymaster="select Prtnm as ITDSC, supcatalogue.* 
		, (select supnm from supmas where supmas.supno = supcatalogue.Supcd  and supcatalogue.Owner_Comp = supmas.Owner_Comp ) as supname
		from supcatalogue where  trim(ordprtno) ='$partno'  and Owner_Comp='$comp' ";
		
		//$desc=$qrymaster;
		$sqlmaster=mysqli_query($msqlcon,$qrymaster);
		if($hslmaster = mysqli_fetch_array ($sqlmaster)){
			$partdes=$hslmaster['ITDSC'];
			$lot=$hslmaster['LotSize'];
			$supno=$hslmaster['Supcd'];
			$supnm=$hslmaster['supname'];
			//$ittyp=$hslmaster['ITTYP'];
			//$itcat=$hslmaster['ITCAT'];
			if($lot == null || $lot == 0){
				$err='1';
				$desc=/*'Error : Lot size was not found. Please contact DENSO PIC'*/get_lng($_SESSION["lng"], "E0057");
			}else{
				$sisa=$qty%$lot;
				if($sisa==0){
					require('supgetDueDate.php');
					
					if(strtoupper($oecus)!='Y'){
						if($ordertype=='Normal'){
								$normalDueArray=getDueDate();
								$duedate_format1=$normalDueArray[0];
								$duedate_format2=$normalDueArray[1];
								$duedate_format3=$requestDueArray[2];
								$err=$normalDueArray[2];
								$desc=$normalDueArray[3];
						}
						else if($ordertype=='Request'){
							if($action =='edit'){
								$duedate_format2=$duedate;
							}
							else{
								$requestDueArray=GetSupplierDueDate($supno);
								$duedate_format1=$requestDueArray[0];
								$duedate_format2=$requestDueArray[1];
								$duedate_format3=$requestDueArray[2];
								$err=$requestDueArray[3];
								$desc=$requestDueArray[4];
							}
						}
				
					} else {  //oe
						if($shipment=='A'){
						//add 45 days
							$addDays=45;	
						}else{
							$addDays=60;	
						}
						$duedate_format1= date('Ymd', strtotime("+".$addDays." days"));
						$duedate_format2= date('d/m/Y', strtotime("+".$addDays." days"));	
						$duedate_format3= date('d-m-Y', strtotime("+".$addDays." days"));	
	
					}
					//require('sendemail.php');
					//require 'supcheckStock.php';
                    /*
					require('supcheckStock.php');
					$warningArray=checkSupStock($partno,$qty,$ordertype);
					if($warningArray[0]=='E'){
						$err='1';
						$desc=$warningArray[1]; 
						//sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered non-stock item","zhaoyi@denso.com.sg");
					}
					*/
					
					require('supcheckMTO.php');
					$warningArray=checkMtoSup($partno,$ordertype,$supno);
					if($warningArray[0]=='E'){
						$err='1';
						$desc=$warningArray[1]; 
						//sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered a MTO item","zhaoyi@denso.com.sg");
					}
					
					if($err!='1'){
					$disc=0;
					$dlrdisc=0;
					$vttlprice=number_format($qty*$bprice,2,".",",");
					//$vttlex=number_format($bprice*$qty*$exrate,2,".",",");
					//$desc=$partdes."||".$curcd."||".$bprice."||".$vttlprice."||".$vttlex."||".$duedate_format2."||".$supno."||".$supnm;		
					$desc=$partdes."||".$curcd."||".$bprice."||".$vttlprice."||1||".$duedate_format2."||".$supno."||".$supnm;	
					// Check in tmp table
					$query2="select * from ".$table. " where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."' and Owner_Comp='$comp' ";
					//$desc .=  $query2;
					$sql2=mysqli_query($msqlcon,$query2);
					if($hasil2 = mysqli_fetch_array ($sql2)){
						if($action=='add'){
							$desc=get_lng($_SESSION["lng"], "E0023")/*'Error: Order Part No is found already'*/ ;
						} else{
							$query4="update ".$table. " set qty=".$qty ."  where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."' and Owner_Comp='$comp' ";
							//$desc =  $query4;
							mysqli_query($msqlcon,$query4);
						}
					}else{
						if($ordertype == "Request"){
							$ordtype = "R";
						}
							$query3="insert into ".$table. " (CUST3, orderno,orderdate,cusno,partno,partdes,ordstatus"
							.",qty,CurCD, bprice, SGCurCD, SGPrice,disc,dlrdisc,slsprice,Corno"
							.",DueDate, DlrCurCD, DlrPrice, OECus, Shipment,Owner_Comp,supno) "
							."values('$cusno','$orderno','$YMD','$shpno','$partno','$partdes','$ordtype'
							,$qty, '$curcd', $bprice,'SD', $exrate, $disc, $dlrdisc,$slsprice, '$corno'
							, '$duedate_format1', '$curcddlr', $bpricedlr, '$oecus', '$shipment','$comp','$supno')";
							// /$desc = $query3;
							mysqli_query($msqlcon,$query3);
				
	
						}
					}
					
				}else{
					//$desc=/*"Error: Order Not in Lot Size!, Lot Size=".number_format($lot).*/get_lng($_SESSION["lng"], "E0001");
					$desc= get_lng($_SESSION["lng"], "E0001").number_format($lot);
	
				}
			}
			
		}else{
			$desc=/*"Error: Item master was not found. Please contact DSTH"*/get_lng($_SESSION["lng"], "E0002");
		}
	} else{
		$desc=/*'Error: Sales Price was not found. Please contact DSTH'*/get_lng($_SESSION["lng"], "E0002");	
	
	}
	//echo ">>".$query3;
	echo $desc;
	
}
?>
