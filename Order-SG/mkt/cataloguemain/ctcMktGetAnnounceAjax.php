<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkadmin_permission('../../../login.php');

$comp = ctc_get_session_comp();
$date = date('Y-m-d');

$result = ctc_get_announce($comp, $date);

echo json_encode($result);

