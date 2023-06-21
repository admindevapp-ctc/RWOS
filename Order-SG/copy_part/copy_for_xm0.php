<?php
include 'config/config_copy_part_XM0.php';

include 'sendmail/sendmail_copy.php';
include 'copy_part.php';
include 'copy_sup_part.php';
$owner_comp; // owner comp from config file

$num_rows_del= $num_rows_del + $num_rows_del_sup;
$num_rows_inst = $num_rows_inst + $num_rows_inst_sup;
if($allquerypass && $allquerypass_sup){
	test_send_s($owner_comp,$num_rows_inst,$num_rows_del);
}else{
	test_send_f($owner_comp,$num_rows_inst,$num_rows_del);
}
?>