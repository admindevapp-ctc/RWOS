<?php
session_start();
require_once('../language/Lang_Lib.php');
require('db/conn.inc');
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

$customerNo = trim($_REQUEST['customerNo']);
$result = '';
$query = "SELECT ship_to_cd as shipCd , ship_to_nm as shipNm FROM `shiptoma` where  trim(Cusno) ='" . $customerNo . "' and Owner_Comp='$comp' order by ship_to_cd asc";
$sqlResult = mysqli_query($msqlcon,$query);
$oJsonResult = array(); // add by CTC
while ($axQuery = mysqli_fetch_array($sqlResult)) {
    $shipCd = $axQuery['shipCd'];
    $shipNm = $axQuery['shipNm'];
    $oJsonResult[] = array('shipCd' => $shipCd, 'shipNm' => $shipNm);
}
$result = json_encode($oJsonResult);
echo $result;

?>