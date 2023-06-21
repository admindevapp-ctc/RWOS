<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$shoppingList = array();
$shoppingList = filter_input(INPUT_POST, 'shoppingidlist', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$errorCode = '0000';
$message = 'success';
if (count($shoppingList) > 0) {
    $result = ctc_delete_shoppingcart_by_id($shoppingList);
    $errorCode = $result->errorCode;
    $message = $result->message;
} else {
    $errorCode = '1111';
    $message = 'Not found data';
}

$reportClass = new stdClass();
$reportClass->errorCode = $errorCode;
$reportClass->message = $message;

echo json_encode($reportClass);
