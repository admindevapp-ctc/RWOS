<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();
$ctcdb = new ctcdb();
$sql = "
SELECT distinct awscusmas.cusno1 
FROM awscusmas 
    join supawsexc on awscusmas.cusno1 = supawsexc.cusno1 and awscusmas.cusgrp = supawsexc.cusgrp 
        and awscusmas.Owner_Comp = supawsexc.Owner_Comp 
WHERE awscusmas.cusno2 = '$cusno' and awscusmas.Owner_Comp='$comp'";
$sth = $ctcdb->db->prepare($sql);
$sth->execute();

$result = $sth->fetchAll(PDO::FETCH_ASSOC);
$hasil = $result[0];

if ($hasil) {
    $cusno1 = strtoupper($hasil['cusno1']);
} else {
    $cusno1 = $cusno ;
}

$carMaker = (string) filter_input(INPUT_POST, 'CarMaker');
$result = ctc_get_supcatalogue_modelname_forcus($carMaker,$cusno1);

echo json_encode($result);

//echo $result;