<?php

function ctc_get_shoppingcart_table($shipto) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "SELECT shop.ID, CONCAT(bm.ITNBR, ' - ', bm.ITDSC) AS PartNumber, bm.ITNBR AS PartNo, bm.ITDSC AS PrtDescr, sp.Price, sp.CurCD, bm.Lotsize, shop.QTY, cat.ModelCode"
            . " FROM shoppingcart as shop"
            . " LEFT JOIN catalogue AS cat ON shop.ID = cat.ID"
            . " LEFT JOIN (SELECT DISTINCT ITNBR, ITDSC, Lotsize, Owner_Comp FROM bm008pr) AS bm ON cat.ordprtno = bm.ITNBR"
            . " LEFT JOIN (SELECT DISTINCT Itnbr, Price, CurCD FROM sellprice WHERE Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' AND Shipto = '" . $shipto . "') AS sp ON bm.ITNBR = sp.Itnbr"
            . " WHERE bm.Owner_Comp = '" . $comp . "' AND shop.Owner_Comp = '" . $comp . "' AND cat.Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_update_shoppingcart($customerNo, $comp, $id, $quantity,$datetime) {
    $ctcdb = new ctcdb();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

        // $dup = ctc_get_dup_part_by_id($id);
        // if(!empty($dup)){
        //     $nid = $dup[0]['id'];
        //     $id = $nid;
        // }

            $quantity = $qty + $quantity;
            $sql = "UPDATE shoppingcart"
                    . " SET qty='" . $quantity . "', TransactionDate='" . $datetime . "'"
                    . " WHERE cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "' AND ID='" . $id . "' ";
            $stmt = $ctcdb->db->prepare($sql);
            $stmt->execute($data);

        $ctcdb->db->commit();
    } catch (Exception $e) {
        $ctcdb->db->rollback();
        $errorCode = '9999';
        $message = $e->getMessage();
    }

    $reportClass = new stdClass();
    $reportClass->errorCode = $errorCode;
    $reportClass->message = $message;
    //return $sql;
    return $reportClass;
}

function ctc_delete_shoppingcart_by_id($shoppingidlist) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

        $ids = implode("','", $shoppingidlist);
        $sql = "DELETE FROM shoppingcart WHERE id IN ('" . $ids . "') AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";
        $sth = $ctcdb->db->prepare($sql);
        $sth->execute();

        $ctcdb->db->commit();
    } catch (Exception $e) {
        $ctcdb->db->rollback();
        $errorCode = '9999';
        $message = $e->getMessage();
    }

    $reportClass = new stdClass();
    $reportClass->errorCode = $errorCode;
    $reportClass->message = $message;

    return $reportClass;
}

function ctc_delete_shoppingcart_by_cusno($cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

        $ids = implode("','", $shoppingidlist);
        $sql = "DELETE FROM shoppingcart WHERE Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";
        $sth = $ctcdb->db->prepare($sql);
        $sth->execute();

        $ctcdb->db->commit();
    } catch (Exception $e) {
        $ctcdb->db->rollback();
        $errorCode = '9999';
        $message = $e->getMessage();
    }

    $reportClass = new stdClass();
    $reportClass->errorCode = $errorCode;
    $reportClass->message = $message;

    return $reportClass;
}

function ctc_get_cusmas() {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();
    $sql = "SELECT cusmas.Cusno, cusmas.ESCA1, cusmas.ESCA2, cusmas.ESCA3, cusmas.OECus, cusrem.curcd, cusrem.remark FROM `cusmas` LEFT JOIN cusrem ON cusmas.cusno = cusrem.cusno WHERE TRIM(cusmas.cust3) ='$cusno' and cusmas.Owner_Comp='$comp' ORDER BY cusmas.Cusno";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function ctc_get_shoppingcart_cusno2($shipto) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();
    // if($comp == "IN0"){
        // $sql = "SELECT shop.ID, CONCAT(bm.ITNBR, ' - ', bm.ITDSC) AS PartNumber, bm.ITNBR AS PartNo, bm.ITDSC AS PrtDescr, sp.Price, sp.CurCD, bm.Lotsize, shop.QTY, cat.ModelCode"
        // . " FROM shoppingcart as shop"
        // . " LEFT JOIN catalogue AS cat ON shop.ID = cat.ID"
        // . " LEFT JOIN (SELECT DISTINCT ITNBR, ITDSC, Lotsize, Owner_Comp FROM bm008pr) AS bm ON cat.ordprtno = bm.ITNBR"
       // . " LEFT JOIN (SELECT DISTINCT Itnbr, Price, CurCD FROM sellprice WHERE Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' AND Shipto = '" . $shipto . "') AS sp ON bm.ITNBR = sp.Itnbr"
        // . " WHERE bm.Owner_Comp = '" . $comp . "' AND shop.Owner_Comp = '" . $comp . "' AND cat.Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";

    // }
    // else{
        $sql = "SELECT shop.ID, CONCAT(bm.ITNBR, ' - ', bm.ITDSC) AS PartNumber, bm.ITNBR AS PartNo, bm.ITDSC AS PrtDescr, sp.Price, sp.CurCD, bm.Lotsize, shop.QTY, cat.ModelCode"
            . " FROM shoppingcart as shop"
            . " LEFT JOIN catalogue AS cat ON shop.ID = cat.ID"
            . " LEFT JOIN (SELECT DISTINCT ITNBR, ITDSC, Lotsize, Owner_Comp FROM bm008pr) AS bm ON cat.ordprtno = bm.ITNBR"
            . " LEFT JOIN (SELECT DISTINCT awsexc.cusgrp,Itnbr, Price, curr CurCD FROM awsexc JOIN awscusmas on awscusmas.Owner_Comp = awsexc.Owner_Comp and awscusmas.cusno1 = awsexc.cusno1 and awscusmas.cusgrp = awsexc.cusgrp WHERE awsexc.Owner_Comp = '" . $comp . "' AND awscusmas.cusno2 = '".$cusno."') AS sp ON bm.ITNBR = sp.Itnbr"
            . " WHERE bm.Owner_Comp = '" . $comp . "' AND shop.Owner_Comp = '" . $comp . "' AND cat.Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";
   // }
   $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}