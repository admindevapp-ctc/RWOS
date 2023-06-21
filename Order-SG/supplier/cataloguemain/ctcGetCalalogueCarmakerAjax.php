<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$supno=$_SESSION['supno'];
$result = ctc_get_supno_supcatalogue_carmaker($supno);
echo json_encode($result);
//echo $result;
