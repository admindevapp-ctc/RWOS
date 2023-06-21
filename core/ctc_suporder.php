<?php

function ctc_add_suporder_partno($partno, $orderno, $corno, $action, $shpno, $oecus, $shipment, $qty, $ordertype, $passDueDate, $supno, $shipto) {
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

	$table1=str_replace("ordtmp",'_sup_ordtmp',$table);
    $_SESSION["tablenamesup"] = $table1;
    //Insert order to temp
    createTempTableOrderSup($table1);


    // check order
    if ($action == 'edit') {
        //echo $query2;
        $queryold = "select * from suporderdtl where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp' and supno = '$supno'";  // edit by CTC
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
    $query = "select * from supprice where partno = '$partno' and Cusno= '$cusno' and Owner_Comp='$comp' and supno = '$supno' and shipto = '$shipto'";
   
    //$desc = $query;
    $sql = $ctcdb->db->prepare($query);
    $sql->execute();
    $hasil = $sql->fetchAll(PDO::FETCH_ASSOC);
    if ($hasil[0]) {
        if ($flag == '1') {
            $curcd = $hasil[0]['curr'];
            $bprice = $hasil[0]['price'];
            $curcddlr = $hasil[0]['curr'];
            $bpricedlr = $hasil[0]['price'];
        } 
        $exrate = 1;
       /* $qrycur = "select * from excrate where trim(CurCD) = '$curcd' and EfDateFr<='$YMD' and Owner_Comp='$comp' order by EfDateFr desc ";
        $sqlcur = $ctcdb->db->prepare($qrycur);
        $sqlcur->execute();
        $hslcur = $sqlcur->fetchAll(PDO::FETCH_ASSOC);
        if ($hslcur[0]) {
            $exrate = $hslcur[0]['Rate'];
        }
*/
        $slsprice = $bprice;

        $qrymaster = "select * from supcatalogue where ordprtno ='$partno' and Owner_Comp='$comp' and Supcd = '$supno'";
        $sqlmaster = $ctcdb->db->prepare($qrymaster);
        $sqlmaster->execute();
        $hslmaster = $sqlmaster->fetchAll(PDO::FETCH_ASSOC);
        if ($hslmaster[0]) {
            $partdes = $hslmaster[0]['Prtnm'];
            $lot = $hslmaster[0]['LotSize'];
            $sisa = $qty % $lot;
            if ($sisa == 0) {
                if (strtoupper($oecus) != 'Y') {
                    if ($ordertype == 'R') {
                        $duedate_format1 = trim($passDueDate);
                    }
                } 
                if ($err != '1') {
                    $disc = 0;
                    $dlrdisc = 0;
                    $vttlprice = number_format($qty * $bprice, 2, ".", ",");
                   // $vttlex = number_format($bprice * $qty * $exrate, 2, ".", ",");
                    //$desc = $partdes . "||" . $curcd . "||" . $bprice . "||" . $vttlprice . "||" . $vttlex . "||" . $duedate_format2;
                    // Check in tmp table
                    $query2 = "select * from " . $table1 . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='".$comp."' and supno = '".$supno."' ";
                    $desc =  $query2 ."<br/>";
                    $sql2 = $ctcdb->db->prepare($query2);
                    $sql2->execute();
                    $hasil2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($hasil2[0]) {
                        
                        if ($action == 'add') {
                            $desc = get_lng($_SESSION["lng"], "E0023")/* 'Error: Order Part No is found already' */;
                        } else {
                            // sum qty
                            $qty += $hasil2[0]['qty'];
                            $query4 = "update " . $table1 . " set qty=" . $qty . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='". $comp ."' and supno = '". $supno ."'";
//                            mysqli_query($msqlcon, $query4);
                            $sql4 = $ctcdb->db->prepare($query4);
                            $sql4->execute();
                        }
                    } else {

                        $query3 = "insert into " . $table1 . " (CUST3, orderno,orderdate,cusno,partno,partdes,ordstatus,qty,CurCD, bprice,"
                        ." SGCurCD, SGPrice,disc,dlrdisc,slsprice,Corno,DueDate, DlrCurCD, DlrPrice,"
                        ." OECus, Shipment,Owner_Comp,supno) values('$cusno','$orderno','$YMD',"
                        ." '$cusno','$partno','$partdes','$ordertype',$qty, '$curcd', $bprice,'SD', "
                        ." $exrate, $disc, $dlrdisc,$slsprice, '$corno', '$duedate_format1', '$curcddlr'"
                        .", $bpricedlr, '$oecus', '$shipment','$comp','$supno')";
                        //$desc = $query3;
                      //  mysqli_query($msqlcon, $query3);
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


function createTempTableOrderSup($tblname){
   
    include('../db/conn.inc');
    $sql = "DESC " . $tblname;
    mysqli_query($msqlcon,$sql);
    if ($msqlcon->errno == 1146) {
        $query2 = "CREATE TABLE " . $tblname . " (
            Owner_Comp varchar(3),
            CUST3 varchar(45),
            orderno varchar(20),
            orderdate varchar(8),
            cusno varchar(8),
            partno varchar(15),
            partdes varchar(100),
            ordstatus varchar(1),
            qty int(11),
            CurCD varchar(2),
            bprice decimal(18,4),
            SGCurCD varchar(2),
            SGPrice decimal(18,8),
            disc int(3),
            dlrdisc int(3),
            slsprice decimal(18,4),
            Corno varchar(20),
            DueDate varchar(8),
            DlvBy varchar(1),
            DlrCurCD varchar(2),
            DlrPrice decimal(18,4),
            OECus varchar(1),
            Shipment varchar(1),
            supno varchar(8),
            PRIMARY KEY (orderno,ordstatus,cusno,partno,Owner_Comp,supno)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        mysqli_query($msqlcon,$query2);
    }
}


function sup_get_ordertempimp($cusno,$imptable){
    $ctcdb = new ctcdb();
    $table = ctc_get_session_tablename();
    $comp = ctc_get_session_comp();


    $sql = " SELECT a.*  , b.*
        ,(select supnm from supmas where a.supno = supmas.supno and a.Owner_Comp = supmas.Owner_Comp ) as supname
        FROM ".$imptable." a
       left join supcatalogue b on a.partno = b.ordprtno and a.Owner_Comp = b.Owner_Comp and a.supno = b.Supcd
        where (ordsts ='E' or ordsts ='W' or ordsts ='R' or ordsts ='') and a.Owner_Comp='$comp' and a.CUST3 = '$cusno'
        limit 1
    ";


    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function ctc_get_supordertemp_bysupno($shipto,$supno,$imptable) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "
        SELECT cat.ID, cat.ordprtno as Prtno, CONCAT( cat.ordprtno, ' - ', cat.Prtnm) AS PartNumber, cat.supcd as supno
        , (select supnm  from supmas where supmas.Owner_Comp = cat.Owner_Comp AND cat.Supcd = supmas.supno ) as supname
        , cat.ordprtno AS PartNo, cat.Prtnm AS PrtDescr, price.price, price.curr, cat.Lotsize, shop.qty, cat.ModelCode
        , supref.shipto
        FROM ".$imptable."  as shop
            JOIN supcatalogue AS cat ON shop.partno = cat.ordprtno and shop.Owner_Comp = cat.Owner_Comp
            and shop.supno = cat.Supcd
            JOIN supprice  as price on price.supno =  cat.Supcd and price.Owner_comp = cat.Owner_Comp 
                and price.partno = cat.ordprtno and price.Cusno = shop.cusno 
            JOIN supref on supref.shipto = price.shipto and supref.supno = cat.Supcd and shop.cusno = supref.cusno
            and supref.Owner_comp = shop.Owner_Comp 
        WHERE shop.Owner_Comp = '" . $comp . "' AND  shop.Cusno =  '" . $cusno . "' and cat.Supcd = '".$supno."'
        ORDER BY supno
        limit 1
    ";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function ctc_add_awssuporder_partno($partno, $orderno, $corno, $action, $shpno, $oecus, $shipment, $qty, $ordertype, $passDueDate, $supno, $shipto) {
	
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

	$table1=str_replace("ordtmp",'_sup_awsordtmp',$table);
    $_SESSION["awstablenamesup"] = $table1;
    //Insert order to temp
    createTempTableOrderawsSup($table1);

    // check order
    if ($action == 'edit') {
        //echo $query2;
        $queryold = "select * from suporderdtl where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='$comp' and supno = '$supno'";  // edit by CTC
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
    //$query = "select * from supawsexc where prtno = '$partno' and Cusno= '$cusno' and Owner_Comp='$comp' and supno = '$supno' and shipto = '$shipto'";
    $query = "select supawsexc.prtno, supawsexc.price, supawsexc.curr 
    from awscusmas
    left join supawsexc on awscusmas.cusgrp = supawsexc.cusgrp and awscusmas.cusno1 = supawsexc.cusno1
     and awscusmas.Owner_comp = supawsexc.Owner_Comp and supawsexc.prtno ='$partno' and supawsexc.supcode = '$supno'
    where awscusmas.cusno2 = '$cusno' and awscusmas.Owner_Comp = '$comp'
     and awscusmas.ship_to_cd2 = '$shipto'";
   
    $sql = $ctcdb->db->prepare($query);
    $sql->execute();
    $hasil = $sql->fetchAll(PDO::FETCH_ASSOC);
    if ($hasil[0]) {
		
        if ($flag == '1') {
            $curcd = $hasil[0]['curr'];
            $bprice = $hasil[0]['price'];
            $curcddlr = $hasil[0]['curr'];
            $bpricedlr = $hasil[0]['price'];
        } 
        $exrate = 1;
        $slsprice = $bprice;
        $qrymaster = "select * from supcatalogue where ordprtno ='$partno' and Owner_Comp='$comp' and Supcd = '$supno'";
      if($slsprice == ''){
        $slsprice = "NULL";
      }
      if($bpricedlr == ''){
        $bpricedlr = "NULL";
      }
      if($bprice == ''){
        $bprice = "NULL";
      }
      
      
        $sqlmaster = $ctcdb->db->prepare($qrymaster);
        $sqlmaster->execute();
        $hslmaster = $sqlmaster->fetchAll(PDO::FETCH_ASSOC);
        if ($hslmaster[0]) {
            $partdes = $hslmaster[0]['Prtnm'];
            $lot = $hslmaster[0]['LotSize'];
            $sisa = $qty % $lot;
            if ($sisa == 0) {
                /*if (strtoupper($oecus) != 'Y') {
                    if ($ordertype == 'R') {
                        $duedate_format1 = trim($passDueDate);
                    }
                } */
                if ($err != '1') {
                    $disc = 0;
                    $dlrdisc = 0;
                    $vttlprice = number_format($qty * $bprice, 2, ".", ",");
                   // $vttlex = number_format($bprice * $qty * $exrate, 2, ".", ",");
                    //$desc = $partdes . "||" . $curcd . "||" . $bprice . "||" . $vttlprice . "||" . $vttlex . "||" . $duedate_format2;
                    $query2 = "select * from " . $table1 . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='".$comp."' and supno = '".$supno."' ";
                    $desc =  $query2 ."<br/>";
                    $sql2 = $ctcdb->db->prepare($query2);
                    $sql2->execute();
                    $hasil2 = $sql2->fetchAll(PDO::FETCH_ASSOC);
                    if ($hasil2[0]) {
                        if ($action == 'add') {
                            $desc = get_lng($_SESSION["lng"], "E0023")/* 'Error: Order Part No is found already' */;
                        } else {
                            // sum qty
                            $qty += $hasil2[0]['qty'];
                            $query4 = "update " . $table1 . " set qty=" . $qty . " where trim(partno) ='" . $partno . "' and trim(orderno)='" . $orderno . "' and Owner_Comp='". $comp ."' and supno = '". $supno ."'";
//                            mysqli_query($msqlcon, $query4);
                            $sql4 = $ctcdb->db->prepare($query4);
                            $sql4->execute();
                        }
                    } else {

                        $query3 = "insert into " . $table1 . " (CUST3, orderno,orderdate,cusno,partno,partdes,ordstatus,qty,CurCD, bprice,"
                        ." SGCurCD, SGPrice,disc,dlrdisc,slsprice,Corno,DueDate, DlrCurCD, DlrPrice,"
                        ." OECus, Shipment,Owner_Comp,supno) values('$cusno','$orderno','$YMD',"
                        ." '$cusno','$partno','$partdes','".substr($ordertype, 0, 1)."',$qty, '$curcd', $bprice,'SD', "
                        ." $exrate, $disc, $dlrdisc,$slsprice, '$corno', '$passDueDate', '$curcddlr'"
                        .", $bpricedlr, '$oecus', '$shipment','$comp','$supno')";
                        //$desc = $query3;
                       // echo $query3;
                      //  mysqli_query($msqlcon, $query3);
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


function createTempTableOrderawsSup($tblname){
   
    include('../../db/conn.inc');
	$sql = "DESC " . $tblname;
    mysqli_query($msqlcon,$sql);
    if ($msqlcon->errno == 1146) {
        $query2 = "CREATE TABLE " . $tblname . " (
            Owner_Comp varchar(3),
            CUST3 varchar(45),
            orderno varchar(20),
            orderdate varchar(8),
            cusno varchar(8),
            partno varchar(15),
            partdes varchar(100),
            ordstatus varchar(1),
            qty int(11),
            CurCD varchar(2),
            bprice decimal(18,4),
            SGCurCD varchar(2),
            SGPrice decimal(18,8),
            disc int(3),
            dlrdisc int(3),
            slsprice decimal(18,4),
            Corno varchar(20),
            DueDate varchar(8),
            DlvBy varchar(1),
            DlrCurCD varchar(2),
            DlrPrice decimal(18,4),
            OECus varchar(1),
            Shipment varchar(1),
            supno varchar(8),
            PRIMARY KEY (orderno,ordstatus,cusno,partno,Owner_Comp,supno)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        mysqli_query($msqlcon,$query2);
        if (!mysqli_query($msqlcon,$query2))   {
            return "Error description: " . mysqli_error($msqlcon); 
        }
    }
}