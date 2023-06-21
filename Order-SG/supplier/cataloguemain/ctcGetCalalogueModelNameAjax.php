<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$supno=$_SESSION['supno'];

$carMaker = (string) filter_input(INPUT_POST, 'CarMaker');
$result = ctc_get_supno_supcatalogue_modelname($carMaker,$supno);

echo json_encode($result);

