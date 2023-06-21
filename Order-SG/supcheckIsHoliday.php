<?php
session_start();
require_once('../language/Lang_Lib.php');
require('db/conn.inc');
require_once('./../core/ctc_init.php'); // add by CTC

$selected = trim($_GET['selected']);
$parts = explode('-', $selected);
$desc = '';
$comp = ctc_get_session_comp(); // add by CTC

$qryIsHoliday = "select * from supsc003pr where yrmon='" . $parts[2] . $parts[1] . "' and Owner_Comp='$comp' ";

$sqlResult = mysqli_query($msqlcon,$qryIsHoliday);
if ($ArrayDue = mysqli_fetch_array($sqlResult)) {
    $calcd = $ArrayDue['calcd'];
    $todayIsWrkDay = substr($calcd, ($parts[0] - 1), 1); // check today is holiday
    if ($todayIsWrkDay == 1) {
        
    } else {
        $desc = "Error : " .get_lng($_SESSION["lng"], "E0035")/*'Error: Selected Due Date on DENSO Holiday'*/;
    }
} else {
    $desc = "Error : " .get_lng($_SESSION["lng"], "E0006")/*'Error: Calender was not found, Please contact DSTH'*/;
}
echo $desc;
?>