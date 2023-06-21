<?php

session_start();
require_once('../../core/ctc_init.php'); // add by CTC

function getRequestedDueDate()
{
	require('../db/conn.inc');
	require_once('../../language/Lang_Lib.php');
	
	$comp = ctc_get_session_comp(); // add by CTC
	$dealer = $_SESSION['dealer'];
	$qrydue = "select * from supsc003pr where yrmon='" . date('Ym') . "' and Owner_Comp='$comp' ";
	$cday = date('d');
	$sqldue = mysqli_query($msqlcon, $qrydue);
	$found = '';
	$err = '';
	$desc = '';
	$holiday_chk = '';
	$setRdayQry = "select * from awsduedate where ordtype='R' and Owner_Comp='$comp'  AND awsduedate.cusno = '" . $dealer . "'";
	$setRdaysql = mysqli_query($msqlcon, $setRdayQry);
	if(mysqli_num_rows($setRdaysql) <= 0){
		$err = '1';
		$desc = "1st Customer's leadtime setting not found.";
	}else{
		// $setRday = "";
		if ($resultR = mysqli_fetch_array($setRdaysql)) {
			$setRday = $resultR['setday'];
			$holiday_chk = $resultR['holiday_st'];
		}
		if ($dueArr = mysqli_fetch_array($sqldue)) {
			$calcd = $dueArr['calcd']; 
			if($holiday_chk == 1){
				 // $setRday = 210;
				$due_date_aws = date('Ymd', strtotime("+ " . $setRday . " days"));
				$new_Ym  = date('Ym' , strtotime($due_date_aws));
				$new_d  = date('d' , strtotime($due_date_aws));
				$new_m  = date('m' , strtotime($due_date_aws));
				$new_Y  = date('Y' , strtotime($due_date_aws));
				$qrydue2 = "select * from supsc003pr where yrmon='$new_Ym' and Owner_Comp='$comp' ";
				$sqldue2 = mysqli_query($msqlcon, $qrydue2);
				if ($arrayDue2 = mysqli_fetch_array($sqldue2)) {
					$calcd2 = $arrayDue2['calcd'];
				
					$arr_calcd = str_split($calcd2);
					$remappedArray = array_combine(range(1, count($arr_calcd)), $arr_calcd);
					$i = $new_d;
					while($i<= sizeof($remappedArray)){
						if($remappedArray[$i] == 1){
							$new_d2 = sprintf("%02d", $i);
							$date = date($new_Y.$new_m.$new_d2);
							// echo '                ';
							$requestDue_f1 = date('Ymd', strtotime( date($date)  ));
							$requestDue_f2 = date('d/m/Y', strtotime( date($date)  ));
							$requestDue_f3 = date('d-m-Y', strtotime( date($date) ));
							$found = true;
							break;
						}else{
							$i++;
						}
						$found = false;
					}
					
				}else{
					
				}
				//can't find suitable date at current month, search in next month
				if ($found == false) {
					if ($new_m+1 <= 12) {
						$nextMth = $new_Y . $new_m + 1;
					} else {
						$nextMth = ($new_Y + 1) . "01";
					}
					$qrydue2 = "select * from supsc003pr where yrmon='$nextMth' and Owner_Comp='$comp' limit 1";
					$sqldue2 = mysqli_query($msqlcon, $qrydue2);
					if ($arrayDue2 = mysqli_fetch_array($sqldue2)) {
						$calcd2 = $arrayDue2['calcd'];
						$arr_calcd = str_split($calcd2);
						$due_date_aws = date('Ymd', strtotime($nextMth.'01'));
						$new_Ym  = date('Ym' , strtotime($due_date_aws));
						$new_d  = date('d' , strtotime($due_date_aws));
						$new_m  = date('m' , strtotime($due_date_aws));
						$new_Y  = date('Y' , strtotime($due_date_aws));
						$remappedArray = array_combine(range(1, count($arr_calcd)), $arr_calcd);
						// print_r($remappedArray);
						echo $i = intval($new_d);
						while($i<= sizeof($remappedArray)){
							// echo $i;
							$new_d2 = sprintf("%02d", $i);
							$date = date($new_Y.$new_m.$new_d2);
							 // echo $i;
							if($remappedArray[$i] == 1){
								
								$requestDue_f1 = date('Ymd', strtotime( date($date)  ));
								$requestDue_f2 = date('d/m/Y', strtotime( date($date)  ));
								$requestDue_f3 = date('d-m-Y', strtotime( date($date) ));
								$found = true;
								break;
							}else{
								
								$i++;
							}
							$found = false;
						}
					} else {

						$err = '1';
						$desc = get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
					}
				}
				//end next month

					
					
				
			}else{
				$requestDue_f1 = date('Ymd', strtotime("+ " . $setRday . " days"));
				$requestDue_f2 = date('d/m/Y', strtotime("+ " . $setRday . "  days"));
				$requestDue_f3 = date('d-m-Y', strtotime("+ " . $setRday . "  days"));
				$holiday_chk = $holiday_chk;
			}
			
			
		} else {
			$err = '1';
			$desc = get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
		}
	}
	
	return array($requestDue_f1, $requestDue_f2, $requestDue_f3, $err, $desc,$holiday_chk);
}
?>