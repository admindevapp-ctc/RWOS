<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$cusno = ctc_get_session_cusno();
$result = ctc_get_supcatalogue_carmaker_forcus($cusno);
echo json_encode($result);


//echo $result;