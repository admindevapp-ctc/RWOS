<?php

session_start();
require_once('../../../core/ctc_init.php');
require_once('../../../core/ctc_permission.php');
require_once('../../../language/Lang_Lib.php');

ctc_checkadmin_permission('../../../login.php');

$catMaker = (string) filter_input(INPUT_POST, 'CatMaker');
$modelName = (string) filter_input(INPUT_POST, 'ModelName');
$brand = (string) filter_input(INPUT_POST, 'Brand');
$modelCode = (string) filter_input(INPUT_POST, 'ModelCode');
$subCatMaker = (string) filter_input(INPUT_POST, 'SubCatMaker');
$subModelName = (string) filter_input(INPUT_POST, 'SubModelName');
$subGroup = (string) filter_input(INPUT_POST, 'SubGroup');
$search = (string) filter_input(INPUT_POST, 'SearchTable');
$order = (int) $_REQUEST['order'][0]['column'];
$orderType = (string) $_REQUEST['order'][0]['dir'];
$start = (int) filter_input(INPUT_POST, 'start');
$length = (int) filter_input(INPUT_POST, 'length');
$comp = ctc_get_session_comp();

$result = ctc_get_catalogue_table($catMaker, $modelName, $modelCode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $brand);

//Remapping for datatable
$output = [];
foreach ($result->data as $r) {
    require('../../db/conn.inc');
    $qry1="select * from availablestock where prtno='".$r['ordprtno']."' and Owner_Comp='$comp' ";  // edit by CTC
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
		$qry2="select * from hd100pr where prtno='".$r['ordprtno']."' and (l1awqy+l2awqy)>0 ";
		$qry2Result = mysqli_query($msqlcon,$qry2);
		$count2 = mysqli_num_rows($qry2Result);
		if($count2>0){
			$msg= get_lng($_SESSION["lng"], "L0288")/*"Yes"*/;
		} else{
			$msg= get_lng($_SESSION["lng"], "L0289")/*"No"*/;
		}
			
	}

    $lotsize = (int) ctc_get_lotsize($r['ordprtno'])[0]['Lotsize'];
    $newThing = new stdClass();
    $newThing->ID = $r['ID']."|". $r['Cusno'];
    $newThing->CheckBox = '<input type="checkbox" />';
    $newThing->CarMaker = $r['CarMaker'];
    $newThing->ModelName = $r['ModelName'];
    $newThing->ModelCode = $r['ModelCode'];
    $newThing->EngineCode = $r['EngineCode'];
    $newThing->GenuinePartNo = $r['Cprtn'];
    $newThing->PartNo = $r['Prtno'];
    $newThing->CGPartNo = $r['Cgprtno'];
    $newThing->OrderPartNo = $r['ordprtno'];
    $newThing->PartName = $r['Prtnm'];
    $newThing->LotSize = $lotsize;
    $newThing->Brand = $r['Brand'];
    $newThing->Stock = $msg;
    $newThing->Vincode = $r['Vincode'];
    $newThing->StdPrice = "<span class='arial11'>" . $r['stdprice']  . "</span><br/><span style='font-size: 9px;'>" .$r['Cusno'] . "</span>";
    $newThing->Picture = '<a href="../user_images/' . $r['PrtPic'] . '" class="img-rounded" data-lightbox="image-' . $r['ID'] . '" data-title="' . $newThing->OrderPartNo . '"><img onerror="this.style.display=\'none\'" src="../user_images/' . $r['PrtPic'] . '" class="img-rounded" width="25px" height="25px"></a>';
    $newThing->Detail = '<button type="button" class="btn btn-link btn-xs padding-bottom-xs button-datail" data-lot-size="' . $lotsize . '"  data-cusno="'. $r['Cusno'] .'" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0430")/* Details */ . '"><span class="glyphicon glyphicon-list-alt"></span></button>';
    $newThing->Action = '<button type="button" class="btn btn-warning btn-xs padding-bottom-xs button-edit margin-right-sm" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0434")/* Edit */ . '"><span class="glyphicon glyphicon-edit"></span></button>'
            . '<button type="button" class="btn btn-danger btn-xs button-delete padding-bottom-xs margin-right-sm" value="' . $r['ID'] . '" title="' . get_lng($_SESSION["lng"], "L0433")/* Remove */ . '"><span class="glyphicon glyphicon-scissors"></span></a>';
//    $newThing->Action = '<a href="cata_editform.php?edit_id=' . $r['ID'] . ' title="click for edit" onclick="return confirm(\'Confirm to edit ?\')">Edit/</a>'
//            . '<a href = "?delete_id=' . $r['ID'] . '" title = "click for delete" onclick = "return confirm(\'Confirm to delete ?\')">Delete</a>';
    $output[] = $newThing;
}

$result->data = $output;

echo json_encode($result);

