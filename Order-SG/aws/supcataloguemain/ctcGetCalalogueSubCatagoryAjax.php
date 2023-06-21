<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');

ctc_checkuser_permission('../../../login.php');

$cusno = ctc_get_session_cusno();
$comp = ctc_get_session_comp();
$catMaker = (string) filter_input(INPUT_POST, 'CatMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$brandName = (string) filter_input(INPUT_POST, 'Brand');
$modelCode = (string) filter_input(INPUT_POST, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_POST, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_POST, 'SubModelName');
$suppliercode = (string) filter_input(INPUT_POST, 'Supplier');

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
//echo $sql ;
if ($hasil) {
    $cusno1 = strtoupper($hasil['cusno1']);
} else {
    $cusno1 = $cusno ;
}

$result = ctc_get_supcatalogue_sub_category_forcus($catMaker, $modelName, $brandName, $modelCode, $subCatMaker, $subModelName, $suppliercode, $cusno1);
//echo $result ;
echo json_encode($result);

