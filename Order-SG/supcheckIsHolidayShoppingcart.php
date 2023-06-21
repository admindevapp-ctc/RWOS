<?php
session_start();
require_once('../language/Lang_Lib.php');
require('db/conn.inc');
require_once('./../core/ctc_init.php'); // add by CTC

$selected = trim($_GET['selected']);
$supno = trim($_GET['supno']);

$parts = explode('-', $selected);
$desc = '';
$comp = ctc_get_session_comp(); // add by CTC


	 $query ="select * from supmas where supno = '$supno' and Owner_Comp='$comp' ";
	 $sql=mysqli_query($msqlcon,$query);
	 $chkholidayArr = mysqli_fetch_array ($sql);
	 $checkholiday = $chkholidayArr["holidayck"];
	 $setNday = $chkholidayArr["duedate"];

	if($checkholiday == "1"){

        $qryIsHoliday = "select * from supsc003pr where yrmon='" . $parts[2] . $parts[1] . "' and Owner_Comp='$comp' ";
        $sqlResult = mysqli_query($msqlcon,$qryIsHoliday);
        if ($ArrayDue = mysqli_fetch_array($sqlResult)) {
            $calcd = $ArrayDue['calcd'];
            $todayIsWrkDay = substr($calcd, ($parts[0] - 1), 1); // check today is holiday
            if ($todayIsWrkDay == 1) {
                
            } else {
                $desc = "Error : " .get_lng($_SESSION["lng"], "E0049")/*'Selected Due Date on Holiday'*/;
            }
        } else {
            $desc = "Error : " .get_lng($_SESSION["lng"], "E0059")/*'Calender was not found, Please contact DENSO PIC'*/;
        }
    }
    echo $desc;
?>