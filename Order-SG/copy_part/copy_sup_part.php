<?php
require_once('../../core/ctc_init.php'); // add by CTC
$owner_comp = isset($owner_comp) ? $owner_comp : '';
$allquerypass_sup = true;
$ctcdb = new ctcdb();
$num_rows_inst_sup = 0;
$num_rows_del_sup = 0;
try {
    $ctcdb->db->beginTransaction();

    $sql_cusno1 = "SELECT DISTINCT a.cusgrp,a.cusno1 FROM awscusmas a WHERE a.cusgrp NOT IN(SELECT DISTINCT b.cusgrp from supawsexc b WHERE 1 AND Owner_comp = '$owner_comp') AND Owner_comp = '$owner_comp'";
    $result_cusno1 = $ctcdb->db->query($sql_cusno1);
    $arr_cus = array();
    while ($row = $result_cusno1->fetch(PDO::FETCH_ASSOC)) {
        $arr_cus[] = $row;
    }

    foreach ($arr_cus as $k => $v) {
        $cusgrp = $v['cusgrp'];
        $cusno1 = $v['cusno1'];
        $sql = "INSERT INTO supawsexc(Owner_comp,supcode, prtno, cusno1,sell, cusgrp)
                SELECT Owner_comp,supcode, prtno, cusno1, '1' , '$cusgrp' FROM supawsexc WHERE cusno1 = '$cusno1' AND Owner_comp = '$owner_comp' GROUP BY prtno";
        $result = $ctcdb->db->query($sql);
		$num_rows_inst_sup += $result->rowCount();

        if ($result !== false) {
        } else {
            $allquerypass_sup = false;
        }
    }

    $sql_cus = "SELECT DISTINCT a.cusno1 FROM awscusmas a WHERE 1 AND Owner_comp = '$owner_comp'";
    $result_cus = $ctcdb->db->query($sql_cus);
    

    $arr_cus_inst = array();
    while ($row = $result_cus->fetch(PDO::FETCH_ASSOC)) {
        $arr_cus_inst[] = $row;
    }

    foreach ($arr_cus_inst as $k => $v) {
        $cusno = $v['cusno1'];
        $sql2 = "
        INSERT INTO supawsexc(
            Owner_comp,
            prtno,
            cusno1,
            supcode,
            sell,
            cusgrp
        )
        SELECT DISTINCT
            supprice.Owner_Comp,
            supprice.partno,
            awscusmas.cusno1,
            supprice.supno,
            '1',
            awscusmas.cusgrp
        FROM
            awscusmas
        JOIN supprice ON awscusmas.cusno1 = supprice.cusno AND supprice.Owner_comp = awscusmas.Owner_Comp
		JOIN supmas ON supmas.supno = supprice.supno

        WHERE
            supprice.Owner_comp = '$owner_comp' AND supprice.Cusno = '$cusno' AND awscusmas.cusgrp IS NOT NULL and awscusmas.cusgrp != '' AND supprice.partno NOT IN(
            SELECT DISTINCT
                prtno
            FROM
                supawsexc
            WHERE
                cusno1 = '$cusno'
                AND Owner_comp = '$owner_comp'
        )
    ";
        $result_inst = $ctcdb->db->query($sql2);
		$num_rows_inst_sup += $result_inst->rowCount();

        if ($result_inst !== false) {
        } else {
            $allquerypass_sup = false;
        }


        $sql_del = "
    
    DELETE
    FROM
        supawsexc
    WHERE
        prtno NOT IN(
        SELECT DISTINCT
            partno
        FROM
            supprice
		LEFT JOIN supmas on supmas.supno = supprice.supno
        WHERE
            supmas.Owner_comp = '$owner_comp' AND supprice.Cusno = '$cusno'  AND supmas.supno != ''
    ) AND cusno1 = '$cusno' AND Owner_comp = '$owner_comp'";
        $result_del = $ctcdb->db->query($sql_del);
		$num_rows_del_sup += $result_del->rowCount();

        if ($result_del !== false) {
        } else {
            $allquerypass_sup = false;
        }
    }
	$sql_del_grp = "
        DELETE supawsexc
		FROM supawsexc
		LEFT JOIN awscusmas ON supawsexc.cusgrp = awscusmas.cusgrp AND awscusmas.Owner_Comp = supawsexc.Owner_Comp
		WHERE (awscusmas.cusgrp IS NULL OR awscusmas.cusgrp = '')
		AND supawsexc.Owner_comp = '$owner_comp';
        ";
    $result_del_grp = $ctcdb->db->query($sql_del_grp);
	$num_rows_del_sup += $result_del_grp->rowCount();

    $num_rows = $result_del_grp->rowCount();

    if ($result_del_grp !== false) {
    } else {
        $allquerypass_sup = false;
    }
	if( $allquerypass_sup){
		$ctcdb->db->commit();
	}else{
		$ctcdb->db->rollback(); 
	}
}catch (PDOException  $e) {
	$ctcdb->db->rollback();
}
