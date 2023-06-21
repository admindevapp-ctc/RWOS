<?php
session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
require_once('../../language/Lang_Lib.php');

ctc_checkuser_permission('../../login.php');

include "../crypt.php";

$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();
$cusnm = ctc_get_session_cusnm();

//echo $action;
$vaction = trim($_GET['action']);
$vordno = trim($_GET['ordno']);
$vcorno = trim($_GET['corno']);
$vorddate = trim($_GET['orddate']);
$vsupno = trim($_GET['supno']);
$vshpno = trim($_GET['shpno']);
$shpCd = trim($_GET['shpCd']);
$vordtype = trim($_GET['ordertype']);
$vshipment = trim($_GET['shipment']);
$requestDate = $_GET['requestDate'];
$txtnote = trim($_GET['txtnote']);

$vcusno = $cusno;
$vcusnm = $cusnm;
if($vordtype == "Request"){
    $ordtype = "R";
}
$ctcdb = new ctcdb();
$sql = "select OECus from cusmas where Cusno = '$vcusno' and Owner_Comp='$comp'";
$sth = $ctcdb->db->prepare($sql);
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$hasil = $result[0];

if ($hasil) {
    $oecus = strtoupper($hasil['OECus']);
} else {
    $oecus = 'N';
}
$sql2 = "select * from suporderhdr where Cusno = '" . $vcusno . "' and Corno='" . $vcorno . "' and Owner_Comp='$comp' ";  // edit by CTC
$sth2 = $ctcdb->db->prepare($sql2);
$sth2->execute();
$result2 = $sth2->fetchAll(PDO::FETCH_ASSOC);
$hasil2 = $result2[0];

if (!$hasil2) {
    $sql3 = "select * from suporderhdr where orderno='" . $vordno . "' and Owner_Comp='$comp'";  // edit by CTC
    $sth3 = $ctcdb->db->prepare($sql3);
    $sth3->execute();
    $result3 = $sth3->fetchAll(PDO::FETCH_ASSOC);
    $hasil3 = $result3[0];
    
    if (!$hasil3) {
        if($vsupno != ""){
            $array = explode(",", $vsupno);
            $arraydate = explode(",", $requestDate);
            
             for($i = 0; $i < count($array) -1; $i++)
            {
                $result = ctc_get_supshoppingcart_bysupno($shpCd, $array[$i]);
                
                $parts = explode("-", $arraydate[$i]);
                $passDueDate = $parts[2] . $parts[1] . $parts[0];
                // echo $passDueDate;
            
                foreach ($result as $r) {
                    //echo $r['PartNo'] .">". $vordno.">". $vcorno.">". $action.">". $vshpno.">".$oecus.">". $vshipment.">". $r['qty'], $vordtype.">Date:". $passDueDate.">".$r['supno'].">".$r['shipto'] ."<br/>";
                     ctc_add_suporder_partno($r['PartNo'], $vordno, $vcorno, $action, $vshpno, $oecus, $vshipment, $r['qty'], $ordtype, $passDueDate,$r['supno'], $r['shipto']);
                
                }
            }
        }
       

        ctc_delete_supshoppingcart_by_cusno($cusno);
        echo "suporder_entry.php?" . paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&orddate=$vorddate&corno=$vcorno&shpno=$vshpno&oecus=$oecus&shipment=$vshipment&ordertype=$vordtype&requestDate=$requestDate&shpCd=$shpCd&txtnote=$txtnote");
    } else {
        echo "Error - Denso Order No has found, Close your Internet browser!";
    }
} else {
    echo "Error - PO has already found, Use edit or new PO!";
}
