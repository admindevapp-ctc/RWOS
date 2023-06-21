<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$id = (string) filter_input(INPUT_POST, 'ID');
$quantity = (string) filter_input(INPUT_POST, 'Quantity');
$customerNo = ctc_get_session_cusno();
$comp = ctc_get_session_comp();
$dateTime = date('Y-m-d H:i:s');

$result = ctc_save_shoppingcart($customerNo, $comp, $id, $quantity, $dateTime);

echo json_encode($result);

