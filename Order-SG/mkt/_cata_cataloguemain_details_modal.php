<?php
session_start();
require_once('../../language/Lang_Lib.php');
require_once('../../core/ctc_init.php');
require_once('../../core/ctc_permission.php');

ctc_checkadmin_permission('../../login.php');
?>

<?php
error_reporting(~E_NOTICE);
$comp = ctc_get_session_comp();

$uid = (int) filter_input(INPUT_POST, 'edit_id');
$lotsize = (int) filter_input(INPUT_POST, 'lotsize');
$ucusno = (int) filter_input(INPUT_POST, 'cusno');
$result = ctc_get_catalogue_by_cusid($uid,$ucusno);
//$result = ctc_get_catalogue_by_id($uid);
if (count($result) === 0) {
    echo "No record Found";
    exit();
}
    require('../db/conn.inc');
    $qry1="select * from availablestock where prtno='".$result[0]['ordprtno']."' and Owner_Comp='$comp' ";  // edit by CTC
	
    $qry1Result=mysqli_query($msqlcon,$qry1);
	if($stockArray = mysqli_fetch_array ($qry1Result)){
	    $stockqty=$stockArray['qty'];
		if($stockqty >= $qty){
			$msg= get_lng($_SESSION["lng"], "L0288")/*Yes"*/;
		}
		else{
			$msg= get_lng($_SESSION["lng"], "L0289")/*"No"*/;
		}
	}
	else{
		$qry2="select * from hd100pr where prtno='".$result[0]['ordprtno']."' and (l1awqy+l2awqy)>0 ";
		$qry2Result = mysqli_query($msqlcon,$qry2);
		$count2 = mysqli_num_rows($qry2Result);
		if($count2>0){
			$msg= get_lng($_SESSION["lng"], "L0288")/*"Yes"*/;
		} else{
			$msg= get_lng($_SESSION["lng"], "L0289")/*"No"*/;
		}
			
	}
//$mto = ctc_mto($result[0]['ordprtno']);
?>


<div class="margin-left-xs margin-right-xs row">
    <div class="col-md-6 margin-bottom-md">
        <img src="../../images/cata.png" width="15" height="15" alt="" />&nbsp;<span class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "L0370")/* Part Details Information */; ?></span>
    </div>
    <div class="col-md-6 margin-bottom-lg">
        <?php echo $quantityResult ?>
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
                <?php echo get_lng($_SESSION["lng"],  "L0446")/* Vin Code */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Vincode']; ?>
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
        <!--<div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0354")/* Genuine P/NO (Old) */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Custprthis']; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0356")/* DENSO P/NO (Old) */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Prtnohis']; ?>
            </div>
        </div>-->
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0357")/* DENSO P/NO (Current) */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Prtno']; ?>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0358")/* CG P/NO (Old) */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['cgprtnohis']; ?>
            </div>
        </div>-->
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"], "L0359")/* CG P/NO (Current) */; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Cgprtno']; ?>
            </div>
        </div>
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
                <?php echo get_lng($_SESSION["lng"], "L0445")/* Brand*/ ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['Brand']; ?>
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
                <?php echo get_lng($_SESSION["lng"], "L0447")/* Stock*/; ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $msg;?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"] ,"L0448")/* stdprice */;  ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $result[0]['stdprice']; ?>
            </div>
        </div>
        <!--<div class="row">
            <div class="col-xs-4">
                <?php echo get_lng($_SESSION["lng"] ,"L0072")/* MTO */;  ?>
            </div>
            <div class="col-xs-8 arial11">
                :&nbsp;<?php echo $mto; ?>
            </div>
        </div>-->
    </div>
    <div class="col-md-12 margin-top-md" style="overflow-y: auto;overflow-x: auto;max-height:300px">
        <img onerror="this.style.display='none'" alt="" draggable="false" src="../user_images/<?php echo $result[0]['PrtPic']; ?>" style="align-content: left;"/>
    </div>
    <div class="col-md-12 margin-left-md margin-top-sm custom-footer-modal">
        <p style="font-size:8px" align="left">Copyright &copy; 2023 DENSO . All rights reserved</p>
    </div>
</div> 