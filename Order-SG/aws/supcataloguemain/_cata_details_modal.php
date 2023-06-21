<?php 
session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');
require_once('../../../language/Lang_Lib.php');

ctc_checkuser_permission('../../login.php');

error_reporting(~E_NOTICE);

$uid = (int) filter_input(INPUT_POST, 'edit_id');
$lotsize = (int) filter_input(INPUT_POST, 'lotsize');
$result = ctc_aws_get_supcatalogue_by_id_cusno($uid);
if (count($result) === 0) {
    echo "No record Found";
    exit();
}

$erp = ctc_get_session_erp();
$comp = ctc_get_session_comp();
$cusno = ctc_get_session_cusno();
$quantityBox = '';
$quantityButtonCart = '';
$quantityMessage = '';
$lotsize = $result[0]['Lotsize'];
if($lotsize == '-'){
    $lotsize = 0;
}
//$sellprice = (double) $r['SellPrice'];
$sellprice = $result[0]['stdprice'];
if($sellprice == '-'){
    $sellprice = 0.00;
}
else{
    $sellprice = (double)$sellprice;
}
$orderno = $result[0]['ordprtno'];


$mto = "";
if($result[0]['MTO'] == "1"){
    $mto = "Yes";
}
else if($result[0]['MTO'] == "0"){
    $mto = "No";
}
else{
    $mto = $result[0]['MTO'] ;
}
$sql_chk_supawsexc = "
	
";


if ($lotsize === 0 || $result[0]['sell'] != '1') {
    $quantityBox = '<input class="touchspinQuantitySub form-control input-xs text-center" type="text" value="0" disabled>';
    $quantityButtonCart = '<button type="button" class="btn btn-xs" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '" disabled style="background-color: gray">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
    $quantityMessage = '<span class="arial21redbold">' . get_lng($_SESSION["lng"], "E0041")/* Price or Item not found */ . '</span>';
} else {
    $quantityButtonCart = '<button type="button" class="btn btn-success btn-xs buttonAddToCartSub" data-id="' . $uid . '" value="' . $uid . '" title="' . get_lng($_SESSION["lng"], "L0429")/* Add to cart */ . '">&nbsp;<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span></button>';
    $maxLot = 999 - (999 % $lotsize);
    $quantityBox = '<input class="touchspinQuantitySub form-control input-xs text-center" id="quantitySub' . $uid . '" type="text" value="0" name="quantity' . $uid . '"'
            . ' data-bts-step="' . $lotsize . '" data-bts-mousewheel="true" data-bts-min="' . $lotsize . '" data-bts-max="' . $maxLot . '">';
}

$quantityResult = '<div class="input-group">'
        . $quantityBox
        . '<div class="input-group-btn">'
        . $quantityButtonCart
        . '</div>'
        . '</div>';
?>


<div class="margin-left-xs margin-right-xs row">
    <div class="col-md-8 col-sm-8 col-xs-8 margin-bottom-md">
        <img src="../images/cata.png" width="15" height="15" alt="" />&nbsp;<span class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "L0370")/* Part Details Information */; ?></span>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-4 margin-bottom-lg">
        <?php echo $quantityResult ?>
        <?php echo $quantityMessage ?>
    </div>
    <div class="margin-left-md col-md-12 arial11blackbold">
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0347")/* Car Maker */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['CarMaker']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0348")/* Model Name */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['ModelName']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0446")/* Vin Code  */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['vincode']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0349")/* Model Code */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['ModelCode']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0420")/* Engine Code */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['EngineCode']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0351")/* CC. */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Cc']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0352")/* Start Date */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Start']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0353")/* End Date */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['End']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0423")/* Genuine P/NO */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Cprtn']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0455")/* Supplier P/NO */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Prtno']; ?>
            </div>
        </div>
       <!-- <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0425")/* CG P/NO */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Cgprtno']; ?>
            </div>
        </div>-->
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0360")/* Part Name */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Prtnm']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0445")/* Brand Name */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['brand']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0426")/* Order P/NO */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['ordprtno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0361")/* Lot Size */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $lotsize ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0447")/* stock */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['StockQty']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0448")/* std price */;?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['stdprice'] ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"] ,"L0517")/* MTO */;  ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $mto; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0362")/* Remark */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Remark']; ?>
            </div>
        </div>
    </div>
    <div class="col-md-12 margin-top-md" style="overflow-y: auto;overflow-x: auto;max-height:300px">
        <img onerror="this.style.display='none'" alt="" draggable="false" src="sup_catalogue/<?php echo $result[0]['PrtPic']; ?>" style="align-content: left;"/>
    </div>
    <div class="col-md-12 margin-left-md margin-top-sm custom-footer-modal">
        <p style="font-size:8px" align="left">Copyright &copy; 2023 DENSO . All rights reserved</p>
    </div>
</div> 