<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkadmin_permission('../../../login.php');

$catMaker = (string) filter_input(INPUT_POST, 'CatMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$modelCode = (string) filter_input(INPUT_POST, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_POST, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_POST, 'SubModelName');
$brandName = (string) filter_input(INPUT_POST, 'Brand');

$result = ctc_get_catalogue_sub_category($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName, $brandName);

echo json_encode($result);

