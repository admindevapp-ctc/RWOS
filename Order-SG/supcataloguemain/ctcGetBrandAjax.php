<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$cusno = ctc_get_session_cusno();
$carmaker = (string) filter_input(INPUT_POST, 'CarMaker');
$modelname = (string) filter_input(INPUT_POST, 'ModelName');
$result = ctc_get_supbrand_forcus($cusno,$carmaker,$modelname  );

echo json_encode($result);

//echo $result;