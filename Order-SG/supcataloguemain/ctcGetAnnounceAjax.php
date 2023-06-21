<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$comp = ctc_get_session_comp();
$date = date('Y-m-d');
$cusno = ctc_get_session_cusno();

$result = ctc_get_supannounce_forcus($comp, $date, $cusno);

echo json_encode($result);
//echo $result;
