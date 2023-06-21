<?php
session_start();
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');
require_once('../../language/Lang_Lib.php');

ctc_checkuser_permission('../../login.php');

?>

<?php
error_reporting(~E_NOTICE);
$comp = ctc_get_session_comp();

$uid = (int) filter_input(INPUT_POST, 'edit_id');
$lotsize = (int) filter_input(INPUT_POST, 'lotsize');
$result = ctc_get_supcatalogue_by_id_cusno($uid);
$orderno = $result[0]['ordprtno'];
$stdprice =$result[0]['stdprice']; 
//$stdprice = (double) ctc_get_stdprice($orderno)[0]['price'];
if (count($result) === 0) {
    echo "No record Found";
    exit();
}



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

?>

<div class="row col-md-12 arial11blackbold">
    <table width="95%">
        <tr class="row">
            <td colspan="3"><img src="../images/cata.png" width="15" height="15" />&nbsp;<span class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "L0370")/* Part Details Information */; ?></span></td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0347")/* Car Maker */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['CarMaker']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0423")/* Genuine P/NO */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Cprtn']; ?>
            </td>
            <td class="col-xs-2 arial11" rowspan="9"  style=" vertical-align:top;" align="right">
                <!--<img onerror="this.style.display='none'" alt="" draggable="false" src="sup_catalogue/<?php echo $result[0]['PrtPic']; ?>" style="width:150px; max-height: 150px; display: block; align-content: left;"> -->
                <a href="sup_catalogue/<?php echo $result[0]['PrtPic']; ?>" class="img-rounded"  data-lightbox="sub_image-<?php echo $result[0]['ID']; ?>" data-title="<?php echo $result[0]['Prtno']; ?>"><img onerror="this.style.display=\'none\'" src="sup_catalogue/<?php echo $result[0]['PrtPic']; ?>" class="img-rounded" style="width:150px; max-height: 150px; display: block; align-content: left;"></a>
            </td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0348")/* Model Name */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['ModelName']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0455")/* Supplier P/NO */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Prtno']; ?>
            </td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0446")/* Vin Code  */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['vincode']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0426")/* Order P/NO */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['ordprtno']; ?>
            </td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0349")/* Model Code */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['ModelCode']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0360")/* Part Name */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Prtnm']; ?>
            </td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0420")/* Engine Code */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['EngineCode']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0445")/* Brand Name */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['brand']; ?>
            </td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0351")/* CC. */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Cc']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0448")/* std price */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['stdprice'];    ?>
            </td>
           <!-- <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0361")/* Lot Size */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Lotsize']; ?>
            </td>-->
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0352")/* Start Date */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Start']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0517")/* MTO */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $mto; ?>
            </td>
        </tr>
        <tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0353")/* End Date */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['End']; ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0362")/* Remark */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['Remark']; ?>
            </td>
            <!--<td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0447")/* stock */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['StockQty']; ?>
            </td>-->
        </tr>
        <!--<tr class="row">
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0448")/* std price */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $result[0]['stdprice'];    ?>
            </td>
            <td class="col-xs-2 arial11blackbold">
                <?php echo get_lng($_SESSION["lng"], "L0517")/* MTO */; ?>
            </td>
            <td class="col-xs-3 arial11">
                :&nbsp;<?php echo $mto; ?>
            </td>
        </tr>-->
    </table>
</div> 
<!--
<div class="col-md-12 margin-left-md margin-top-sm custom-footer-modal">
    <p style="font-size:8px" align="left">Copyright &copy; 2023 DENSO . All rights reserved</p>
</div>-->