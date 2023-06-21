<?php
session_start();
require_once('../language/Lang_Lib.php');
require('../language/conn.inc');
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC
$cusno = trim($_REQUEST['cusno']);
$shipToCd = trim($_REQUEST['shipToCd']);
$result = '';
$query = "select ship_to_cd2,ship_to_adrs1,ship_to_adrs2,ship_to_adrs3
from `awscusmas` 
where trim(ship_to_cd2) =trim('".$shipToCd."') and trim(cusno2) = trim('".$cusno."')
 and Owner_Comp='$comp'";  // edit by CTC
 //echo $query;
$sqlResult = mysqli_query($msqlcon,$query);
while ($axQuery = mysqli_fetch_array($sqlResult)) {
    $ship_to_nm = $axQuery['ship_to_cd2'];
    $adrs1 = $axQuery['ship_to_adrs1'];
    $adrs2 = $axQuery['ship_to_adrs2'];
    $adrs3 = $axQuery['ship_to_adrs3'];
    $oJsonResult = array('ship_to_nm' => $ship_to_nm, 'adrs1' => $adrs1, 'adrs2' => $adrs2, 'adrs3' => $adrs3);
    $result = json_encode($oJsonResult);
echo $result;
}
?>