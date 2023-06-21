<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$catMaker = (string) filter_input(INPUT_POST, 'CatMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$brandName = (string) filter_input(INPUT_POST, 'Brand');
$modelCode = (string) filter_input(INPUT_POST, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_POST, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_POST, 'SubModelName');
$suppliercode=$_SESSION['supno'];

$result = ctc_get_supcatalogue_sub_category($catMaker, $modelName, $brandName, $modelCode, $subCatMaker, $subModelName, $suppliercode);

echo json_encode($result);

