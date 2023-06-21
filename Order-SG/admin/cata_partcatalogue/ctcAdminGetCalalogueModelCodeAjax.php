<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkadmin_permission('../../../login.php');

$carMaker = (string) filter_input(INPUT_POST, 'CarMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$result = ctc_get_catalogue_modelcode($carMaker, $modelName);

echo json_encode($result);

