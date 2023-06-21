<?php 
function checkLimitedUrgentOrderQty($shpno,$qty,$partno){
	require_once('../../core/ctc_init.php'); // add by CTC
	require('../db/conn.inc');
	require_once('../../language/Lang_Lib.php');
	
	$comp = ctc_get_session_comp(); // add by CTC

	$qrycusmas="select * from cusmas where cusno= '$shpno' and Owner_Comp='$comp'";
	//echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$custype='';
	if($cusArray = mysqli_fetch_array ($sqlcusmas)){
		$custype=$cusArray['Custype'];
	}
	$today=date('Ymd');
	$qryOrderedQty="select sum(qty) as total from orderdtl where cusno='$shpno' and orderdate='$today' and partno='$partno' and Owner_Comp='$comp' ";
	//echo $qryOrderedQty;
	$sqlOrderedQty=mysqli_query($msqlcon,$qryOrderedQty);		
	$total=0;
	if($qtyArray = mysqli_fetch_array ($sqlOrderedQty)){
		$total=$qtyArray['total'];
	}
	
	//echo "total:".$total;
	
	$error='';
	$message='';
	//echo $custype;
	if($custype=='D'){
		if($total>5 || $qty>5){
			$error='E';
			$message=get_lng($_SESSION["lng"], "E0029")/*'Error: Ordered Qty should be less than 5 or try again after 10:00 AM'*/;
		}
		else if ($qty>5-$total){
			$error='E';
			$message=get_lng($_SESSION["lng"], "E0030").(5-$total).get_lng($_SESSION["lng"], "E0031")/*'Error: You are only allowed to order '.(5-$total).' or try again after 10:00 AM'*/;
		}
	}
	else if($custype=='A'){
		if($total>20 || $qty>20){
			$error='E';
			$message=get_lng($_SESSION["lng"], "E0032")/*'Error: Ordered Qty should be less than 20 for Part Dealer'*/;
		}
		else if ($qty>20-$total){
			$error='E';
			$message=get_lng($_SESSION["lng"], "E0030").(20-$total).get_lng($_SESSION["lng"], "E0031")/*'Error: You are only allowed to order '.(20-$total).' or try again after 10:00 AM'*/;
		}
		
	}
	return array($error,$message);
}
?>