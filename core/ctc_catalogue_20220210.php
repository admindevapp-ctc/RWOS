<?php
ini_set("memory_limit","2G");
function ctc_get_calalogue_carmaker() {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $sql = 'SELECT DISTINCT CarMaker FROM catalogue WHERE Owner_Comp="' . $comp . '" ORDER BY CarMaker';

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_catalogue_modelname($carmaker) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    if (!isset($carmaker) || trim($carmaker) === '') {
        $sql = "SELECT DISTINCT ModelName FROM catalogue WHERE Owner_Comp='" . $comp . "' ORDER BY ModelName";
    } else {
        $sql = "SELECT DISTINCT ModelName FROM catalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' ORDER BY ModelName";
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_catalogue_brandlist($carmaker, $modelname) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
   
    //$sql = "SELECT DISTINCT Brand FROM catalogue WHERE Owner_Comp='" . $comp . "' ORDER BY Brand";
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT Brand FROM catalogue WHERE Owner_Comp='" . $comp . "' ORDER BY Brand";
        } else {
            $sql = "SELECT DISTINCT Brand FROM catalogue WHERE ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY Brand";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT Brand FROM catalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' ORDER BY Brand";
        } else {
            $sql = "SELECT DISTINCT Brand FROM catalogue WHERE CarMaker LIKE '%$carmaker%' AND ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY Brand";
        }
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_catalogue_modelcode($carmaker, $modelname) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode FROM catalogue WHERE Owner_Comp='" . $comp . "' ORDER BY ModelName";
        } else {
            $sql = "SELECT DISTINCT ModelCode FROM catalogue WHERE ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY ModelName";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode FROM catalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' ORDER BY ModelName";
        } else {
            $sql = "SELECT DISTINCT ModelCode FROM catalogue WHERE CarMaker LIKE '%$carmaker%' AND ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY ModelCode";
        }
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_catalogue_table($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $brand) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '');
    $isbrandValid = (isset($brand) && trim($brand) !== '');
    $isSearchValid = (isset($search) && trim($search) !== '');
    $isSubGroupValid = (isset($subGroup) && trim($subGroup) !== '');




    //--------------TODO Check ERP status if 0 use 'qbm008pr' else use 'bm008pr'-------------------//
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
            . ", Cgprtno, Brand , ordprtno, SUBSTRING(REPLACE(REPLACE(Prtnm, ' ',''),'-',''),1,35)  as Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
            . ", (SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' LIMIT 1) AS SellPrice"
            . ", (SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' LIMIT 1) AS SellPrice"
            . ", IFNULL(sellprice.Price,'-') AS stdprice,  sellprice.Cusno " 
            . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
            . " FROM catalogue AS cat";

    $sql .= "  	left join sellprice on sellprice.Owner_Comp = cat.Owner_Comp and  Itnbr = cat.ordprtno ";
    $sqlCount = "SELECT Count(1) totalrow"
            . " FROM catalogue AS cat" 
            . " left join sellprice on sellprice.Owner_Comp = cat.Owner_Comp and  Itnbr = cat.ordprtno "; ;

//    Start condition
    $condition = "WHERE cat.Owner_Comp = '" . $comp . "' AND sellprice.Shipto = '001'";
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker = '$carmaker'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName = '$modelname'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
    }

    if ($isbrandValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$brand'";
    }

    $column = '';
    if ($isSubCatMakerValid && $isSubModelNameValid) {
        switch ($subCatMaker) {
            case '1':
                $column = 'Prtno';
                break;
            case '2' :
                $column = 'CarMaker';
                break;
            case '3' :
                $column = 'ModelName';
                break;
            case '4' :
                $column = 'ModelCode';
                break;
            case '5' :
                $column = 'Cprtn';
                break;
            case '6' :
                $column = 'Cgprtno';
                break;
            case '7' :
                $column = 'Prtnm';
                break;
            default :
                break;
        }
    }

    if (isset($column) && trim($column) !== '') {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . $column . " LIKE '%$subModelName%'";
    }

    if ($isSearchValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "(CarMaker LIKE '%$search%' || "
                . "ModelName LIKE '%$search%' || "
                . "ModelCode LIKE '%$search%' || "
                . "EngineCode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "Prtno LIKE '%$search%' || "
                . "Cgprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
                . "Vincode LIKE '%$search%' || "
                . "Remark LIKE '%$search%' || "
                . "Prtnm LIKE '%$search%')";
    }

    if ($isSubGroupValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "ModelName = '$subGroup'";
    }

    if (isset($condition) && trim($condition) !== '') {
        $sql = $sql . ' ' . $condition;
        $sqlCount = $sqlCount . ' ' . $condition;
    }
//    End condition
//    Start Order By
    $orderCondition = ' ORDER BY ';
    switch ($order) {
        case 0:
            $orderCondition = $orderCondition . 'CarMaker ' . $orderType;
            break;
        case 1:
            $orderCondition = $orderCondition . 'ModelName ' . $orderType;
            break;
        case 2:
            $orderCondition = $orderCondition . 'ModelCode ' . $orderType;
            break;
        case 3:
            $orderCondition = $orderCondition . 'EngineCode ' . $orderType;
            break;
        case 4:
            $orderCondition = $orderCondition . 'Cprtn ' . $orderType;
            break;
        case 5:
            $orderCondition = $orderCondition . 'Prtno ' . $orderType;
            break;
        case 6:
            $orderCondition = $orderCondition . 'Cgprtno ' . $orderType;
            break;
        case 10:
            $orderCondition = $orderCondition . 'Prtnm ' . $orderType;
            break;
        case 11:
            $orderCondition = $orderCondition . 'ordprtno ' . $orderType;
            break;
        default :
            $orderCondition = $orderCondition . 'CarMaker ' . $orderType;
            break;
    }
    //End Order By
    $sql = $sql . $orderCondition . " LIMIT " . $length . " OFFSET " . $start; //Merge all SQL
    
    //Count total record    
    $sthCount = $ctcdb->db->prepare($sqlCount);
    $sthCount->execute();
    $resultCount = $sthCount->fetchAll(PDO::FETCH_ASSOC);
    $totalRecord = (int) $resultCount[0]['totalrow'];

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    //Build return model
    $reportClass = new stdClass();
    $reportClass->data = $result;
    $reportClass->recordsTotal = $totalRecord;
    $reportClass->recordsFiltered = $totalRecord;
    return $reportClass;
}

function ctc_get_catalogue_sub_category($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $brandName) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '');
    $isbrandNameValid = (isset($brandName) && trim($brandName) !== '');

    $sqlSubCategory = "SELECT ModelName, Count(1) totalRow FROM catalogue AS cat" ;
    //Start condition
    $condition = 'WHERE Owner_Comp = "' . $comp . '"';
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker = '$carmaker'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName = '$modelname'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
    }

    if ($isbrandNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$brandName'";
    }

    $column = '';
    if ($isSubCatMakerValid && $isSubModelNameValid) {
        switch ($subCatMaker) {
            case '1':
                $column = 'Prtno';
                break;
            case '2' :
                $column = 'CarMaker';
                break;
            case '3' :
                $column = 'ModelName';
                break;
            case '4' :
                $column = 'ModelCode';
                break;
            case '5' :
                $column = 'Cprtn';
                break;
            case '6' :
                $column = 'Cgprtno';
                break;
            case '7' :
                $column = 'Prtnm';
                break;
            default :
                break;
        }
    }

    if (isset($column) && trim($column) !== '') {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . $column . " LIKE '%$subModelName%'";
    }

    if (isset($condition) && trim($condition) !== '') {
        $sqlSubCategory = $sqlSubCategory . ' ' . $condition;
    }
    //End condition

    $sqlSubCategory = $sqlSubCategory . ' GROUP BY ModelName';

    //Query with Group Filter
    $sthSubCategory = $ctcdb->db->prepare($sqlSubCategory);
    $sthSubCategory->execute();
    $resultSubCategory = $sthSubCategory->fetchAll(PDO::FETCH_ASSOC);

    return $resultSubCategory;
}

function ctc_get_announce($comp, $date) {
    $ctcdb = new ctcdb();

    $sql = "SELECT title, detail, PrtPic FROM announce WHERE Owner_Comp='" . $comp . "' AND `start`<='" . $date . "' AND `end`>='" . $date . "'";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_current_cart_item($customerNo, $comp) {
    $ctcdb = new ctcdb();

    $sqlCount = "SELECT Count(1) totalrow FROM shoppingcart"
            . " WHERE Cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "'";
    $sthCount = $ctcdb->db->prepare($sqlCount);
    $sthCount->execute();
    $resultCount = $sthCount->fetchAll(PDO::FETCH_ASSOC);

    $totalRecord = (int) $resultCount[0]['totalrow'];

    return $totalRecord;
}

function ctc_save_shoppingcart($customerNo, $comp, $id, $quantity, $datetime) {
    $ctcdb = new ctcdb();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

        // $dup = ctc_get_dup_part_by_id($id);
        // if(!empty($dup)){
        //     $nid = $dup[0]['id'];
        //     $id = $nid;
        // }

        $sqlCount = "SELECT QTY FROM shoppingcart"
                . " WHERE Cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "' AND id='" . $id . "'";
        $sthCount = $ctcdb->db->prepare($sqlCount);
        $sthCount->execute();
        $resultCount = $sthCount->fetchAll(PDO::FETCH_ASSOC);
        $qty = (int) $resultCount[0]['QTY'];

        $sql = '';
        if ($qty == 0) {
            $sql = "INSERT INTO shoppingcart (Cusno, Owner_Comp, id, Qty, TransactionDate)"
                    . " VALUES ('" . $customerNo . "', '" . $comp . "', '" . $id . "', '" . $quantity . "', '" . $datetime . "')";
            $stmt = $ctcdb->db->prepare($sql);
            $stmt->execute($data);
        } else {
            $quantity = $qty + $quantity;
            $sql = "UPDATE shoppingcart"
                    . " SET Qty='" . $quantity . "', TransactionDate='" . $datetime . "'"
                    . " WHERE Cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "' AND id='" . $id . "'";
            $stmt = $ctcdb->db->prepare($sql);
            $stmt->execute($data);
        }

        $ctcdb->db->commit();
    } catch (Exception $e) {
        $ctcdb->db->rollback();
        $errorCode = '9999';
        $message = $e->getMessage();
    }

    $reportClass = new stdClass();
    $reportClass->errorCode = $errorCode;
    $reportClass->message = $message;

    return $reportClass;
}

function ctc_get_catalogue_by_id($id) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

     $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
        . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
        . ", (SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' LIMIT 1) AS SellPrice"
        . ", IFNULL(sellprice.Price,'-') AS stdprice,  sellprice.Cusno " 
        . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
        . " FROM catalogue AS cat"
        . "  	left join sellprice on sellprice.Owner_Comp = cat.Owner_Comp and  Itnbr = cat.ordprtno AND Shipto = '001' "
        . " WHERE ID ='" . $id . "' AND cat.Owner_Comp ='" . $comp . "'"
        . " LIMIT 1";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
   
}


function ctc_get_catalogue_by_cusid($id,$cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
    . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
    . ", IFNULL(sellprice.Price,'-') AS stdprice,  sellprice.Cusno " 
    . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
    . "  FROM catalogue AS cat"
    . "  	left join sellprice on sellprice.Owner_Comp = cat.Owner_Comp "
    . "        and  Itnbr = cat.ordprtno and Cusno = '" . $cusno . "' and Shipto = '001' "
    . " WHERE ID ='" . $id . "' AND cat.Owner_Comp ='" . $comp . "' " 
    . " LIMIT 1";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
   
}

function ctc_get_catalogue_by_id_cus($id) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

     $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
        . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
        . ", IFNULL(sellprice.Price,'-') AS stdprice,  sellprice.Cusno " 
        . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
        . "  FROM catalogue AS cat"
        . "  	left join sellprice on sellprice.Owner_Comp = cat.Owner_Comp "
    	. "        and  Itnbr = cat.ordprtno and Cusno = '" . $cusno . "' and Shipto = '001' "
        . " WHERE ID ='" . $id . "' AND cat.Owner_Comp ='" . $comp . "' " 
        . " LIMIT 1";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
   
}

function ctc_mto($partno){

    $ctcdb = new ctcdb();
    $mto = "";
    if(ctc_get_session_erp() == '0'){
        $mtoQry="select * from mto where prtno='".$partno."' and Owner_Comp='$comp'";  // edit by CTC
    }else{
        $mtoQry="select * from bm008pr where MTO='1' and ITNBR='".$partno."' and Owner_Comp='".$comp."' ";
    }
    $sth = $ctcdb->db->prepare($mtoQry);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    if($result[0]){
        $mto = "Yes";
    }
    else{
        $mto = "No";
    }
    return $mto;
}

function ctc_get_catalogue($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $Brand, $search) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '');
    $isBrandValid = (isset($Brand) && trim($Brand) !== '');
    $isSearchValid = (isset($search) && trim($search) !== '');
    
    //--------------TODO Check ERP status if 0 use 'qbm008pr' else use 'bm008pr'-------------------//
   /* $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno, Cgprtno, Brand "
    .", ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
    .", (SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Shipto = '001' ) AS stdprice"
    .", (SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '"  . $comp . "' LIMIT 1) AS Stock "
    ." FROM catalogue AS cat";
*/

//Tuning Performance
/* $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
            . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
            . ", (SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' LIMIT 1) AS SellPrice"
            . ", IFNULL(sellprice.Price,'-') AS stdprice,  sellprice.Cusno " 
            . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
            . " FROM catalogue AS cat"
            . "  	left join sellprice on sellprice.Owner_Comp = cat.Owner_Comp and  Itnbr = cat.ordprtno AND Shipto = '001' "; */
	$sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, cat.Prtno, Cgprtno "
            . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
            . ", IFNULL(sellprice.Price, '-') as stdprice " 
            . ", IFNULL(availablestock.qty,'-') AS Stock "
			. ", hd100pr.amount as hd100pr_amount "
			. ", bm008pr.Lotsize "
            . " FROM catalogue AS cat"
			. " LEFT JOIN sellprice ON sellprice.Owner_Comp = cat.Owner_Comp AND Itnbr = cat.ordprtno"
			. " LEFT JOIN availablestock ON availablestock.prtno = cat.ordprtno AND availablestock.Owner_Comp =cat.Owner_Comp"
//			. " LEFT JOIN hd100pr_count as hd100pr ON hd100pr.prtno = cat.ordprtno AND hd100pr.Owner_Comp = cat.Owner_Comp"
			. " LEFT JOIN (SELECT Owner_Comp, prtno, count(1) as amount FROM hd100pr where (l1awqy + l2awqy) > 0 GROUP BY Owner_Comp , prtno) as hd100pr ON hd100pr.prtno = cat.ordprtno AND hd100pr.Owner_Comp = cat.Owner_Comp"
			. " LEFT JOIN bm008pr as bm008pr ON bm008pr.itnbr = cat.ordprtno AND bm008pr.Owner_Comp = cat.Owner_Comp";
           
    //Start condition

    $condition = "WHERE cat.Owner_Comp = '" . $comp . "' AND sellprice.Shipto = '001'";
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker LIKE '%$carmaker%'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName LIKE '%$modelname%'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode LIKE '%$modelcode%'";
    }

    if ($isBrandValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand LIKE '%$Brand%'";
    }

    if ($isSearchValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "(CarMaker LIKE '%$search%' || "
                . "ModelName LIKE '%$search%' || "
                . "ModelCode LIKE '%$search%' || "
                . "EngineCode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "cat.Prtno LIKE '%$search%' || "
                . "Cgprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
                . "Vincode LIKE '%$search%' || "
                . "Remark LIKE '%$search%' || "
                . "Prtnm LIKE '%$search%')";
    }

    $column = '';
    if ($isSubCatMakerValid && $isSubModelNameValid) {
        switch ($subCatMaker) {
            case '1':
                $column = 'Prtno';
                break;
            case '2' :
                $column = 'CarMaker';
                break;
            case '3' :
                $column = 'ModelName';
                break;
            case '4' :
                $column = 'ModelCode';
                break;
            case '5' :
                $column = 'Cprtn';
                break;
            case '6' :
                $column = 'Cgprtno';
                break;
            case '7' :
                $column = 'Prtnm';
                break;
            default :
                break;
        }
    }

    if (isset($column) && trim($column) !== '') {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . $column . " LIKE '%$subModelName%'";
    }

    if (isset($condition) && trim($condition) !== '') {
        $sql = $sql . ' ' . $condition;
    }
    //End condition
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    //return $sql;
}

function ctc_delete_catalogue_by_id($id) {
    $ctcdb = new ctcdb();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

//        select image from db to delete
        $sqlPic = 'SELECT PrtPic FROM catalogue WHERE ID =' . $id;
        $sthPic = $ctcdb->db->prepare($sqlPic);
        $sthPic->execute();
        $resultPic = $sthPic->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultPic) > 0) {
            $imgPrt = (string) $resultPic[0]['PrtPic'];
            unlink(dirname(__FILE__) . "/../Order-SG/user_images/" . $imgPrt);
//            delete an actual record from db
            $sql = 'DELETE FROM catalogue WHERE ID =' . $id;
            $stmt = $ctcdb->db->prepare($sql);
            $stmt->execute();
        }

        $ctcdb->db->commit();
    } catch (Exception $e) {
        $ctcdb->db->rollback();
        $errorCode = '9999';
        $message = $e->getMessage();
    }

    $reportClass = new stdClass();
    $reportClass->errorCode = $errorCode;
    $reportClass->message = $message;

    return $reportClass;
}

function ctc_get_lotsize($Itnbr) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $sql = "SELECT Lotsize"
            . " FROM bm008pr"
            . " WHERE ITNBR = '" . $Itnbr . "' AND Owner_Comp = '" . $comp . "'";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_available_qbm008pr_count($orderno) {
    $ctcdb = new ctcdb();
    $cusno = ctc_get_session_cusno();
    $comp = ctc_get_session_comp();
    
    $sql = "select count(1) from qbm008pr where prtno='$orderno' and salavl='YES' and cusno='$cusno' and Owner_Comp='$comp' ";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result[0];
}

function ctc_get_catalogue_table_cus($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $brand) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '');
    $isbrandValid = (isset($brand) && trim($brand) !== '');
    $isSearchValid = (isset($search) && trim($search) !== '');
    $isSubGroupValid = (isset($subGroup) && trim($subGroup) !== '');




    //--------------TODO Check ERP status if 0 use 'qbm008pr' else use 'bm008pr'-------------------//
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
            . ", Cgprtno, Brand , ordprtno, SUBSTRING(REPLACE(REPLACE(Prtnm, ' ',''),'-',''),1,35)  as Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
            . ", IFNULL((SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' and shipto='001'),'-') AS stdprice"
            . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
            . " FROM catalogue AS cat";

            
    $sqlCount = "SELECT Count(1) totalrow"
            . " FROM catalogue AS cat";

//    Start condition
    $condition = "WHERE cat.Owner_Comp = '" . $comp . "'";
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker = '$carmaker'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName = '$modelname'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
    }

    if ($isbrandValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$brand'";
    }

    $column = '';
    if ($isSubCatMakerValid && $isSubModelNameValid) {
        switch ($subCatMaker) {
            case '1':
                $column = 'Prtno';
                break;
            case '2' :
                $column = 'CarMaker';
                break;
            case '3' :
                $column = 'ModelName';
                break;
            case '4' :
                $column = 'ModelCode';
                break;
            case '5' :
                $column = 'Cprtn';
                break;
            case '6' :
                $column = 'Cgprtno';
                break;
            case '7' :
                $column = 'Prtnm';
                break;
            default :
                break;
        }
    }

    if (isset($column) && trim($column) !== '') {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . $column . " LIKE '%$subModelName%'";
    }

    if ($isSearchValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "(CarMaker LIKE '%$search%' || "
                . "ModelName LIKE '%$search%' || "
                . "ModelCode LIKE '%$search%' || "
                . "EngineCode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "Prtno LIKE '%$search%' || "
                . "Cgprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
                . "Vincode LIKE '%$search%' || "
                . "Remark LIKE '%$search%' || "
                . "Prtnm LIKE '%$search%')";
    }

    if ($isSubGroupValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "ModelName = '$subGroup'";
    }

    if (isset($condition) && trim($condition) !== '') {
        $sql = $sql . ' ' . $condition;
        $sqlCount = $sqlCount . ' ' . $condition;
    }
//    End condition
//    Start Order By
    $orderCondition = ' ORDER BY ';
    switch ($order) {
        case 0:
            $orderCondition = $orderCondition . 'CarMaker ' . $orderType;
            break;
        case 1:
            $orderCondition = $orderCondition . 'ModelName ' . $orderType;
            break;
        case 2:
            $orderCondition = $orderCondition . 'ModelCode ' . $orderType;
            break;
        case 3:
            $orderCondition = $orderCondition . 'EngineCode ' . $orderType;
            break;
        case 4:
            $orderCondition = $orderCondition . 'Cprtn ' . $orderType;
            break;
        case 5:
            $orderCondition = $orderCondition . 'Prtno ' . $orderType;
            break;
        case 6:
            $orderCondition = $orderCondition . 'Cgprtno ' . $orderType;
            break;
        case 10:
            $orderCondition = $orderCondition . 'Prtnm ' . $orderType;
            break;
        case 11:
            $orderCondition = $orderCondition . 'ordprtno ' . $orderType;
            break;
        default :
            $orderCondition = $orderCondition . 'CarMaker ' . $orderType;
            break;
    }
    //End Order By
    $sql = $sql . $orderCondition . " LIMIT " . $length . " OFFSET " . $start; //Merge all SQL
    
    //Count total record    
    $sthCount = $ctcdb->db->prepare($sqlCount);
    $sthCount->execute();
    $resultCount = $sthCount->fetchAll(PDO::FETCH_ASSOC);
    $totalRecord = (int) $resultCount[0]['totalrow'];

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    //Build return model
    $reportClass = new stdClass();
    $reportClass->data = $result;
    $reportClass->recordsTotal = $totalRecord;
    $reportClass->recordsFiltered = $totalRecord;

    return $reportClass;
    
}


function ctc_get_catalogue_cus($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $Brand, $search) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '');
    $isBrandValid = (isset($Brand) && trim($Brand) !== '');
    $isSearchValid = (isset($search) && trim($search) !== '');

    //--------------TODO Check ERP status if 0 use 'qbm008pr' else use 'bm008pr'-------------------//
   /* $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno, Cgprtno, Brand "
    .", ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
    .", (SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Shipto = '001' ) AS stdprice"
    .", (SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '"  . $comp . "' LIMIT 1) AS Stock "
    ." FROM catalogue AS cat";
*/

// Tuning Performance
/* $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, Cgprtno "
            . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
            . ", IFNULL((SELECT Price FROM sellprice WHERE Itnbr = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno = '" . $cusno . "' LIMIT 1),'-') AS stdprice " 
            . ", IFNULL((SELECT qty FROM availablestock WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "'  LIMIT 1),'-') AS Stock "
            . " FROM catalogue AS cat"; */
		// Create temp info to performance query below.
	$sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, cat.Prtno, Cgprtno "
            . ", Cgprtno, Brand , ordprtno, Prtnm, cgprtnohis, Custprthis, Remark, Cc, Start, End, Prtnohis , Vincode "
            . ", IFNULL(sellprice.Price, '-') as stdprice " 
            . ", IFNULL(availablestock.qty,'-') AS Stock "
			. ", hd100pr.amount as hd100pr_amount "
			. ", bm008pr.Lotsize "
            . " FROM catalogue AS cat"
			. " LEFT JOIN sellprice ON sellprice.Owner_Comp = cat.Owner_Comp AND Itnbr = cat.ordprtno"
			. " LEFT JOIN availablestock ON availablestock.prtno = cat.ordprtno AND availablestock.Owner_Comp =cat.Owner_Comp"
//			. " LEFT JOIN hd100pr_count as hd100pr ON hd100pr.prtno = cat.ordprtno AND hd100pr.Owner_Comp = cat.Owner_Comp"
			. " LEFT JOIN (SELECT Owner_Comp, prtno, count(1) as amount FROM hd100pr where (l1awqy + l2awqy) > 0 GROUP BY Owner_Comp , prtno) as hd100pr ON hd100pr.prtno = cat.ordprtno AND hd100pr.Owner_Comp = cat.Owner_Comp"
			. " LEFT JOIN bm008pr as bm008pr ON bm008pr.itnbr = cat.ordprtno AND bm008pr.Owner_Comp = cat.Owner_Comp";
           
    //Start condition

    $condition = "WHERE cat.Owner_Comp = '" . $comp . "' AND sellprice.Shipto = '001' AND sellprice.Cusno = '" . $cusno . "'";
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker LIKE '%$carmaker%'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName LIKE '%$modelname%'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode LIKE '%$modelcode%'";
    }

    if ($isBrandValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand LIKE '%$Brand%'";
    }
    
    $column = '';
    if ($isSubCatMakerValid && $isSubModelNameValid) {
        switch ($subCatMaker) {
            case '1':
                $column = 'Prtno';
                break;
            case '2' :
                $column = 'CarMaker';
                break;
            case '3' :
                $column = 'ModelName';
                break;
            case '4' :
                $column = 'ModelCode';
                break;
            case '5' :
                $column = 'Cprtn';
                break;
            case '6' :
                $column = 'Cgprtno';
                break;
            case '7' :
                $column = 'Prtnm';
                break;
            default :
                break;
        }
    }

    if (isset($column) && trim($column) !== '') {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . $column . " LIKE '%$subModelName%'";
    }

    if ($isSearchValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "(CarMaker LIKE '%$search%' || "
                . "ModelName LIKE '%$search%' || "
                . "ModelCode LIKE '%$search%' || "
                . "EngineCode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "cat.Prtno LIKE '%$search%' || "
                . "Cgprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
                . "Vincode LIKE '%$search%' || "
                . "Remark LIKE '%$search%' || "
                . "Prtnm LIKE '%$search%')";
    }
    if (isset($condition) && trim($condition) !== '') {
        $sql = $sql . ' ' . $condition;
    }

    //End condition

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


// function ctc_get_dup_part_by_id($id){
//     $ctcdb = new ctcdb();
//     $cusno = ctc_get_session_cusno();
//     $comp = ctc_get_session_comp();
    
//     $sql = "SELECT *,(SELECT ordprtno FROM catalogue WHERE ID=s.id and ordprtno=(SELECT ordprtno FROM catalogue WHERE ID='$id')) as dup 
//     FROM `shoppingcart` s 
//     WHERE Owner_Comp='$comp' AND cusno='$cusno' 
//     HAVING dup is not null LIMIT 1";

//     $sth = $ctcdb->db->prepare($sql);
//     $sth->execute();
//     $result = $sth->fetchAll(PDO::FETCH_ASSOC);

//     return $result;
// }
