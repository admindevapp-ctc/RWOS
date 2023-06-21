<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

function getRequestedDueDate(){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');
	$comp = ctc_get_session_comp(); // add by CTC

	 $qrydue="select * from sc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
	 $cday=date('d');
	 $sqldue=mysqli_query($msqlcon,$qrydue);
	 $found='';
	 $err='';
	 $desc='';
	 $setRdayQry="select * from duedate where ordtype='R' and Owner_Comp='$comp' ";
		$setRdaysql=mysqli_query($msqlcon,$setRdayQry);
		$setRday="";
		if($resultR = mysqli_fetch_array ($setRdaysql)){
			$setRday=$resultR['setday'];
		}
	 if($dueArr = mysqli_fetch_array ($sqldue)){
		 $calcd = $dueArr['calcd'];
		 
		 $theDayAfterTmrIsWrkDay = substr($calcd, $cday+1, 1);

		 if ($theDayAfterTmrIsWrkDay==1){
			 $requestDue_f1= date('Ymd', strtotime("+ ".$setRday." days"));
			 $requestDue_f2= date('d/m/Y', strtotime("+ ".$setRday."  days"));	
			 $requestDue_f3= date('d-m-Y', strtotime("+ ".$setRday."  days"));	
		 }
		 else{
			 $found=false;
			 for ($a= $cday;$a<=strlen($calcd); $a++){
				 if(substr($calcd, $a+1, 1)==1){
					 $diff=$a + $setRday - $cday;
					 $requestDue_f1= date('Ymd', strtotime("+".$diff." days"));
					 $requestDue_f2= date('d/m/Y', strtotime("+".$diff." days"));
					 $requestDue_f3= date('d-m-Y', strtotime("+".$diff." days"));
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
				 if($arrayDue2 = mysqli_fetch_array ($sqldue2)){
					 $calcd2 = $arrayDue2['calcd'];
					 for ($j= 0 ;$j<=strlen($calcd2); $j++){
						 if(substr($calcd2, $j+1, 1)==1){
							 $diff2=$j + strlen($calcd) - $cday + $setRday;
							 $requestDue_f1= date('Ymd', strtotime("+".$diff2." days"));
							 $requestDue_f2= date('d/m/Y', strtotime("+".$diff2." days"));
							 $requestDue_f3= date('d-m-Y', strtotime("+".$diff2." days"));
							 $found=true;
							 break;
						 }
					 }
				 }
				 else{
					 $err='1';
					 $desc=get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
				 }
			 }
			 //end next month
		 }
	 }
	 else{
		$err='1';
		$desc=get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
	 }
	 return array($requestDue_f1,$requestDue_f2,$requestDue_f3,$err,$desc);
}


function AWSgetRequestedDueDateApp1(){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');

	$comp = ctc_get_session_comp(); // add by CTC

	 $qrydue="select * from sc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
	 $cday=date('d');//today Ex 1
	 $sqldue=mysqli_query($msqlcon,$qrydue);
	 $found='';
	 $err='';
	 $desc='';
	 $setRdayQry="select * from duedate where ordtype='R' and Owner_Comp='$comp' ";
		$setRdaysql=mysqli_query($msqlcon,$setRdayQry);
		$setRday="";
		if($resultR = mysqli_fetch_array ($setRdaysql)){
			$setRday=$resultR['setday'];
		}
	 if($dueArr = mysqli_fetch_array ($sqldue)){
		 $calcd = $dueArr['calcd'];
		 $due = $cday  + $setRday;
		 $theDayAfterTmrIsWrkDay = substr($calcd, $due - 1, 1);// check tmr Ex 2
		

		 if ($theDayAfterTmrIsWrkDay==1){ // tmr is wrk d
			 $requestDue_f1= date('Ymd', strtotime("+ ".$setRday." days")); // today + setday
			 $requestDue_f2= date('d/m/Y', strtotime("+ ".$setRday."  days"));	 // today + setday
			 $requestDue_f3= date('d-m-Y', strtotime("+ ".$setRday."  days"));	 // today + setday
		 }
		 else{
			 $found=false;
			 for ($a= $due;$a<=strlen($calcd); $a++){ // duedate 25 to 31
				
				 if(substr($calcd, $a, 1)==1){ // duedate 25 วันหยุดมั้ย
					 $diff=($a+1)-$cday;// duedate - today;
					 $requestDue_f1= date('Ymd', strtotime("+".$diff." days"));
					 $requestDue_f2= date('d/m/Y', strtotime("+".$diff." days"));
					 $requestDue_f3= date('d-m-Y', strtotime("+".$diff." days"));
					 $found=true;
					 break;
				 }
			 }
			 //can't find suitable date at current month, search in next month
			 if($found==false){
				 $next_month = date('Ym', strtotime("+".($due-$cday)." days"));		
				 $qrydue2="select * from sc003pr where yrmon='$next_month' and Owner_Comp='$comp' ";
				 $sqldue2=mysqli_query($msqlcon,$qrydue2);
				 if($arrayDue2 = mysqli_fetch_array ($sqldue2)){
					$calcd2 = $arrayDue2['calcd'];
					$new_date1 = date('d', strtotime("+".($setRday)." days"));
					while($new_date1 <= strlen($calcd2)){ //modified while cause of code have wrong logic
						if(substr($calcd2, $new_date1 - 1, 1) == 1){
							$requestDue_f1= date('Ymd', strtotime("+".$setRday+$add_day." days"));
							$requestDue_f2= date('d/m/Y', strtotime("+".$setRday+$add_day." days"));
							$requestDue_f3= date('d-m-Y', strtotime("+".$setRday+$add_day." days"));
							$found=true;
							break;
						}else{
							$new_date1 += 1;
							$add_day +=1;
							
						}	
					}
				 }
				 else{
					 $err='1';
					 $desc=get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
				 }
			 }
			 //end next month
		 }
		 //$desc = "Day = ".$cday . ", + setday = ".$due. ", Checkhld = ".$theDayAfterTmrIsWrkDay . ", + diff = ".$diff . ", + diff2 = ".$diff2;
	 }
	 else{
		$err='1';
		$desc=get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
	 }

	 return array($requestDue_f1,$requestDue_f2,$requestDue_f3,$err,$desc);
}


function AWSgetRequestedDueDateApp2(){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');
	
	$comp = ctc_get_session_comp(); // add by CTC
	$cusno=	$_SESSION['cusno'];

	 $query ="select * from awsduedate where  Owner_Comp='$comp' and cusno = '$cusno' and ordtype='R'";
	 $sql=mysqli_query($msqlcon,$query);
	 $chkholidayArr = mysqli_fetch_array ($sql);
	 $checkholiday = $chkholidayArr["holiday_st"];
	 $setRday = $chkholidayArr["setday"];
	 $cday=date('d');//today Ex 1
	 $found='';
	 $err='';
	 $desc='';
	 if($checkholiday == "1"){
		$qrydue="select * from supsc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
		$cday=date('d');//today Ex 1
		$sqldue=mysqli_query($msqlcon,$qrydue);
	
		if($dueArr = mysqli_fetch_array ($sqldue)){
			$calcd = $dueArr['calcd'];
			$due = $cday  + $setRday;
			$theDayAfterTmrIsWrkDay = substr($calcd, $due - 1, 1);// check tmr Ex 2
			

			if ($theDayAfterTmrIsWrkDay==1){ // tmr is wrk d
				$requestDue_f1= date('Ymd', strtotime("+ ".$setRday." days")); // today + setday
				$requestDue_f2= date('d/m/Y', strtotime("+ ".$setRday."  days"));	 // today + setday
				$requestDue_f3= date('d-m-Y', strtotime("+ ".$setRday."  days"));	 // today + setday
			}
			else{
				$found=false;

				for ($a= $due;$a<=strlen($calcd); $a++){ // duedate 25 to 31
					if(substr($calcd, $a, 1)==1){ // duedate 25 วันหยุดมั้ย
						$diff=($a+1)-$cday;// duedate - today;
						$requestDue_f1= date('Ymd', strtotime("+".$diff." days"));
						$requestDue_f2= date('d/m/Y', strtotime("+".$diff." days"));
						$requestDue_f3= date('d-m-Y', strtotime("+".$diff." days"));
						$found=true;
						break;
					}
				}
				//can't find suitable date at current month, search in next month
				if($found==false){
					$next_month = date('Ym', strtotime("+".($due-$cday)." days"));

				 if(date('m')<12){
					  $nextMth=date('Y').date('m')+1;
				 }
				 else {
					 $nextMth=(date('Y')+1)."01";
				 }			
				 $qrydue2="select * from supsc003pr where yrmon='$next_month' and Owner_Comp='$comp' ";
				 $sqldue2=mysqli_query($msqlcon,$qrydue2);
				 if($arrayDue2 = mysqli_fetch_array ($sqldue2)){
					$calcd2 = $arrayDue2['calcd'];
					$new_date1 = date('d', strtotime("+".($setRday)." days"));
					while($new_date1 <= strlen($calcd2)){ //modified while cause of code have wrong logic
						if(substr($calcd2, $new_date1 - 1, 1) == 1){
							$requestDue_f1= date('Ymd', strtotime("+".$setRday+$add_day." days"));
							$requestDue_f2= date('d/m/Y', strtotime("+".$setRday+$add_day." days"));
							$requestDue_f3= date('d-m-Y', strtotime("+".$setRday+$add_day." days"));
							$found=true;
							break;
						}else{
							$new_date1 += 1;
							$add_day +=1;
							
						}	
					}

					 // for ($j= date('d') ;$j<=strlen($calcd2); $j++){
						 // if(substr($calcd2, $j, 1)==1){
							 // $diff2=($j+1) + ($due-strlen($calcd)); 
							 // $requestDue_f1= date('Ymd', strtotime("+".$diff2." days"));
							 // $requestDue_f2= date('d/m/Y', strtotime("+".$diff2." days"));
							 // $requestDue_f3= date('d-m-Y', strtotime("+".$diff2." days"));
							 // $found=true;
							 // break;
						 // }
					 // }
				 }
					else{
						$err='1';
						$desc=get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
					}
				}
				//end next month
			}
			///$desc = "Day = ".$cday . ", + setday = ".$due. ", Checkhld = ".$theDayAfterTmrIsWrkDay . ", + diff = ".$diff . ", + diff2 = ".$diff2;
	
		}
		else{
			$err='1';
			$desc=get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
		}
	 }
	 else{
		$requestDue_f1= date('Ymd', strtotime("+ ".$setRday." days")); // today + setday
		$requestDue_f2= date('d/m/Y', strtotime("+ ".$setRday."  days"));	 // today + setday
		$requestDue_f3= date('d-m-Y', strtotime("+ ".$setRday."  days"));	 // today + setday
	 }
		

	 return array($requestDue_f1,$requestDue_f2,$requestDue_f3,$err,$desc);
}

?>