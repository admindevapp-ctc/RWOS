<?php
require_once('../../core/ctc_init.php'); // add by CTC
$owner_comp = isset($owner_comp) ? $owner_comp : '';
$allquerypass = true;
$ctcdb = new ctcdb();
$num_rows_inst = 0;
$num_rows_del = 0;

try {
    $ctcdb->db->beginTransaction();
    // make all group have the same data
    $sql_cusno1 = "SELECT DISTINCT a.cusgrp,a.cusno1 FROM awscusmas a WHERE a.cusgrp NOT IN(SELECT DISTINCT b.cusgrp from awsexc b WHERE 1 AND Owner_comp = '$owner_comp') AND Owner_comp = '$owner_comp'";
    $result_cusno1 = $ctcdb->db->query($sql_cusno1);
    $arr_cus = array();
    while($row = $result_cusno1->fetch(PDO::FETCH_ASSOC)){
        $arr_cus[] = $row;
    }

    foreach ($arr_cus as $k => $v) {
        $cusgrp = $v['cusgrp'];
        $cusno1 = $v['cusno1'];
        $sql = "INSERT INTO awsexc(Owner_comp, itnbr, cusno1,sell, cusgrp)
                SELECT Owner_comp, Itnbr, cusno1, '1' , '$cusgrp' FROM awsexc WHERE cusno1 = '$cusno1' AND Owner_comp = '$owner_comp' GROUP BY Itnbr";
        $result = $ctcdb->db->query($sql);
		$num_rows_inst += $result->rowCount();

        if ($result !== false) {
        } else {
            $allquerypass = false;
        }
    }


    $sql_cus = "SELECT DISTINCT a.cusno1 FROM awscusmas a WHERE 1 AND Owner_comp = '$owner_comp'";
    $result_cus = $ctcdb->db->query($sql_cus);

    $arr_cus_inst = array();
    while($row = $result_cus->fetch(PDO::FETCH_ASSOC)){
        $arr_cus_inst[] = $row;
    }

    foreach ($arr_cus_inst as $k => $v) {
        $cusno = $v['cusno1'];
        $sql2 = "
            INSERT INTO awsexc(
                Owner_comp,
                itnbr,
                cusno1,
                sell,
                cusgrp
            )
            SELECT DISTINCT
                sellprice.Owner_Comp,
                sellprice.itnbr,
                awscusmas.cusno1,
                '1',
                awscusmas.cusgrp
            FROM
                awscusmas
            JOIN sellprice ON awscusmas.cusno1 = sellprice.cusno AND sellprice.Owner_Comp = awscusmas.Owner_Comp
            WHERE
                sellprice.Owner_Comp = '$owner_comp' AND sellprice.Cusno = '$cusno' AND awscusmas.cusgrp IS NOT NULL and awscusmas.cusgrp != '' AND sellprice.Itnbr NOT IN(
                SELECT DISTINCT
                    itnbr
                FROM
                    awsexc
                WHERE
                    cusno1 = '$cusno'
                    AND Owner_comp = '$owner_comp'
            )
        ";
        $result_inst = $ctcdb->db->query($sql2);
		$num_rows_inst += $result_inst->rowCount();

        if ($result_inst !== false) {
        } else {
            $allquerypass = false;
        }

		
        $sql_del = "
        DELETE
        FROM
            awsexc
        WHERE
            itnbr NOT IN(
            SELECT DISTINCT
                itnbr
            FROM
                sellprice
            WHERE
                Owner_Comp = '$owner_comp' AND Cusno = '$cusno'
        ) AND cusno1 = '$cusno' AND Owner_Comp = '$owner_comp'";
        $result_del = $ctcdb->db->query($sql_del);
		$num_rows_del += $result_del->rowCount();

        if ($result_del !== false) {
        } else {
            $allquerypass = false;
        }
    }
	$sql_del_grp = "
		DELETE awsexc
		FROM awsexc
		LEFT JOIN awscusmas ON awsexc.cusgrp = awscusmas.cusgrp AND awscusmas.Owner_Comp = awsexc.Owner_Comp
		WHERE (awscusmas.cusgrp IS NULL OR awscusmas.cusgrp = '')
		AND awsexc.Owner_comp = '$owner_comp';
	";

	$result_del_grp = $ctcdb->db->query($sql_del_grp);
	$num_rows_del += $result_del_grp->rowCount();

	if ($result_del_grp !== false) {
	} else {
		$allquerypass = false;
	} 

    if( $allquerypass){
		$num_rows_inst;
		$num_rows_del;

        $ctcdb->db->commit();
    }else{
        $ctcdb->db->rollback(); 
    }
}catch (PDOException  $e) {
    $ctcdb->db->rollback();
}

?>