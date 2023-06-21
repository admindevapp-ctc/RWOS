<?php 

function checkSupStock($partno,$qty,$ordertype,$supno){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');

	$comp = ctc_get_session_comp(); // add by CTC
	
	$error='';
	$message='';
	$stockqty1=0;
	$stockqty2=0;
	$stockqty=0;
	
	$qry1="select * from supstock where partno='".$partno."' and Owner_Comp='".$comp."' and supno ='".$supno."'";
	$qry1Result=mysqli_query($msqlcon,$qry1);
	if($stockArray = mysqli_fetch_array ($qry1Result)){
		$stockqty=$stockArray['StockQty'];
		if($stockqty >= $qty){
			//$msg.= "Stock Availability: <font color=green>Yes</font> <br/>";
		}
		else{
			if($ordertype=='Urgent'){
				$error="E";
				$message=get_lng($_SESSION["lng"], "E0025")/*"Error: You are ordering a non-stock item. Please contact DSTH"*/;
			}
			else{
				$error="W";
				$message= get_lng($_SESSION["lng"], "W0026")/*"Warning: You are ordering a non-stock item, DSTH will contact you shortly"*/;
		}
		}
	}
	else{
		
		$error="W";
		$message= get_lng($_SESSION["lng"], "W0027")/*"Warning: You are ordering a non-stock item, DSTH will contact you shortly"*/;
			
	
	}
	 return array($error, $message);	
}



?>