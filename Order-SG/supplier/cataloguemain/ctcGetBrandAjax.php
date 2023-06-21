<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$supno=$_SESSION['supno'];

$carMaker = (string) filter_input(INPUT_POST, 'CarMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$result = ctc_get_supno_supbrand( $carMaker ,$modelName, $supno);

echo json_encode($result);

