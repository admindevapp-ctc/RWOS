<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkadmin_permission('../../../login.php');

$delete_id = (int) filter_input(INPUT_POST, 'Delete_id');

if ($delete_id > 0) {
    $result = ctc_delete_catalogue_by_id($delete_id);
}

echo json_encode($result);
