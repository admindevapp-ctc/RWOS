<?php 



session_start();
require_once('../core/ctc_init.php'); // add by CTC


function GetSupplierDueDate($supno){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');

	$comp = ctc_get_session_comp(); // add by CTC


	 $query ="select * from supmas where supno = '$supno' and Owner_Comp='$comp' ";
	 $sql=mysqli_query($msqlcon,$query);
	 $chkholidayArr = mysqli_fetch_array ($sql);
	 $checkholiday = $chkholidayArr["holidayck"];
	 $setNday = $chkholidayArr["duedate"];

	if($checkholiday == "1"){

		$qrydue="select * from supsc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
		$cday=date('d');
		$sqldue=mysqli_query($msqlcon,$qrydue);
		$found='';
		$err='';
		$desc='';
		//echo $qrydue;
		if($dueArr = mysqli_fetch_array ($sqldue)){
			$calcd = $dueArr['calcd'];

		
			$tmrIsWrkDay = substr($calcd, $cday, 1);

			if ($tmrIsWrkDay==1){
				$duedate_f1= date('Ymd', strtotime("+ ".$setNday." days"));
				$duedate_f2= date('d/m/Y', strtotime("+ ".$setNday." days"));	
				$duedate_f3= date('d-m-Y', strtotime("+ ".$setNday." days"));	
			}
			else{
				$found=false;
				for ($a= $cday+1;$a<=strlen($calcd); $a++){
					if(substr($calcd, $a, 1)==1){
						$diff=$a + $setNday - $cday;
						$duedate_f1= date('Ymd', strtotime("+".$diff." days"));
						$duedate_f2= date('d/m/Y', strtotime("+".$diff." days"));
						$duedate_f3= date('d-m-Y', strtotime("+".$diff." days"));
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
					$qrydue2="select * from supsc003pr where yrmon='$nextMth' and Owner_Comp='$comp' ";
					$sqldue2=mysqli_query($msqlcon,$qrydue2);
					if($hasildue2 = mysqli_fetch_array ($sqldue2)){
						$calcd2 = $hasildue2['calcd'];
						for ($j= 0 ;$j<=strlen($calcd2); $j++){
							if(substr($calcd2, $j, 1)==1){
								$diff2=$j + strlen($calcd) - $cday + $setNday;
								$duedate_f1= date('Ymd', strtotime("+".$diff2." days"));
								$duedate_f2= date('d/m/Y', strtotime("+".$diff2." days"));
								$duedate_f3= date('d-m-Y', strtotime("+".$diff2." days"));
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
	}
	else{
		$cday=date('d');
		//$diff2=$cday - $setNday; //
		$duedate_f1= date('Ymd', strtotime("+".$setNday." days"));
		$duedate_f2= date('d/m/Y', strtotime("+".$setNday." days"));
		$duedate_f3= date('d-m-Y', strtotime("+".$setNday." days"));
		//$desc = "[checkholiday = 0," . "today=" .$cday . ", addday = ".$setNday . ", Diff = ". $diff2. ", duedate = ".strtotime("+".$diff2." days") . "]";
	}
	 //echo $desc;
	return array($duedate_f1,$duedate_f2,$duedate_f3,$err,$desc);
	// return $duedate_f1 .">" .$duedate_f2.">" .$duedate_f3.">" .$err.">" .$desc;
}


function AWSGetApp1SupplierDueDate($supno){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');

	$comp = ctc_get_session_comp(); // add by CTC


	 $query ="select * from supmas where supno = '$supno' and Owner_Comp='$comp' ";
	 $sql=mysqli_query($msqlcon,$query);
	 $chkholidayArr = mysqli_fetch_array ($sql);
	 $checkholiday = $chkholidayArr["holidayck"];
	 $setNday = $chkholidayArr["duedate"];

	if($checkholiday == "1"){

		$qrydue="select * from supsc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
		$cday=date('d');
		$sqldue=mysqli_query($msqlcon,$qrydue);
		$found='';
		$err='';
		$desc='';
		//echo $qrydue;
		if($dueArr = mysqli_fetch_array ($sqldue)){
			$calcd = $dueArr['calcd'];
			$due = $cday  + $setNday;
			$tmrIsWrkDay = substr($calcd, $due - 1, 1);

			if ($tmrIsWrkDay==1){
				$duedate_f1= date('Ymd', strtotime("+ ".$setNday." days"));
				$duedate_f2= date('d/m/Y', strtotime("+ ".$setNday." days"));	
				$duedate_f3= date('d-m-Y', strtotime("+ ".$setNday." days"));	
			}
			else{
				$found=false;
				for ($a= $due;$a<=strlen($calcd); $a++){
					if(substr($calcd, $a, 1)==1){
						$diff=($a+1) - $cday;
						$duedate_f1= date('Ymd', strtotime("+".$diff." days"));
						$duedate_f2= date('d/m/Y', strtotime("+".$diff." days"));
						$duedate_f3= date('d-m-Y', strtotime("+".$diff." days"));
						$found=true;
						break;
					}
				}
				//can't find suitable date at current month, search in next month
				if($found==false){
					 $next_month = date('Ym', strtotime("+".($due-$cday)." days"));		
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
			$desc=get_lng($_SESSION["lng"], "E0026")/*'Error: Calender was not found, Please contact DSTH'*/;
		}
		$desc = "AWSGetApp1(Day = ".$cday . ", + setday = ".$due. ", Checkhld = ".$tmrIsWrkDay . ", + diff = ".$diff . ", + diff2 = ".$diff2.")";
	}
	else{
		$cday=date('d');
		//$diff2=$cday - $setNday; //
		$duedate_f1= date('Ymd', strtotime("+".$setNday." days"));
		$duedate_f2= date('d/m/Y', strtotime("+".$setNday." days"));
		$duedate_f3= date('d-m-Y', strtotime("+".$setNday." days"));
		//$desc = "[checkholiday = 0," . "today=" .$cday . ", addday = ".$setNday . ", Diff = ". $diff2. ", duedate = ".strtotime("+".$diff2." days") . "]";
	}
	 //echo $desc;
	return array($duedate_f1,$duedate_f2,$duedate_f3,$err,$desc);
	// return $duedate_f1 .">" .$duedate_f2.">" .$duedate_f3.">" .$err.">" .$desc;
}

function AWSGetApp2SupplierDueDate($supno){
	require('db/conn.inc');
	require_once('../language/Lang_Lib.php');

	$comp = ctc_get_session_comp(); // add by CTC
	$cusno=	$_SESSION['cusno'];

	 $query ="select * from awsduedate where  Owner_Comp='$comp' and cusno = '$cusno' and ordtype='R'";
	 $sql=mysqli_query($msqlcon,$query);
	 $chkholidayArr = mysqli_fetch_array ($sql);
	 $checkholiday = $chkholidayArr["holiday_st"];
	 $setNday = $chkholidayArr["setday"];

	if($checkholiday == "1"){

		$qrydue="select * from supsc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
		$cday=date('d');
		$sqldue=mysqli_query($msqlcon,$qrydue);
		$found='';
		$err='';
		$desc='';
		//echo $qrydue;
		if($dueArr = mysqli_fetch_array ($sqldue)){
			$calcd = $dueArr['calcd'];
			$due = $cday  + $setNday;
			$tmrIsWrkDay = substr($calcd, $due - 1, 1);

			if ($tmrIsWrkDay==1){
				$duedate_f1= date('Ymd', strtotime("+ ".$setNday." days"));
				$duedate_f2= date('d/m/Y', strtotime("+ ".$setNday." days"));	
				$duedate_f3= date('d-m-Y', strtotime("+ ".$setNday." days"));	
			}
			else{
				$found=false;
				for ($a= $due;$a<=strlen($calcd); $a++){
					if(substr($calcd, $a, 1)==1){
						$diff=($a+1) - $cday;
						$duedate_f1= date('Ymd', strtotime("+".$diff." days"));
						$duedate_f2= date('d/m/Y', strtotime("+".$diff." days"));
						$duedate_f3= date('d-m-Y', strtotime("+".$diff." days"));
						$found=true;
						break;
					}
				}
				//can't find suitable date at current month, search in next month
				if($found==false){
					 $next_month = date('Ym', strtotime("+".($due-$cday)." days"));		
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
			$desc=get_lng($_SESSION["lng"], "E0026")/*'Error: Calender was not found, Please contact DSTH'*/;
		}
		$desc = "AWSGetApp2(Day = ".$cday . ", + setday = ".$due. ", Checkhld = ".$tmrIsWrkDay . ", + diff = ".$diff . ", + diff2 = ".$diff2 . ")";
	
	}
	else{
		$cday=date('d');
		//$diff2=$cday - $setNday; //
		$duedate_f1= date('Ymd', strtotime("+".$setNday." days"));
		$duedate_f2= date('d/m/Y', strtotime("+".$setNday." days"));
		$duedate_f3= date('d-m-Y', strtotime("+".$setNday." days"));
		//$desc = "[checkholiday = 0," . "today=" .$cday . ", addday = ".$setNday . ", Diff = ". $diff2. ", duedate = ".strtotime("+".$diff2." days") . "]";
	}
	 //echo $desc;
	return array($duedate_f1,$duedate_f2,$duedate_f3,$err,$desc);
	// return $duedate_f1 .">" .$duedate_f2.">" .$duedate_f3.">" .$err.">" .$desc;
}

?>