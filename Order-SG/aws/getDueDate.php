<?php 

function getDueDate(){
	require_once('../../core/ctc_init.php'); // add by CTC
	require('../db/conn.inc');
	require_once('../../language/Lang_Lib.php');

	$comp = ctc_get_session_comp(); // add by CTC

	 $qrydue="select * from sc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
	 $cday=date('d');
	 $sqldue=mysqli_query($msqlcon,$qrydue);
	 $found='';
	 $err='';
	 $desc='';
	 
	 $setNdayQry="select * from duedate where ordtype='R' and Owner_Comp='$comp' ";
		$setNdaysql=mysqli_query($msqlcon,$setNdayQry);
		$setNday="";
		if($resultN = mysqli_fetch_array ($setNdaysql)){
			$setNday=$resultN['setday'];
		}
	 //echo $qrydue;
	 if($dueArr = mysqli_fetch_array ($sqldue)){
		 $calcd = $dueArr['calcd'];
		 $tmrIsWrkDay = substr($calcd, $cday, 1);

		 if ($tmrIsWrkDay==1){
			 $duedate_f1= date('Ymd', strtotime("+ ".$setNday." days"));
			 $duedate_f2= date('d/m/Y', strtotime("+ ".$setNday." days"));	
		 }
		 else{
			 $found=false;
			 for ($a= $cday+1;$a<=strlen($calcd); $a++){
				 if(substr($calcd, $a, 1)==1){
					 $diff=$a + $setNday - $cday;
					 $duedate_f1= date('Ymd', strtotime("+".$diff." days"));
					 $duedate_f2= date('d/m/Y', strtotime("+".$diff." days"));
					 $found=true;
					 break;
				 }
			 }
			 //can't find suitable date at current month, search in next month
			 if($found==false){
				 if(date('m')<12){
					  $nextMth=date('Y').date('m')+1;
				 }
				 else {
					 $nextMth=(date('Y')+1)."01";
				 }	
				 $qrydue2="select * from sc003pr where yrmon='$nextMth' and Owner_Comp='$comp' ";
				 $sqldue2=mysqli_query($msqlcon,$qrydue2);
				 if($hasildue2 = mysqli_fetch_array ($sqldue2)){
					 $calcd2 = $hasildue2['calcd'];
					 for ($j= 0 ;$j<=strlen($calcd2); $j++){
						 if(substr($calcd2, $j, 1)==1){
							 $diff2=$j + strlen($calcd) - $cday + $setNday;
							 $duedate_f1= date('Ymd', strtotime("+".$diff2." days"));
							 $duedate_f2= date('d/m/Y', strtotime("+".$diff2." days"));
							 $found=true;
							 break;
						 }
					 }
				 }
				 else{
					 $err='1';
					 $desc=get_lng($_SESSION["lng"], "E0026")/*'Error: Calender was not found, Please contact DSTH'*/;
				 }
			 }
			 //end next month
		 }
	 }
	 else{
		$err='1';
		$desc=get_lng($_SESSION["lng"], "E0026")/*'Error: Calender was not found, Please contact DSTH'*/;
	 }
	 
	 return array($duedate_f1,$duedate_f2,$err,$desc);
}



?>