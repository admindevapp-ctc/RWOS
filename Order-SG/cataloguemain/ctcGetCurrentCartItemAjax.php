<?php

session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkuser_permission('../../login.php');

$customerNo = ctc_get_session_cusno();
$comp = ctc_get_session_comp();

$result = ctc_get_current_cart_item($customerNo, $comp);

echo json_encode($result);

