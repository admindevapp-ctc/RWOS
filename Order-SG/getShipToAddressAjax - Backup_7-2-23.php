<?php
session_start();
require_once('../language/Lang_Lib.php');
require('../language/conn.inc');
require_once('./../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC
$cusno = trim($_REQUEST['cusno']);
$shipToCd = trim($_REQUEST['shipToCd']);
$result = '';
$query = "select ship_to_nm,adrs1,adrs2,adrs3,pstl_cd,comp_tel_no from `shiptoma` where trim(ship_to_cd) =trim('".$shipToCd."') and trim(Cusno) = trim('".$cusno."') and Owner_Comp='$comp'";  // edit by CTC
$sqlResult = mysqli_query($msqlcon,$query);
while ($axQuery = mysqli_fetch_array($sqlResult)) {
    $ship_to_nm = $axQuery['ship_to_nm'];
    $adrs1 = $axQuery['adrs1'];
    $adrs2 = $axQuery['adrs2'];
    $adrs3 = $axQuery['adrs3'];
    $pstl_cd = $axQuery['pstl_cd'];
    $comp_tel_no = $axQuery['comp_tel_no'];
    $oJsonResult = array('ship_to_nm' => $ship_to_nm, 'adrs1' => $adrs1, 'adrs2' => $adrs2, 'adrs3' => $adrs3, 'pstl_cd' => $pstl_cd, 'comp_tel_no' => $comp_tel_no);
    $result = json_encode($oJsonResult);
echo $result;
}
?>