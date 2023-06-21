<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC

require_once('../../language/Lang_Lib.php');

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
		$_SESSION['awstable'];
		$awstable=$_SESSION['awstable'];
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
		$comp = ctc_get_session_comp(); // add by CTC
		
		
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	}
}else{	
	header("Location:../../login.php");
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
	$action=trim($_GET['action']);
	$shpno=trim($_GET['shpno']);
	$oecus=trim($_GET['oecus']);
	$shipment=trim($_GET['shipment']);
	$qty=$_GET['qty'];
	$ordertype = trim($_GET['ordertype']);
	$shpCd = trim($_GET['shpCd']);
	$chk_tf = trim($_GET['chktf']);
	$chk_tf = 0; // fix not check TF for AWS
	require('../db/conn.inc');
	$YMD=date('Ymd');
	$err='';
	
	$setdayQry="select * from duedate where ordtype='U' and Owner_Comp='$comp'";  // edit by CTC
	$setdaysql=mysqli_query($msqlcon,$setdayQry);
	$time="";
	if($result = mysqli_fetch_array ($setdaysql)){
		$time=$result['setday'];
	}
	
	// check order
	if($action =='edit'){
			$queryold="select * from orderdtl where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."' and Owner_Comp='$comp'";  // edit by CTC
			//echo $query2;
			$sqlold=mysqli_query($msqlcon,$queryold);
			if($hasilold = mysqli_fetch_array ($sqlold)){
				$oldqty=$hasilold['qty'];
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
	// if($comp == "IN0"){
		// if(strtoupper($route)=='N'){
			// $query="select * from sellpriceaws where trim(Itnbr) = '$partno' and cusno= '$shpno' and Owner_Comp='$comp' ";
			// $flag='2';
		// }else{
			// if($chk_tf != '1'){
				// $query="select * from sellprice where trim(Itnbr) = '$partno' and cusno= '$shpno' and Owner_Comp='$comp' and Shipto='$shpCd' ";
			// }else{
				// $query ="SELECT sellprice.*, b.SLS_PRCE,b.SLS_AMNT,b.BS_PRCE,b.DSCNT_RATIO FROM sellprice LEFT JOIN tf_snd_web_item_ma_tk0 b on sellprice.Cusno = b.CST_CD AND sellprice.Itnbr = b.CST_ORDR_ITEM_NO_DSP AND sellprice.Owner_Comp = b.OWNER_COMP AND sellprice.Shipto = b.SHP_TO_CD WHERE TRIM(Itnbr) = '$partno' AND sellprice.cusno = '$shpno' AND sellprice.Owner_Comp = '$comp' AND sellprice.Shipto = '$shpCd' ";
			// }
			
		// }
		
	// }
	// else{
		$query="select distinct awsexc.*   
        from awsexc 
        left join awscusmas on awscusmas.cusgrp = awsexc.cusgrp 
            and awscusmas.Owner_comp = awsexc.Owner_comp and awscusmas.cusno1 = awsexc.cusno1 
        where trim(awsexc.itnbr) = '$partno'  and awsexc.sell = '1' 
            and awsexc.Owner_comp = '$comp'
            and awscusmas.cusno2 =  '$shpno'  ";
	// }
	
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
		if($flag=='1'){
			// if($comp != "IN0"){
				$curcd=$hasil['curr'];
				$bprice=$hasil['price'];
				$curcddlr=$hasil['curr'];
				$bpricedlr=$hasil['price'];
			// }
			// else{
				// $curcd=$hasil['CurCD'];
				// $bprice=$hasil['Price'];
				// $curcddlr=$hasil['CurCD'];
				// $bpricedlr=$hasil['Price'];
			// }
			// $qty = $hasil['qty'];
            $bprice = !empty($bprice) ? $bprice : "NULL";
            $bpricedlr = !empty($bpricedlr) ? $bpricedlr : "NULL";
			$slsamnt = $chk_tf =='1' ? $hasil['SLS_AMNT'] : 0;
			$disc_ratio = $chk_tf =='1' ? $hasil['DSCNT_RATIO'] : 0;
		}else{
			$curcd=$hasil['CurCDAWS'];
			$bprice=$hasil['PriceAWS'];
			$curcddlr=$hasil['CurCD'];
			$bpricedlr=$hasil['Price'];
            $bprice = !empty($bprice) ? $bprice : "NULL";
            $bpricedlr = !empty($bpricedlr) ? $bpricedlr : "NULL";
			$slsamnt = $chk_tf =='1' ? $hasil['SLS_AMNT'] : 0;
			$disc_ratio = $chk_tf =='1' ? $hasil['DSCNT_RATIO'] : 0;
            
		}
		
		$qrycur="select * from excrate  where trim(CurCD) = '$curcd' and EfDateFr<='$YMD' and Owner_Comp='$comp' order by EfDateFr desc ";
		$sqlcur=mysqli_query($msqlcon,$qrycur);		
		if($hslcur = mysqli_fetch_array ($sqlcur)){
			$exrate=$hslcur['Rate'];
		}
		
		$slsprice=$bprice;
		if(ctc_get_session_erp() =='0'){
			$query="select itdsc as ITDSC, (select Lotsize from bm008pr where trim(ITNBR) = '$partno' and Owner_Comp='$comp') as Lotsize 
            from qbm008pr where salavl='YES' and trim(prtno) = '$partno' and Owner_Comp='$comp' limit 1";
		}else{
			$query="select ITDSC,Lotsize from bm008pr where trim(ITNBR) = '$partno' and Owner_Comp='$comp'";
		}
		$sqlmaster=mysqli_query($msqlcon,$query);
		if($hslmaster = mysqli_fetch_array ($sqlmaster)){
			$partdes=$hslmaster['ITDSC'];
			$lot=$hslmaster['Lotsize'];
			//$ittyp=$hslmaster['ITTYP'];
			//$itcat=$hslmaster['ITCAT'];
			if($lot == null || $lot == 0){
				$err='1';
				$desc=/*'Error : Lot size was not found. Please contact DENSO PIC'*/get_lng($_SESSION["lng"], "E0057");
			}else{
				$sisa=$qty%$lot;
				if($sisa==0){
					require('getDueDate.php');
					require('getUrgentDueDate.php');
					
					if(strtoupper($oecus)!='Y'){
								/*require('getRequestDueDate.php');
								$requestDueArray=getRequestedDueDate();
								$duedate_format1=$requestDueArray[0];
								$duedate_format2=$requestDueArray[1];
								$err=$requestDueArray[3];
								$desc=$requestDueArray[4];*/
								$duedate_format1 = trim($_GET['passDueDate']);
					
					} else {  //oe
						if($shipment=='A'){
						//add 45 days
							$addDays=45;	
						}else{
							$addDays=60;	
						}
						$duedate_format1= date('Ymd', strtotime("+".$addDays." days"));
						$duedate_format2= date('d/m/Y', strtotime("+".$addDays." days"));	
	
					}
					if(ctc_get_session_erp() == 0){
						require('checkPhaseOut.php');
						$errArray=checkPhaseOut($partno);
						if($errArray[0]=='E'){
							$err='1';
							$desc=/*'Error : This is a Phase Out Part. Please contact DENSO PIC'*/get_lng($_SESSION["lng"], "E0058");
						}
					}
					//require('sendemail.php');
					require('checkStock.php');
					$warningArray=checkStock($partno,$qty,$ordertype);
					if($warningArray[0]=='E'){
						$err='1';
						$desc=$warningArray[1]; 
						//sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered non-stock item","zhaoyi@denso.com.sg");
					}
					
					require('checkMTO.php');
					$warningArray=checkMto($partno,$ordertype);
					if($warningArray[0]=='E'){
						$err='1';
						$desc=$warningArray[1]; 
						//sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered a MTO item","zhaoyi@denso.com.sg");
					}
					
					if($err!='1'){
						$disc=0;
						$dlrdisc=0;
						$vttlprice=number_format($qty*$bprice,2,".",",");
						$vttlex=number_format($bprice*$qty*$exrate,2,".",",");
						if($chk_tf != '1'){
							$desc=$partdes."||".$curcd."||".number_format($bprice,2,".",",")."||".$vttlprice."||".$vttlex."||".$duedate_format2;		
						}else{
							$vttlprice = number_format($qty*$slsamnt,2,".",",");
							$desc=$partdes."||".$curcd."||".number_format($bprice,2,".",",")."||".$vttlprice."||".$vttlex."||".$duedate_format2;		
					}
					
					// Check in tmp table
					$query2="select * from ".$awstable. " where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."' and Owner_Comp='$comp' ";
					
					$sql2=mysqli_query($msqlcon,$query2);
					if($hasil2 = mysqli_fetch_array ($sql2)){
						if($action=='add'){
							$desc=get_lng($_SESSION["lng"], "E0023")/*'Error: Order Part No is found already'*/ ;
						} else{
							$query4="update ".$awstable. " set qty=".$qty ." where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."' and Owner_Comp='$comp' ";
							mysqli_query($msqlcon,$query4);
                           // echo $query4;
						}
					}else{
							$query3="insert into ".$awstable. " (CUST3, orderno,orderdate,cusno,partno,partdes,ordstatus,qty,CurCD, bprice, SGCurCD
                                , SGPrice,disc,dlrdisc,slsprice,Corno,DueDate, DlrCurCD, DlrPrice, OECus
                                , Shipment,Owner_Comp) values('$cusno','$orderno','$YMD','$shpno','$partno'
                                ,'$partdes','$ordertype',$qty, '$curcd', $bprice,'SD', '$exrate'
                                , $disc, $dlrdisc,$slsprice, '$corno', '$duedate_format1', '$curcddlr'
                                , $bpricedlr, '$oecus', '$shipment','$comp')";
							//echo $query3;
							mysqli_query($msqlcon,$query3);
				
	
						}
					}
					
				}else{
					//$desc=/*"Error: Order Not in Lot Size!, Lot Size=".number_format($lot).*/get_lng($_SESSION["lng"], "E0001");
					$desc= get_lng($_SESSION["lng"], "E0001").number_format($lot);
	
				}
			}
			
		}else{

			//$desc=/*"Error: Item master was not found. Please contact DSTH"*/get_lng($_SESSION["lng"], "E0002");
		}
	} else{
		$desc=/*'Error: Sales Price was not found. Please contact DSTH'*/get_lng($_SESSION["lng"], "E0002");	
	
	}
	echo $desc;
	
}
?>
