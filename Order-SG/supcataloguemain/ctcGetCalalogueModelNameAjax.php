<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$cusno = ctc_get_session_cusno();
$carMaker = (string) filter_input(INPUT_POST, 'CarMaker');
$result = ctc_get_supcatalogue_modelname_forcus($carMaker,$cusno);

echo json_encode($result);

//echo $result;