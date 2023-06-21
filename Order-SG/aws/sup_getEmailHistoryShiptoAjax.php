<?php
session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');
require('../db/conn.inc');

$owner_comp = ctc_get_session_comp(); // add by CTC
// get email List from table shiptoma -- start --
if(isset($_POST['cusNo']) && isset($_POST['shipToCd']) && isset($_POST['orderNo']) && isset($_POST['corno']) && isset($_POST['supno']) ){
  $cusno = trim($_POST['cusNo']);
  $shipToCd = trim($_POST['shipToCd']);
  $orderNo = trim($_POST['orderNo']);
  $corNo = trim($_POST['corno']);
  $supno = trim($_POST['supno']);
  
  $result = '';
  //$query = "select * from `shiptoma` st where  trim(st.Cusno) ='" . $cusno . "' and st.ship_to_cd = '".$shipToCd."' and Owner_Comp='$owner_comp' and supno = '$supno'";
  $query = "select * from supmas where  Owner_Comp='$owner_comp' and supno = '$supno'";
  $sqlResult = mysqli_query($msqlcon,$query);
  //echo $query;

  while ($axQuery = mysqli_fetch_array($sqlResult)) {
    $prsn_mail_add1 = $axQuery['email1'];
    $prsn_mail_add2 = $axQuery['email2'];
    $oJsonResult[] = array('prsn_mail_add1' => $prsn_mail_add1,'prsn_mail_add2' => $prsn_mail_add2);
}
  $result = json_encode($oJsonResult);
  echo $result;
}
// get email List from table shiptoma -- end --

?>
