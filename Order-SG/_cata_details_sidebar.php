<?php /* Create by CTC */ ?>
<?php session_start() ?>
<?php
require_once('../language/Lang_Lib.php');
require_once('../core/ctc_init.php');
require_once('../core/ctc_permission.php');

ctc_checkuser_permission('../login.php');
?>

<?php
error_reporting(~E_NOTICE);

$uid = (int) filter_input(INPUT_POST, 'edit_id');
$lotsize = (int) filter_input(INPUT_POST, 'lotsize');
$result = ctc_get_catalogue_by_id_cus($uid);
if (count($result) === 0) {
    echo "No record Found";
    exit();
}

//$mto = ctc_mto($result[0]['ordprtno']);
?>

<div class="row">
    <div class="col-md-12 margin-bottom-md margin-top-md">
        <img src="../images/cata.png" width="15" height="15" />&nbsp;<span class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "L0370")/* Part Details Information */; ?></span>
    </div>
    <div class="col-md-12 arial11blackbold">
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0347")/* Car Maker */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['CarMaker']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0423")/* Genuine Part No. */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Cprtn']; ?>
            </div>
            <div class="col-xs-1 arial11"  style=" vertical-align:top;position: absolute;padding-right:50px;width: 100%;" align="right">
                <!--<img onerror="this.style.display='none'" alt="" draggable="false" src="sup_catalogue/<?php echo $result[0]['PrtPic']; ?>" style="width:150px; max-height: 150px; display: block; align-content: left;"> -->
                <a href="user_images/<?php echo $result[0]['PrtPic']; ?>" class="img-rounded"  data-lightbox="sub_image-<?php echo $result[0]['ID']; ?>" data-title="<?php echo $result[0]['Prtno']; ?>"><img onerror="this.style.display=\'none\'" src="user_images/<?php echo $result[0]['PrtPic']; ?>" class="img-rounded" style="width:150px; max-height: 150px; display: block; align-content: left;"></a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0348")/* Model Name */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['ModelName']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0357")/* DENSO P/NO (Current) */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Prtno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0446")/* Vin Code */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Vincode']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0359")/* CG P/NO */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Cgprtno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0349")/* Model Code */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['ModelCode']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0426")/* Order P/NO */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['ordprtno']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0420")/* Engine Code */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['EngineCode']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0360")/* Part Name */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Prtnm']; ?>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0354")/* Genuine P/NO (Old) */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Custprthis']; ?>
            </div>
             <div class="col-xs-2">
            <?php echo get_lng($_SESSION["lng"], "L0356")/* DENSO P/NO (Old) */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Prtnohis']; ?>
            </div>
        </div>-->
        <!--<div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0358")/* CG P/NO (Old) */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['cgprtnohis']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0359")/* CG P/NO */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Cgprtno']; ?>
            </div>
        </div>-->
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0351")/* CC. */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Cc']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0445")/* Brand*/; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Brand']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0352")/* Start Date */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Start']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0448")/* Price*/; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['stdprice']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0353")/* End Date */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['End']; ?>
            </div>
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0362")/* Remark */; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Remark']; ?>
            </div>
        </div>

           <!-- <div class="col-xs-2">
           <?php echo get_lng($_SESSION["lng"], "L0447")/* Stock*/; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Stock'];  ?>
            </div>-->
        <!--<div class="row">
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0448")/* Price*/; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['stdprice']; ?>
            </div>
            
            <div class="col-xs-2">
                <?php echo get_lng($_SESSION["lng"], "L0072")/* MTO*/; ?>
            </div>
            <div class="col-xs-3 arial11">
                :&nbsp;<?php echo $mto;  ?>
            </div>
        </div>-->
    </div>
    <!--<div class="col-md-12 margin-top-md">
        <img onerror="this.style.display='none'" alt="" draggable="false" src="user_images/<?php echo $result[0]['PrtPic']; ?>" class="img-responsive" style="max-width:100%; max-height: 200px; align-content: left;" />
    </div>-->
    <div class="col-md-12 margin-left-md margin-top-sm custom-footer-modal">
        <p style="font-size:8px" align="left">Copyright &copy; 2023 DENSO . All rights reserved</p>
    </div>
</div> 