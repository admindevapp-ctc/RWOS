<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$comp = ctc_get_session_comp();
$supno=$_SESSION['supno'];
$date = date('Y-m-d');

$result = ctc_get_supno_supannounce($comp, $date, $supno);

echo json_encode($result);

