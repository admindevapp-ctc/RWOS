<?php 
session_start();
require_once('../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] != 'denso-sg') {
        $_SESSION['cusno'];
        $_SESSION['cusnm'];
        $_SESSION['redir'];
        $_SESSION['type'];
        $_SESSION['com'];
        $_SESSION['user'];
        $_SESSION['alias'];
        $_SESSION['tablename'];
        $_SESSION['custype'];
        $_SESSION['dealer'];
        $_SESSION['group'];
		$_SESSION['awstable'];
        $cusno = $_SESSION['cusno'];
        $cusnm = $_SESSION['cusnm'];
        $password = $_SESSION['password'];
        $alias = $_SESSION['alias'];
        $table = $_SESSION['tablename'];
		$awstable=$_SESSION['awstable'];
        $type = $_SESSION['type'];
        $custype = $_SESSION['custype'];
        $user = $_SESSION['user'];
        $dealer = $_SESSION['dealer'];
        $group = $_SESSION['group'];
    } else {
        echo "<script> document.location.href='../../" . redir . "'; </script>";
    }
} else {
    header("Location:../../login.php");
}


require('../db/conn.inc');
//echo $action;
$vaction = trim($_GET['action']);
$vordno = trim($_GET['ordno']);
$xcusno = trim($_GET['cusno']);
$vcorno = trim($_GET['corno']);
$vorddate = trim($_GET['orddate']);
$vshpno = trim($_GET['shpno']);
$shpCd=trim($_GET['shpCd']);
$vordtype = trim($_GET['ordertype']);
$vshipment = trim($_GET['shipment']);
$requestDate = $_GET['requestDate'] != null ? trim($_GET['requestDate']) : "";
$txtnote = trim($_GET['txtnote']);

$query = "select OECus from cusmas where Cusno = '$vshpno' and Owner_Comp='$comp' ";  // edit by CTC
$sql = mysqli_query($msqlcon,$query);
if ($hasil = mysqli_fetch_array($sql)) {
    $oecus = strtoupper($hasil['OECus']);
} else {
    $oecus = 'N';
}

switch ($vaction) {

    case 'new':
        $vcusno = $cusno;
        $vcusnm = $cusnm;
        checknew($vcusno, $vcusnm, $vcorno, $vordno, $vshpno, $vorddate, $vaction, $oecus, $vshipment, $vordtype, $requestDate, $shpCd, $txtnote);
        break;

    case 'newAdd':
        $vcusno = $cusno;
        $vcusnm = $cusnm;
        $xaction = "new";
        echo 'newAdd';
        //checknewAdd($vcusno, $vcusnm, $vcorno, $vordno, $vordtype, $vorddate, $vshpno, $xaction, $oecus, $vshipment);
        break;
}

function checknewAdd($vcusno, $vcusnm, $vcorno, $vordno, $vordtype, $vorddate, $vshpno, $action, $oecus, $vshipment) {
	require('../db/conn.inc');
    include "../crypt.php";
    
    $comp = ctc_get_session_comp(); // add by CTC

    $query = "select * from orderhdr where Cust3='" . $cusno . "' and Corno='" . $vcorno . "' and Owner_Comp='$comp'";   // edit by cTC
    $sql = mysqli_query($msqlcon,$query);
    $hasil = mysqli_fetch_array($sql);
    if (!$hasil) {
        $query1 = "select * from orderhdr where Cusno='" . $cusno . "' and Corno='" . $vcorno . "' and Owner_Comp='$comp'"; // edit by CTC
        //echo $query1;
        $sql1 = mysqli_query($msqlcon,$query1);
        $hasil1 = mysqli_fetch_array($sql1);
        if ($hasil1) {
            echo "Error - PO has already found, Use edit or new PO!";
        } else {
            echo "Addorder.php?" . paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&ordtype=$vordtype&orddate=$vorddate&corno=$vcorno&shpno=$vshpno&oecus=$oecus&shipment=$vshipment");
        }
    } else {
        echo "Error - PO has already found, Use edit or new PO!";
    }
}

function checknew($vcusno, $vcusnm, $vcorno, $vordno, $vshpno, $vorddate, $action, $oecus, $vshipment, $vordtype, $requestDate, $shpCd , $txtnote) {
    require('../db/conn.inc');
    include "../crypt.php";
    
    $comp = ctc_get_session_comp(); // add by CTC
    $dealer = $_SESSION['dealer'];
    // Check PO Number
    $query = "select * from awsorderhdr where Cusno = '" . $vshpno . "' and Corno='" . $vcorno . "' and Owner_Comp='$comp' ";  // edit by CTC
    $sql = mysqli_query($msqlcon,$query);
    $hasil = mysqli_fetch_array($sql);
    if (!$hasil) {
        //check PO on order
        $query = "select * from orderhdr where Corno='" . $vcorno . "' and  CUST3 = '" . $dealer . "'  and Owner_Comp='$comp'";  // edit by CTC
        
        $sql = mysqli_query($msqlcon,$query);
        $hasil1 = mysqli_fetch_array($sql);
        if (!$hasil1) {
            $query = "select * from awsorderhdr where orderno='" . $vordno . "' and Owner_Comp='$comp'";  // edit by CTC
            $sql = mysqli_query($msqlcon,$query);
            $hasil2 = mysqli_fetch_array($sql);
            if (!$hasil2) {
                if ($vordtype == "Normal" || $vordtype == "Urgent" || $vordtype == 'Request') {
                    echo "order2_new.php?" . paramEncrypt("action=$action&ordno=$vordno&cusno=$vcusno&orddate=$vorddate&corno=$vcorno&shpno=$vshpno&oecus=$oecus&shipment=$vshipment&ordertype=$vordtype&requestDate=$requestDate&shpCd=$shpCd&txtnote=$txtnote");
                   // echo "order2_new.php";
                } 
            } else {
    
                echo "Error - Denso Order No has found, Close your Internet browser!";
            }
        } else {
            echo "Error - This PO number already exist!" ;
        }
       
    } else {
        echo "Error - This PO number already exist!" ;
    }
}

?>
