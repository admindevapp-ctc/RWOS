<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkadmin_permission('../../../login.php');

$carMaker = (string) filter_input(INPUT_POST, 'CarMaker');
$result = ctc_get_catalogue_modelname($carMaker);

echo json_encode($result);

