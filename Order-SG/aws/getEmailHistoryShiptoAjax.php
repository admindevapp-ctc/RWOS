<?php
session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');
require('../db/conn.inc');

$owner_comp = ctc_get_session_comp(); // add by CTC


// get email List from table shiptoma -- start --
if(isset($_POST['cusNo']) && isset($_POST['shipToCd']) && isset($_POST['orderNo']) && isset($_POST['corno']) ){
  $cusno = trim($_POST['cusNo']);
  $shipToCd = trim($_POST['shipToCd']);
  $orderNo = trim($_POST['orderNo']);
  $corNo = trim($_POST['corno']);
  $result = '';
  $query = "select * from `awscusmas` st where  trim(st.cusno2) ='" . $cusno .  "' 
   and Owner_Comp='$owner_comp' and st.ship_to_cd2 = '".$shipToCd."'";
  $sqlResult = mysqli_query($msqlcon,$query);
  while ($axQuery = mysqli_fetch_array($sqlResult)) {
      $prsn_mail_add1 = $axQuery['mail_add1'];
      $prsn_mail_add2 = $axQuery['mail_add2'];
      $prsn_mail_add3 = $axQuery['mail_add3'];
      $oJsonResult[] = array('prsn_mail_add1' => $prsn_mail_add1,'prsn_mail_add2' => $prsn_mail_add2,'prsn_mail_add3' => $prsn_mail_add3);
  }
  $result = json_encode($oJsonResult);
  echo $result;
}
// get email List from table shiptoma -- end --

?>
