<?php

function ctc_add_order_partno($partno, $orderno, $corno, $action, $shpno, $oecus, $shipment, $qty, $ordertype, $passDueDate) {
    $today = getdate();
    $hour = $today['hours'];
    $min = $today['minutes'];
    $sec = $today['seconds'];
    $currenttime = $hour . ":" . $min . ":" . $sec;
    $YMD = date('Ymd');
    $err = '';

    $ctcdb = new ctcdb();
    $table = ctc_get_session_tablename();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $setdayQry = "select * from duedate where ordtype='U' and Owner_Comp='$comp'";  // edit by CTC
    $setday = $ctcdb->db->prepare($setdayQry);
    $setday->execute();
    $result = $setday->fetchAll(PDO::FETCH_ASSOC);
    $time = "";
    if ($result[0]) {
        $time = $result[0]['setday'];
    }

    // check order
    if ($action == 'edit') {
        //echo $query2;
        $queryold = "select * from orderdtl where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp'";  // edit by CTC
        $sqlold = $ctcdb->db->prepare($queryold);
        $sqlold->execute();
        $hasilold = $sqlold->fetchAll(PDO::FETCH_ASSOC);
        if ($hasilold[0]) {
            $oldqty = $hasilold[0]['qty'];
        }
    }

    $qrycusmas = "select * from cusmas where cusno= '$cusno' and Owner_Comp='$comp' ";
    $sqlcusmas = $ctcdb->db->prepare($qrycusmas);
    $sqlcusmas->execute();
    $hslcusmas = $sqlcusmas->fetchAll(PDO::FETCH_ASSOC);

    if ($hslcusmas[0]) {
        $cusgr = $hslcusmas[0]['CusGr'];
        $route = $hslcusmas[0]['route'];
        $oecus = $hslcusmas[0]['OECus'];
    }

    $flag = '1';
    if (strtoupper($route) == 'N') {
        $query = "select * from sellpriceaws where trim(Itnbr) = '$partno' and cusno= '$cusno' and Owner_Comp='$comp' ";
        $flag = '2';
    } else {
        $query = "select * from sellprice where trim(Itnbr) = '$partno' and cusno= '$cusno' and Owner_Comp='$comp' ";
    }

    $sql = $ctcdb->db->prepare($query);
    $sql->execute();
    $hasil = $sql->fetchAll(PDO::FETCH_ASSOC);
    if ($hasil[0]) {
        if ($flag == '1') {
            $curcd = $hasil[0]['CurCD'];
            $bprice = $hasil[0]['Price'];
            $curcddlr = $hasil[0]['CurCD'];
            $bpricedlr = $hasil[0]['Price'];
        } else {
            $curcd = $hasil[0]['CurCDAWS'];
            $bprice = $hasil[0]['PriceAWS'];
            $curcddlr = $hasil[0]['CurCD'];
            $bpricedlr = $hasil[0]['Price'];
        }

        $qrycur = "select * from excrate where trim(CurCD) = '$curcd' and EfDateFr<='$YMD' and Owner_Comp='$comp' order by EfDateFr desc ";
        $sqlcur = $ctcdb->db->prepare($qrycur);
        $sqlcur->execute();
        $hslcur = $sqlcur->fetchAll(PDO::FETCH_ASSOC);
        if ($hslcur[0]) {
            $exrate = $hslcur[0]['Rate'];
        }

        $slsprice = $bprice;

        $qrymaster = "select * from BM008pR where trim(ITNBR) ='$partno' and Owner_Comp='$comp' ";
        $sqlmaster = $ctcdb->db->prepare($qrymaster);
        $sqlmaster->execute();
        $hslmaster = $sqlmaster->fetchAll(PDO::FETCH_ASSOC);
        if ($hslmaster[0]) {
            $partdes = $hslmaster[0]['ITDSC'];
            $lot = $hslmaster[0]['Lotsize'];
            $ittyp = $hslmaster[0]['ITTYP'];
            $itcat = $hslmaster[0]['ITCAT'];
            $sisa = $qty % $lot;
            if ($sisa == 0) {
                if (strtoupper($oecus) != 'Y') {
                    if ($ordertype == 'Normal') {
                        $normalDueArray = getDueDate();
                        $duedate_format1 = $normalDueArray[0];
                        $duedate_format2 = $normalDueArray[1];
                        $err = $normalDueArray[2];
                        $desc = $normalDueArray[3];
                    } else if ($ordertype == 'Urgent') {
                        $urgentDueArray = getUrgentDueDate($time);
                        $duedate_format1 = $urgentDueArray[0];
                        $duedate_format2 = $urgentDueArray[1];
                        $err = $urgentDueArray[2];
                        $desc = $urgentDueArray[3];
                        if (strtotime($currenttime) < strtotime($time)) {
                            $errArr = checkLimitedUrgentOrderQty($cusno, $qty, $partno);
                            if ($errArr[0] == 'E') {
                                $err = '1';
                                $desc = $errArr[1];
                            }
                        }
                    } else if ($ordertype == 'Request') {
                        /* require('getRequestDueDate.php');
                          $requestDueArray=getRequestedDueDate();
                          $duedate_format1=$requestDueArray[0];
                          $duedate_format2=$requestDueArray[1];
                          $err=$requestDueArray[3];
                          $desc=$requestDueArray[4]; */
                        $duedate_format1 = trim($passDueDate);
                    }
                } else {  //oe
                    if ($shipment == 'A') {
                        //add 45 days
                        $addDays = 45;
                    } else {
                        $addDays = 60;
                    }
                    $duedate_format1 = date('Ymd', strtotime("+" . $addDays . " days"));
                    $duedate_format2 = date('d/m/Y', strtotime("+" . $addDays . " days"));
                }
                if(ctc_get_session_erp() == 0){
                    $errArray = checkPhaseOut($partno);
                    if ($errArray[0] == 'E') {
                        $err = '1';
                        $desc = /* 'Error : This is a Phase Out Part. Please contact DENSO PIC' */get_lng($_SESSION["lng"], "E0058");
                    }
                }
                //require('sendemail.php');
                $warningArray = checkStock($partno, $qty, $ordertype);
                if ($warningArray[0] == 'E') {
                    $err = '1';
                    $desc = $warningArray[1];
                    //sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered non-stock item","zhaoyi@denso.com.sg");
                }

                $warningArray = checkMto($partno, $ordertype);
                if ($warningArray[0] == 'E') {
                    $err = '1';
                    $desc = $warningArray[1];
                    //sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered a MTO item","zhaoyi@denso.com.sg");
                }

                if ($err != '1') {
                    $disc = 0;
                    $dlrdisc = 0;
                    $vttlprice = number_format($qty * $bprice, 2, ".", ",");
                    $vttlex = number_format($bprice * $qty * $exrate, 2, ".", ",");
                    $desc = $partdes . "||" . $curcd . "||" . $bprice . "||" . $vttlprice . "||" . $vttlex . "||" . $duedate_format2;
                    // Check in tmp table
                    $query2 = "select * from " . $table . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp' ";
//                    echo $query2;
                    $sql2 = $ctcdb->db->prepare($query2);
                    $sql2->execute();
                    $hasil2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
                    if ($hasil2[0]) {
                        if ($action == 'add') {
                            $desc = get_lng($_SESSION["lng"], "E0023")/* 'Error: Order Part No is found already' */;
                        } else {
                            // sum qty
                            $qty += $hasil2[0]['qty'];
                            $query4 = "update " . $table . " set qty=" . $qty . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp' ";
//                            mysqli_query($msqlcon, $query4);
                            $sql4 = $ctcdb->db->prepare($query4);
                            $sql4->execute();
                        }
                    } else {
                        $query3 = "insert into " . $table . " (CUST3, orderno,orderdate,cusno,partno,partdes
                        ,ordstatus,qty,CurCD, bprice, SGCurCD, SGPrice,disc,dlrdisc,slsprice,Corno
                        ,DueDate, DlrCurCD, DlrPrice, OECus, Shipment,Owner_Comp) values('$cusno','$orderno','$YMD' ,'$cusno','$partno','$partdes'
                        ,'$ordertype',$qty, '$curcd', $bprice,'SD', $exrate , $disc, $dlrdisc,$slsprice, '$corno', '$duedate_format1', '$curcddlr', $bpricedlr, '$oecus', '$shipment','$comp')";
//                        echo $query3;
//                        mysqli_query($msqlcon, $query3);
                        $sql3 = $ctcdb->db->prepare($query3);
                        $sql3->execute();
                    }
                }
            } else {
                //$desc=/*"Error: Order Not in Lot Size!, Lot Size=".number_format($lot).*/get_lng($_SESSION["lng"], "E0001");
                $desc = get_lng($_SESSION["lng"], "E0001") . number_format($lot);
            }
        } else {
            $desc = /* "Error: Item master was not found. Please contact DSTH" */get_lng($_SESSION["lng"], "E0002");
        }
    } else {
        $desc = /* 'Error: Sales Price was not found. Please contact DSTH' */get_lng($_SESSION["lng"], "E0002");
    }

    return $desc;
}

function getDueDate() {
//    require_once('../language/Lang_Lib.php');
    $ctcdb = new ctcdb();

    $qrydue = "select * from sc003pr where yrmon='" . date('Ym') . "'";
    $sqldue = $ctcdb->db->prepare($qrydue);
    $sqldue->execute();
    $dueArr = $sqldue->fetchAll(PDO::FETCH_ASSOC);
    $cday = date('d');
    $found = '';
    $err = '';
    $desc = '';

    $setNdayQry = "select * from duedate where ordtype='N'";
    $setNdaysql = $ctcdb->db->prepare($setNdayQry);
    $setNdaysql->execute();
    $resultN = $setNdaysql->fetchAll(PDO::FETCH_ASSOC);

    $setNday = "";
    if ($resultN[0]) {
        $setNday = $resultN[0]['setday'];
    }
    //echo $qrydue;
    if ($dueArr[0]) {
        $calcd = $dueArr[0]['calcd'];
        $tmrIsWrkDay = substr($calcd, $cday, 1);

        if ($tmrIsWrkDay == 1) {
            $duedate_f1 = date('Ymd', strtotime("+ " . $setNday . " days"));
            $duedate_f2 = date('d/m/Y', strtotime("+ " . $setNday . " days"));
        } else {
            $found = false;
            for ($a = $cday + 1; $a <= strlen($calcd); $a++) {
                if (substr($calcd, $a, 1) == 1) {
                    $diff = $a + $setNday - $cday;
                    $duedate_f1 = date('Ymd', strtotime("+" . $diff . " days"));
                    $duedate_f2 = date('d/m/Y', strtotime("+" . $diff . " days"));
                    $found = true;
                    break;
                }
            }
            //can't find suitable date at current month, search in next month
            if ($found == false) {
                if (date('m') < 12) {
                    $nextMth = date('Y') . date('m') + 1;
                } else {
                    $nextMth = (date('Y') + 1) . "01";
                }
                $qrydue2 = "select * from sc003pr where yrmon='$nextMth'";
                $sqldue2 = $ctcdb->db->prepare($qrydue2);
                $sqldue2->execute();
                $hasildue2 = $sqldue2->fetchAll(PDO::FETCH_ASSOC);
                if ($hasildue2[0]) {
                    $calcd2 = $hasildue2[0]['calcd'];
                    for ($j = 0; $j <= strlen($calcd2); $j++) {
                        if (substr($calcd2, $j, 1) == 1) {
                            $diff2 = $j + strlen($calcd) - $cday + $setNday;
                            $duedate_f1 = date('Ymd', strtotime("+" . $diff2 . " days"));
                            $duedate_f2 = date('d/m/Y', strtotime("+" . $diff2 . " days"));
                            $found = true;
                            break;
                        }
                    }
                } else {
                    $err = '1';
                    $desc = get_lng($_SESSION["lng"], "E0026")/* 'Error: Calender was not found, Please contact DSTH' */;
                }
            }
            //end next month
        }
    } else {
        $err = '1';
        $desc = get_lng($_SESSION["lng"], "E0026")/* 'Error: Calender was not found, Please contact DSTH' */;
    }

    return array($duedate_f1, $duedate_f2, $err, $desc);
}

function getUrgentDueDate($time) {
//    require_once('../language/Lang_Lib.php');
    $ctcdb = new ctcdb();
    //date_default_timezone_set('Asia/Bangkok'); // CDT
    $today = getdate();
    $hour = $today['hours'];
    $min = $today['minutes'];
    $sec = $today['seconds'];
    $currenttime = $hour . ":" . $min . ":" . $sec;
    //$time="10:00:00";
    if (strtotime($currenttime) > strtotime($time)) {
        $urgentDueArray = getDueDate();
        $urgentDue_f1 = $urgentDueArray[0];
        $urgentDue_f2 = $urgentDueArray[1];
        $err = $urgentDueArray[2];
        $desc = $urgentDueArray[3];
    } else {
        $qryIsHoliday = "select * from sc003pr where yrmon='" . date('Ym') . "'";
        $sqlResult = $ctcdb->db->prepare($qryIsHoliday);
        $sqlResult->execute();
        $ArrayDue = $sqlResult->fetchAll(PDO::FETCH_ASSOC);
        if ($ArrayDue[0]) {
            $calcd = $ArrayDue[0]['calcd'];
            $startIndex = date('d'); //date('d',strtotime("-1 day"))
            if ($startIndex == '01') {
                $startIndex = 0;
            } else {
                $startIndex = (int) date('d', strtotime("-1 day"));
            }
            $todayIsWrkDay = substr($calcd, $startIndex, 1); // check today is holiday
            if ($todayIsWrkDay == 1) {
                $urgentDue_f1 = date('Ymd');
                $urgentDue_f2 = date('d/m/Y');
            } else {
                $err = '1';
                $desc = get_lng($_SESSION["lng"], "E0028")/* 'Error: Can not input urgent order on Holiday. Please contact DSTH' */;
            }
        } else {
            $err = '1';
            $desc = get_lng($_SESSION["lng"], "E0029")/* 'Error: Calender was not found, Please contact DSTH' */;
        }
    }
    return array($urgentDue_f1, $urgentDue_f2, $err, $desc);
}

function checkLimitedUrgentOrderQty($cusno, $qty, $partno) {
//    require_once('../language/Lang_Lib.php');

    $ctcdb = new ctcdb();

    $qrycusmas = "select * from cusmas where cusno= '$cusno'";
    $sqlcusmas = $ctcdb->db->prepare($qrycusmas);
    $sqlcusmas->execute();
    $cusArray = $sqlcusmas->fetchAll(PDO::FETCH_ASSOC);
    $custype = '';
    if ($cusArray[0]) {
        $custype = $cusArray[0]['Custype'];
    }
    $today = date('Ymd');
    $qryOrderedQty = "select sum(qty) as total from orderdtl where cusno='$cusno' and orderdate='$today' and partno='$partno'";
    $sqlOrderedQty = $ctcdb->db->prepare($qryOrderedQty);
    $sqlOrderedQty->execute();
    $qtyArray = $sqlOrderedQty->fetchAll(PDO::FETCH_ASSOC);
    $total = 0;
    if ($qtyArray[0]) {
        $total = $qtyArray[0]['total'];
    }

    $error = '';
    $message = '';
    if ($custype == 'D') {
        if ($total > 5 || $qty > 5) {
            $error = 'E';
            $message = get_lng($_SESSION["lng"], "E0029")/* 'Error: Ordered Qty should be less than 5 or try again after 10:00 AM' */;
        } else if ($qty > 5 - $total) {
            $error = 'E';
            $message = get_lng($_SESSION["lng"], "E0030") . (5 - $total) . get_lng($_SESSION["lng"], "E0031")/* 'Error: You are only allowed to order '.(5-$total).' or try again after 10:00 AM' */;
        }
    } else if ($custype == 'A') {
        if ($total > 20 || $qty > 20) {
            $error = 'E';
            $message = get_lng($_SESSION["lng"], "E0032")/* 'Error: Ordered Qty should be less than 20 for Part Dealer' */;
        } else if ($qty > 20 - $total) {
            $error = 'E';
            $message = get_lng($_SESSION["lng"], "E0030") . (20 - $total) . get_lng($_SESSION["lng"], "E0031")/* 'Error: You are only allowed to order '.(20-$total).' or try again after 10:00 AM' */;
        }
    }
    return array($error, $message);
}

function checkPhaseOut($partno) {
    $sub = '';
    $jwb = '';
    $comp = ctc_get_session_comp(); // add by CTC

    $ctcdb = new ctcdb();

    $query = "select *  from phaseout where ITNBR='" . $partno . "' and Owner_Comp='$comp' ";
    $sql = $ctcdb->db->prepare($query);
    $sql->execute();
    $hasil = $sql->fetchAll(PDO::FETCH_ASSOC);
    if ($hasil[0]) {
        if (trim($hasil[0]['SUBITNBR']) != '') {
            $sub = $hasil[0]['SUBITNBR'];
            $jwb = 'S';
            for ($i = 1; $i < 20; $i++) {
                $query1 = "select *  from phaseout where ITNBR='" . $sub . "' and Owner_Comp='$comp' ";
                $sql1 = $ctcdb->db->prepare($query1);
                $sql1->execute();
                $hasil1 = $sql1->fetchAll(PDO::FETCH_ASSOC);
                if ($hasil1[0]) {
                    $sub = $hasil1[0]['SUBITNBR'];
                } else {
                    break;
                }
            }
        } else {
            $sub = $hasil[0]['ITDSC'];
            $jwb = 'E';
        }
    }
    return array($jwb, $sub);
}

function checkStock($partno, $qty, $ordertype) {
//    require_once('../language/Lang_Lib.php');
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp(); // add by CTC

    $error = '';
    $message = '';
    $stockqty1 = 0;
    $stockqty2 = 0;
    $stockqty = 0;

    $qry1 = "select * from availablestock where prtno='" . $partno . "' and Owner_Comp='" . $comp . "' ";
    $qry1Result = $ctcdb->db->prepare($qry1);
    $qry1Result->execute();
    $stockArray = $qry1Result->fetchAll(PDO::FETCH_ASSOC);
    if ($stockArray[0]) {
        $stockqty = $stockArray[0]['qty'];
        if ($stockqty >= $qty) {
            //$msg.= "Stock Availability: <font color=green>Yes</font> <br/>";
        } else {
            if ($ordertype == 'Urgent') {
                $error = "E";
                $message = get_lng($_SESSION["lng"], "E0025")/* "Error: You are ordering a non-stock item. Please contact DSTH" */;
            } else {
                $error = "W";
                $message = get_lng($_SESSION["lng"], "W0026")/* "Warning: You are ordering a non-stock item, DSTH will contact you shortly" */;
            }
        }
    } else {
        $qry2 = "select * from hd100pr where Owner_Comp='" . $comp . "' and prtno='" . $partno . "' and (l1awqy+l2awqy)>=" . $qty;
        $qry2Result = $ctcdb->db->prepare($qry2);
        $qry2Result->execute();
        $count2 = $qry2Result->fetchAll(PDO::FETCH_ASSOC);
        if (count($count2) > 0) {
            //$msg.= "Stock Availability: <font color=green>Yes</font> <br/>";
        } else {
            if ($ordertype == 'Urgent') {
                $error = "E";
                $message = get_lng($_SESSION["lng"], "E0036")/* "Error: You are ordering a non-stock item. Please contact DSTH" */;
            } else {
                $error = "W";
                $message = get_lng($_SESSION["lng"], "W0027")/* "Warning: You are ordering a non-stock item, DSTH will contact you shortly" */;
            }
        }
    }
    return array($error, $message);
}

function checkMto($partno, $ordertype) {
//    require_once('../language/Lang_Lib.php');
    $ctcdb = new ctcdb();
    $error = '';
    $message = '';
    $comp = ctc_get_session_comp(); // add by CTC
    //$mtoQry="select * from mto where prtno='".$partno."'";
    $mtoQry = "select * from bm008pr where MTO='1' and ITNBR='" . $partno . "' and Owner_Comp='" . $comp . "' ";
    $mtoResult = $ctcdb->db->prepare($mtoQry);
    $mtoResult->execute();
    $mtoArray = $mtoResult->fetchAll(PDO::FETCH_ASSOC);
    foreach ($mtoArray as $r) {
        if ($ordertype == 'Request') {
            $error = "W";
            $message = get_lng($_SESSION["lng"], "W0024")/* "Warning: You are ordering a MTO item, DSTH will contact you shortly" */;
        } else {
            $error = "E";
            $message = get_lng($_SESSION["lng"], "E0024")/* "Error: You are ordering a MTO item, DSTH will contact you shortly" */;
        }
    }

    return array($error, $message);
}


function ctc_add_order_partno_aws($partno, $orderno, $corno, $action, $shpno, $oecus, $shipment, $qty, $ordertype, $passDueDate) {
    $today = getdate();
    $hour = $today['hours'];
    $min = $today['minutes'];
    $sec = $today['seconds'];
    $currenttime = $hour . ":" . $min . ":" . $sec;
    $YMD = date('Ymd');
    $err = '';

    $ctcdb = new ctcdb();
    $table = ctc_get_session_tablename();
    $awstable = ctc_get_session_awstable();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $setdayQry = "select * from duedate where ordtype='U' and Owner_Comp='$comp'";  // edit by CTC
    $setday = $ctcdb->db->prepare($setdayQry);
    $setday->execute();
    $result = $setday->fetchAll(PDO::FETCH_ASSOC);
    $time = "";
    if ($result[0]) {
        $time = $result[0]['setday'];
    }

    // check order
    if ($action == 'edit') {
        //echo $query2;
        $queryold = "select * from orderdtl where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp'";  // edit by CTC
        $sqlold = $ctcdb->db->prepare($queryold);
        $sqlold->execute();
        $hasilold = $sqlold->fetchAll(PDO::FETCH_ASSOC);
        if ($hasilold[0]) {
            $oldqty = $hasilold[0]['qty'];
        }
    }

    $qrycusmas = "select * from cusmas where cusno= '$cusno' and Owner_Comp='$comp' ";
    $sqlcusmas = $ctcdb->db->prepare($qrycusmas);
    $sqlcusmas->execute();
    $hslcusmas = $sqlcusmas->fetchAll(PDO::FETCH_ASSOC);

    if ($hslcusmas[0]) {
        $cusgr = $hslcusmas[0]['CusGr'];
        $route = $hslcusmas[0]['route'];
        $oecus = $hslcusmas[0]['OECus'];
    }

    $flag = '1';
    
	// if($comp == "IN0"){
        // $query="select * from sellprice 
        // where trim(Itnbr) = '$partno' and cusno= '$cusno' 
        // and Owner_Comp='$comp' limit 1 ";
    // }
    // else{
        if (strtoupper($route) == 'N') {
            $query = "select * from sellpriceaws 
            where trim(Itnbr) = '$partno' and cusno= '$cusno' 
            and Owner_Comp='$comp' ";
            $flag = '2';
        } else {
        // $query = "select * from sellprice where trim(Itnbr) = '$partno' and cusno= '$cusno' and Owner_Comp='$comp' ";

            $query = "select curr as CurCD,price as Price 
            from awsexc 
            left join awscusmas on awscusmas.cusgrp = awsexc.cusgrp and awscusmas.cusno1  = awsexc.cusno1
                and awsexc.Owner_Comp = awscusmas.Owner_Comp
            where trim(awsexc.Itnbr) = '$partno'   
            and awscusmas.Owner_comp ='$comp'
                and awscusmas.cusno2 =  '$cusno'";
        }
    // }
   

    $sql = $ctcdb->db->prepare($query);
    $sql->execute();
    $hasil = $sql->fetchAll(PDO::FETCH_ASSOC);
    // if ($comp == "IN0") {
        // $curcd = $hasil[0]['CurCD'];
        // $bprice = $hasil[0]['Price'];
        // $curcddlr = $hasil[0]['CurCD'];
        // $bpricedlr = $hasil[0]['Price'];
    // }
    // else{
        if ($flag == '1') {
            if ($hasil[0]) {
                $curcd = $hasil[0]['CurCD'];
                $bprice = $hasil[0]['Price'];
                $curcddlr = $hasil[0]['CurCD'];
                $bpricedlr = $hasil[0]['Price'];
            }
            else{
                $curcd = "";
                $bprice = "NULL";
                $curcddlr = "";
                $bpricedlr =  "NULL";
            }
        } else {
            if ($hasil[0]) {
                $curcd = $hasil[0]['CurCDAWS'];
                $bprice = $hasil[0]['PriceAWS'];
                $curcddlr = $hasil[0]['CurCD'];
                $bpricedlr = $hasil[0]['Price'];
            }
            else{
                $curcd = "";
                $bprice = "NULL";
                $curcddlr = "";
                $bpricedlr =  "NULL";
            }
        }
    // }
        if($curcd != ""){
            $qrycur = "select * from excrate where trim(CurCD) = '$curcd' and EfDateFr<='$YMD' and Owner_Comp='$comp' order by EfDateFr desc ";
            $sqlcur = $ctcdb->db->prepare($qrycur);
            $sqlcur->execute();
            $hslcur = $sqlcur->fetchAll(PDO::FETCH_ASSOC);
            if ($hslcur[0]) {
                $exrate = $hslcur[0]['Rate'];
            }
        }
        else{
            $exrate="NULL";
        }
        $bprice = strlen($bprice) > 0 ? $bprice : "NULL" ;
        $slsprice =  strlen($bprice) > 0 ? $bprice : "NULL";
        $bpricedlr =  strlen($bpricedlr) > 0 ? $bpricedlr : "NULL";

        $qrymaster = "select * from bm008pr where trim(ITNBR) ='$partno' and Owner_Comp='$comp' ";
        $sqlmaster = $ctcdb->db->prepare($qrymaster);
       
        $sqlmaster->execute();
        $hslmaster = $sqlmaster->fetchAll(PDO::FETCH_ASSOC);
        if ($hslmaster[0]) {
            $partdes = $hslmaster[0]['ITDSC'];
            $lot = $hslmaster[0]['Lotsize'];
            $ittyp = $hslmaster[0]['ITTYP'];
            $itcat = $hslmaster[0]['ITCAT'];
            $sisa = $qty % $lot;
            if ($sisa == 0) {
                if (strtoupper($oecus) != 'Y') {
                    if ($ordertype == 'Normal') {
                        $normalDueArray = getDueDate();
                        $duedate_format1 = $normalDueArray[0];
                        $duedate_format2 = $normalDueArray[1];
                        $err = $normalDueArray[2];
                        $desc = $normalDueArray[3];
                    } else if ($ordertype == 'Urgent') {
                        $urgentDueArray = getUrgentDueDate($time);
                        $duedate_format1 = $urgentDueArray[0];
                        $duedate_format2 = $urgentDueArray[1];
                        $err = $urgentDueArray[2];
                        $desc = $urgentDueArray[3];
                        if (strtotime($currenttime) < strtotime($time)) {
                            $errArr = checkLimitedUrgentOrderQty($cusno, $qty, $partno);
                            if ($errArr[0] == 'E') {
                                $err = '1';
                                $desc = $errArr[1];
                            }
                        }
                    } else if ($ordertype == 'Request') {
                        /* require('getRequestDueDate.php');
                          $requestDueArray=getRequestedDueDate();
                          $duedate_format1=$requestDueArray[0];
                          $duedate_format2=$requestDueArray[1];
                          $err=$requestDueArray[3];
                          $desc=$requestDueArray[4]; */
                        $duedate_format1 = trim($passDueDate);
                    }
                } else {  //oe
                    if ($shipment == 'A') {
                        //add 45 days
                        $addDays = 45;
                    } else {
                        $addDays = 60;
                    }
                    $duedate_format1 = date('Ymd', strtotime("+" . $addDays . " days"));
                    $duedate_format2 = date('d/m/Y', strtotime("+" . $addDays . " days"));
                }
                if(ctc_get_session_erp() == 0){
                    $errArray = checkPhaseOut($partno);
                    if ($errArray[0] == 'E') {
                        $err = '1';
                        $desc = /* 'Error : This is a Phase Out Part. Please contact DENSO PIC' */get_lng($_SESSION["lng"], "E0058");
                    }
                }
                //require('sendemail.php');
                $warningArray = checkStock($partno, $qty, $ordertype);
                if ($warningArray[0] == 'E') {
                    $err = '1';
                    $desc = $warningArray[1];
                    //sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered non-stock item","zhaoyi@denso.com.sg");
                }

                $warningArray = checkMto($partno, $ordertype);
                if ($warningArray[0] == 'E') {
                    $err = '1';
                    $desc = $warningArray[1];
                    //sendmail("zhaoyi@denso.com.sg",'ziaur.r@denso.co.th',"Warning!You have ordered a MTO item","zhaoyi@denso.com.sg");
                }

                if ($err != '1') {
                    $disc = 0;
                    $dlrdisc = 0;
                    $vttlprice = number_format($qty * $bprice, 2, ".", ",");
                    $vttlex = number_format($bprice * $qty * $exrate, 2, ".", ",");
                    //$desc = $partdes . "||" . $curcd . "||" . $bprice . "||" . $vttlprice . "||" . $vttlex . "||" . $duedate_format2;
                    // Check in tmp table
                    $query2 = "select * from " . $awstable . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp' ";
                 //  echo $query2;
                    $sql2 = $ctcdb->db->prepare($query2);
                    $sql2->execute();
                    $hasil2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
                    if ($hasil2[0]) {
                        if ($action == 'add') {
                            $desc = get_lng($_SESSION["lng"], "E0023")/* 'Error: Order Part No is found already' */;
                        } else {
                            // sum qty
                            $qty += $hasil2[0]['qty'];
                            $query4 = "update " . $awstable . " set qty=" . $qty . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp' ";
                            mysqli_query($msqlcon, $query4);
                            $sql4 = $ctcdb->db->prepare($query4);
                            $sql4->execute();
                        }
                    } else {
                        $query3 = "
						INSERT INTO `" . $awstable. "`(
							CUST3,
							orderno,
							orderdate,
							cusno,
							partno,
							partdes,
							ordstatus,
							qty,
							CurCD,
							bprice,
							SGCurCD,
							SGPrice,
							disc,
							dlrdisc,
							slsprice,
							Corno,
							DueDate,
							DlrCurCD,
							DlrPrice,
							OECus,
							Shipment,
							Owner_Comp
						)
						VALUES(
							'$cusno',
							'$orderno',
							'$YMD',
							'$cusno',
							'$partno',
							'$partdes',
							'".substr($ordertype, 0, 1)."',
							'$qty',
							'$curcd',
							$bprice,
							'SD',
							'$exrate',
							'$disc',
							'$dlrdisc',
							'$slsprice',
							'$corno',
							'$duedate_format1',
							'$curcddlr',
							$bpricedlr,
							'$oecus',
							'$shipment',
							'$comp'
						)";
                        
                        mysqli_query($msqlcon, $query3);
                        $sql3 = $ctcdb->db->prepare($query3);
                        $sql3->execute();
                    }
                }
            } else {
                //$desc=/*"Error: Order Not in Lot Size!, Lot Size=".number_format($lot).*/get_lng($_SESSION["lng"], "E0001");
                $desc = get_lng($_SESSION["lng"], "E0001") . number_format($lot);
            }
        } else {
            $desc = /* "Error: Item master was not found. Please contact DSTH" */get_lng($_SESSION["lng"], "E0002");
        }
   // } else {
    //    $desc = /* 'Error: Sales Price was not found. Please contact DSTH' */get_lng($_SESSION["lng"], "E0002");
    //}
//echo $desc;
    return $desc;
}