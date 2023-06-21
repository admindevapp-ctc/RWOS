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
		$setRday = "";
		if ($resultR = mysqli_fetch_array($setRdaysql)) {
			$setRday = $resultR['setday'];
			$holiday_chk = $resultR['holiday_st'];
		}
		if ($dueArr = mysqli_fetch_array($sqldue)) {
			$calcd = $dueArr['calcd']; 
			if($holiday_chk == 1){
				$theDayAfterTmrIsWrkDay = substr($calcd, $cday + 1, 1);
				if ($theDayAfterTmrIsWrkDay == 1) {
					$requestDue_f1 = date('Ymd', strtotime("+ " . $setRday . " days"));
					$requestDue_f2 = date('d/m/Y', strtotime("+ " . $setRday . "  days"));
					$requestDue_f3 = date('d-m-Y', strtotime("+ " . $setRday . "  days"));
				} else {
					$found = false;
					for ($a = $cday; $a <= strlen($calcd); $a++) {
						if (substr($calcd, $a + 1, 1) == 1) {
							$diff = $a + $setRday - $cday;
							$requestDue_f1 = date('Ymd', strtotime("+" . $diff . " days"));
							$requestDue_f2 = date('d/m/Y', strtotime("+" . $diff . " days"));
							$requestDue_f3 = date('d-m-Y', strtotime("+" . $diff . " days"));
							$found = true;
							break;
						}
					}
					//can't find suitable date at current month, search in next month
					if ($found == false) {
						if (date('m') < 12) {
							$nextMth = date('Y') . date('m') + 1;
						} else {
							$nextMth = (date('Y') + 1) . "01";
						}
						$qrydue2 = "select * from supsc003pr where yrmon='$nextMth' and Owner_Comp='$comp' ";
						$sqldue2 = mysqli_query($msqlcon, $qrydue2);
						if ($arrayDue2 = mysqli_fetch_array($sqldue2)) {
							$calcd2 = $arrayDue2['calcd'];
							for ($j = 0; $j <= strlen($calcd2); $j++) {
								if (substr($calcd2, $j + 1, 1) == 1) {
									$diff2 = $j + strlen($calcd) - $cday + $setRday;
									$requestDue_f1 = date('Ymd', strtotime("+" . $diff2 . " days"));
									$requestDue_f2 = date('d/m/Y', strtotime("+" . $diff2 . " days"));
									$requestDue_f3 = date('d-m-Y', strtotime("+" . $diff2 . " days"));
									$found = true;
									break;
								}
							}
						} else {
							$err = '1';
							$desc = get_lng($_SESSION["lng"], "E0027")/*'Error: Calender was not found, Please contact DSTH'*/;
						}
					}
					//end next month
				}
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