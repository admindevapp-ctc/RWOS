<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC
require('db/conn.inc');

$cusno=$_GET['cusno'];
$alias=$_GET['alias'];
$comp = ctc_get_session_comp();

$tblname = $alias . "regimp_supplier";
$query = "delete from " . $tblname . " where cusno ='" . $cusno . "' and Owner_Comp='$comp' ";
mysqli_query($msqlcon,$query);

$table1 = str_replace("regimp", 'ordtmp', $tblname);
$query2 = "delete from " . $table1 . " where cusno ='" . $cusno . "' and Owner_Comp='$comp' ";
mysqli_query($msqlcon,$query2);


?>
<!-- 28/10/2019 Prachaya inphum CTC --end-->