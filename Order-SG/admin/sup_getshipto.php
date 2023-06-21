<?php
session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require('../db/conn.inc');
$comp = ctc_get_session_comp();

$sql = "select ship_to_cd as id from shiptoma  where cusno = {$_GET['cusid']}  and Owner_comp='".$comp."' ";
$query = mysqli_query($msqlcon, $sql);
 
$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}


echo json_encode($json);
//echo $sql;
?>