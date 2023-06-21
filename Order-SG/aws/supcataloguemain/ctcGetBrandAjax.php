<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$cusno = ctc_get_session_cusno();
$comp = ctc_get_session_comp();

$ctcdb = new ctcdb();
$sql = "
SELECT distinct awscusmas.cusno1 
FROM awscusmas 
    join supawsexc on awscusmas.cusno1 = supawsexc.cusno1 and awscusmas.cusgrp = supawsexc.cusgrp 
        and awscusmas.Owner_Comp = supawsexc.Owner_Comp 
WHERE awscusmas.cusno2 = '$cusno' and awscusmas.Owner_Comp='$comp'";
$sth = $ctcdb->db->prepare($sql);
$sth->execute();
//echo $sql;
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$hasil = $result[0];

if ($hasil) {
    $cusno1 = strtoupper($hasil['cusno1']);
} else {
    $cusno1 = $cusno ;
}


$carmaker = (string) filter_input(INPUT_POST, 'CarMaker');
$modelname = (string) filter_input(INPUT_POST, 'ModelName');
$result = ctc_get_supbrand_forcus($cusno1,$carmaker,$modelname  );

echo json_encode($result);

//echo $result;