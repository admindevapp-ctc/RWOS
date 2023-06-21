<?php 

function checkMto($partno,$ordertype){
	require('../db/conn.inc');
	require_once('../../language/Lang_Lib.php');
	$error='';
	$message='';
	$comp = ctc_get_session_comp(); // add by CTC

	//$mtoQry="select * from mto where prtno='".$partno."'";
	if(ctc_get_session_erp() == '0'){
		$mtoQry="select * from mto where prtno='".$partno."' and Owner_Comp='$comp'";  // edit by CTC
	}else{
		$mtoQry="select * from bm008pr where MTO='1' and ITNBR='".$partno."' and Owner_Comp='".$comp."' ";
	}

	$mtoResult=mysqli_query($msqlcon,$mtoQry);
	While($mtoArray = mysqli_fetch_array ($mtoResult)){
		if($ordertype=='Request'){
			$error="W";
			$message=get_lng($_SESSION["lng"], "W0024")/*"Warning: You are ordering a MTO item, DSTH will contact you shortly"*/;	
		}
		else{
			$error="E";
			$message=get_lng($_SESSION["lng"], "E0024")/*"Error: You are ordering a MTO item, DSTH will contact you shortly"*/;	
		}
		
	}

	return array($error, $message);	
}





?>