<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

	require('../db/conn.inc');


	$comp = ctc_get_session_comp(); // add by CTC

	$qrydue="select * from supsc003pr where yrmon='".date('Ym')."' and Owner_Comp='$comp' ";
	$cday=date('d');
	$sqldue=mysqli_query($msqlcon,$qrydue);

	$desc='';
	
    if(mysqli_num_rows($sqldue)){
        $dueArr = mysqli_fetch_array ($sqldue);
        $calcd = $dueArr['calcd'];
    }
	else{
        $err='1';
        $desc=get_lng($_SESSION["lng"], "E0026")/*'Error: Calender was not found, Please contact DSTH'*/;
		
	}
	echo $desc;
	// return $duedate_f1 .">" .$duedate_f2.">" .$duedate_f3.">" .$err.">" .$desc;
?>