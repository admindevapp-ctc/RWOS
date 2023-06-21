<?php 

function getUrgentDueDate($time){
	require_once('./../core/ctc_init.php'); // add by CTC
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');
	//date_default_timezone_set('Asia/Bangkok'); // CDT
	$today = getdate();
	$hour = $today['hours'];
	$min = $today['minutes'];
	$sec = $today['seconds'];
	$currenttime=$hour.":".$min.":".$sec;
	//$time="10:00:00";
	
	$comp = ctc_get_session_comp();

	if(strtotime($currenttime)>strtotime($time)){
		$urgentDueArray=getDueDate();
		$urgentDue_f1=$urgentDueArray[0];
		$urgentDue_f2=$urgentDueArray[1];
		$err=$urgentDueArray[2];
		$desc=$urgentDueArray[3];
	}else {
		$qryIsHoliday="select * from sc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
		$sqlResult=mysqli_query($msqlcon,$qryIsHoliday);
		if($ArrayDue = mysqli_fetch_array ($sqlResult)){
			$calcd = $ArrayDue['calcd'];
			$startIndex = date('d'); //date('d',strtotime("-1 day"))
			if($startIndex =='01'){
				$startIndex=0;
			}
			else {
				$startIndex=(int)date('d',strtotime("-1 day"));
			}
			$todayIsWrkDay = substr($calcd, $startIndex , 1); // check today is holiday
			if ($todayIsWrkDay==1){
				$urgentDue_f1= date('Ymd');
				$urgentDue_f2= date('d/m/Y');
			}else{
				$err='1';
				$desc=get_lng($_SESSION["lng"], "E0028")/*'Error: Can not input urgent order on Holiday. Please contact DSTH'*/;
			}
		}else {
			$err='1';
			$desc=get_lng($_SESSION["lng"], "E0029")/*'Error: Calender was not found, Please contact DSTH'*/;
		}

	}
	return array($urgentDue_f1,$urgentDue_f2,$err,$desc);
}
?>