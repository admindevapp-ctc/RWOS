<?php

function ctc_get_supshoppingcart_table($shipto) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "
        SELECT shop.ID,cat.Prtno, CONCAT( cat.ordprtno, ' - ', cat.Prtnm) AS PartNumber, cat.supcd as supno
        , (select supnm  from supmas where supmas.Owner_Comp = cat.Owner_Comp AND cat.Supcd = supmas.supno ) as supname
        , cat.ordprtno AS PartNo, cat.Prtnm AS PrtDescr,  IFNULL(price.price, '') as price, IFNULL(price.curr,'') as curr , cat.Lotsize, shop.qty, cat.ModelCode
        FROM supshoppingcart as shop
            JOIN supcatalogue AS cat ON shop.ID = cat.ID and shop.Owner_comp = cat.Owner_comp
            LEFT JOIN (
                select *
                from supprice
                where supprice.Owner_comp = '" . $comp . "' 
                    and supprice.Cusno =    '" . $cusno . "'  and supprice.shipto =  '" . $shipto . "'
            ) as price on price.supno = cat.Supcd and price.Owner_comp = cat.Owner_comp
            and price.partno = cat.ordprtno
        WHERE shop.Owner_Comp = '" . $comp . "' AND  shop.Cusno =  '" . $cusno . "' 
        ORDER BY supno
    ";


    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    //return $sql;
}

function ctc_get_supshoppingcart($shipto) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "
        SELECT shop.ID,cat.Prtno, CONCAT( cat.ordprtno, ' - ', cat.Prtnm) AS PartNumber, cat.supcd as supno
        , (select supnm  from supmas where supmas.Owner_Comp = cat.Owner_Comp AND cat.Supcd = supmas.supno ) as supname
        , cat.ordprtno AS PartNo, cat.Prtnm AS PrtDescr, IFNULL(price.price, '') as price, IFNULL(price.curr,'') as curr , cat.Lotsize, shop.qty, cat.ModelCode
        FROM supshoppingcart as shop
            JOIN supcatalogue AS cat ON shop.ID = cat.ID  and shop.Owner_comp = cat.Owner_comp
            LEFT JOIN (
                select *
                from supprice
                where supprice.Owner_comp = '" . $comp . "' 
                    and supprice.Cusno =    '" . $cusno . "'  and supprice.shipto =  '" . $shipto . "'
            ) as price on price.supno = cat.Supcd and price.Owner_comp = cat.Owner_comp
            and price.partno = cat.ordprtno
        WHERE shop.Owner_Comp = '" . $comp . "' AND  shop.Cusno =  '" . $cusno . "' 
        ORDER BY supno
    ";


    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    //return $sql;
}


function ctc_get_supshoppingcart_bysupno($shipto,$supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "
        SELECT shop.ID,cat.Prtno, CONCAT( cat.ordprtno, ' - ', cat.Prtnm) AS PartNumber, cat.supcd as supno
        , (select supnm  from supmas where supmas.Owner_Comp = cat.Owner_Comp AND cat.Supcd = supmas.supno ) as supname
        , cat.ordprtno AS PartNo, cat.Prtnm AS PrtDescr, price.price, price.curr, cat.Lotsize, shop.qty, cat.ModelCode
        , '$shipto' as shipto
        FROM supshoppingcart as shop
            JOIN supcatalogue AS cat ON shop.ID = cat.ID and shop.Owner_comp = cat.Owner_comp
            JOIN supprice  as price on price.supno =  cat.Supcd and price.Owner_comp = cat.Owner_Comp 
                and price.partno = cat.ordprtno and price.Cusno = shop.cusno
            -- JOIN supref on supref.shipto = price.shipto and supref.supno = cat.Supcd and shop.cusno = supref.cusno
            --     and supref.Owner_comp = shop.Owner_comp
        WHERE shop.Owner_Comp = '" . $comp . "' AND  shop.Cusno =  '" . $cusno . "' and cat.Supcd = '".$supno."' and price.shipto  = '$shipto'
        ORDER BY supno
    ";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_delete_supshoppingcart_by_id($shoppingidlist) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

        $ids = implode("','", $shoppingidlist);
        $sql = "DELETE FROM supshoppingcart WHERE id IN ('" . $ids . "') AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";
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

function ctc_delete_supshoppingcart_by_cusno($cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

        $sql = "DELETE FROM supshoppingcart WHERE Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "'";
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

function ctc_get_supshoppingcart_tableaws($shipto) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "
        SELECT shop.ID,cat.Prtno, CONCAT( cat.ordprtno, ' - ', cat.Prtnm) AS PartNumber, cat.supcd as supno
        , (select supnm  from supmas where supmas.Owner_Comp = cat.Owner_Comp AND cat.Supcd = supmas.supno ) as supname
        , cat.ordprtno AS PartNo, cat.Prtnm AS PrtDescr
        -- ,  IFNULL(price.price, '') as price, IFNULL(price.curr,'') as curr 
        , cat.Lotsize, shop.qty, cat.ModelCode
        FROM supshoppingcart as shop
            JOIN supcatalogue AS cat ON shop.ID = cat.ID and shop.Owner_comp = cat.Owner_comp
        WHERE shop.Owner_Comp = '" . $comp . "' AND  shop.Cusno =  '" . $cusno . "' 
        ORDER BY supno
    ";


    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    //return $sql;
}


function ctc_get_awssupshoppingcart_bysupno($shipto,$supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $sql = "
		SELECT
			shop.ID,
			cat.Prtno,
			CONCAT(cat.ordprtno, ' - ', cat.Prtnm) AS PartNumber,
			cat.supcd AS supno,
				(
				SELECT
					supnm
				FROM
					supmas
				WHERE
					supmas.Owner_Comp = cat.Owner_Comp AND cat.Supcd = supmas.supno
			) AS supname,
			cat.ordprtno AS PartNo,
			cat.Prtnm AS PrtDescr,
			price.price,
			price.curr,
			cat.Lotsize,
			shop.qty,
			cat.ModelCode,
			awscusmas.ship_to_cd2 AS shipto
		FROM
			supshoppingcart AS shop
		JOIN supcatalogue AS cat
		ON
			shop.ID = cat.ID AND shop.Owner_comp = cat.Owner_comp
		JOIN awscusmas on awscusmas.cusno2 = shop.cusno
		JOIN supref ON supref.supno = cat.Supcd AND awscusmas.cusno1 = supref.cusno AND supref.Owner_comp = shop.Owner_comp
		LEFT JOIN supawsexc AS price
		ON
			price.supcode = cat.Supcd AND price.Owner_comp = cat.Owner_Comp AND price.prtno = cat.ordprtno AND price.cusno1 = shop.cusno
        WHERE shop.Owner_Comp = '" . $comp . "' AND  shop.Cusno =  '" . $cusno . "' and cat.Supcd = '".$supno."' and awscusmas.ship_to_cd2  = '$shipto'
        ORDER BY supno
    ";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}