<?php

function ctc_get_supplier() {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
     $sql = "SELECT DISTINCT supno,supnm,logo FROM supmas WHERE Owner_Comp='" . $comp . "' ORDER BY supno";
        

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supbrand($carmaker ,$modelname) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    // $sql = "SELECT distinct brand  FROM supcatalogue WHERE Owner_Comp='" . $comp . "' ORDER BY brand";
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE Owner_Comp='" . $comp . "' ORDER BY brand";
        } else {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY brand";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' ORDER BY brand";
        } else {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY brand";
        }
    }
  

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function ctc_get_supcatalogue_carmaker() {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $sql = 'SELECT DISTINCT CarMaker FROM supcatalogue WHERE Owner_Comp="' . $comp . '" ORDER BY CarMaker';

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_delete_supcatalogue_by_id($id) {
    $ctcdb = new ctcdb();
    $errorCode = '0000';
    $message = 'success';
    try {
        $ctcdb->db->beginTransaction();

//        select image from db to delete
        $sqlPic = 'SELECT PrtPic FROM supcatalogue WHERE ID =' . $id;
        $sthPic = $ctcdb->db->prepare($sqlPic);
        $sthPic->execute();
        $resultPic = $sthPic->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultPic) > 0) {
            $imgPrt = (string) $resultPic[0]['PrtPic'];
            unlink(dirname(__FILE__) . "/../Order-SG/sup_catalogue/" . $imgPrt);
//            delete an actual record from db
            $sql = 'DELETE FROM supcatalogue WHERE ID =' . $id;
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


function ctc_get_supcatalogue_modelname($carmaker) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    if (!isset($carmaker) || trim($carmaker) === '') {
        $sql = "SELECT DISTINCT ModelName FROM supcatalogue WHERE Owner_Comp='" . $comp . "' ORDER BY ModelName";
    } else {
        $sql = "SELECT DISTINCT ModelName FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' ORDER BY ModelName";
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supcatalogue_modelcode($carmaker, $modelname) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE Owner_Comp='" . $comp . "' ORDER BY ModelCode";
        } else {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY ModelCode";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' ORDER BY ModelCode";
        } else {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' ORDER BY ModelCode";
        }
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function ctc_get_supcatalogue_table($carmaker, $modelname, $brandname, $modelcode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $suppliercode) {

    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $d = ctc_get_session_cusno();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '' && trim($carmaker) !== '0');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '' && trim($modelname) !== '0');
    $isBrandNameValid = (isset($brandname) && trim($brandname) !== '' && trim($brandname) !== '0');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '' && trim($modelcode) !== '0');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '' && trim($subCatMaker) !== '0');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '' && trim($subModelName) !== '0');
    $isSearchValid = (isset($search) && trim($search) !== '');
    $isSubGroupValid = (isset($subGroup) && trim($subGroup) !== '');
    $isSuppliercodeValid = (isset($suppliercode) && trim($suppliercode) !== '');

    //---------------------------------//
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
        . " ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO,supref.Cusno "
        . "  ,   IFNULL( ( "
        . "     SELECT supprice.Price "
        . "     FROM supprice  "
        . "     where cat.Supcd = supprice.supno "
        . "         and cat.Owner_Comp = supprice.Owner_comp "
        . "         and cat.Supcd = supprice.supno "
        . "         and cat.ordprtno = supprice.partno "
        . "         and supprice.Owner_comp = supref.Owner_comp "
        . "         and supprice.supno = supref.supno "
        . "         and supprice.Cusno = supref.Cusno "
        . "         and supprice.shipto = supref.shipto"
        . "      ),'-') AS stdprice "
        . " , IFNULL(( "
        . "     SELECT StockQty FROM supstock  "
        . "     WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno  and cat.Owner_Comp = supstock.Owner_Comp "
        . " ),'-') AS StockQty "
        . " ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno "
        . " FROM supcatalogue AS cat "
        . " left join supref  on cat.Owner_comp = supref.Owner_comp "
        . " and cat.Supcd = supref.supno" ;
    $sqlCount = "SELECT Count(1) totalrow"
            . " FROM supcatalogue AS cat"
            . " left join supref  on cat.Owner_comp = supref.Owner_comp "
            . " and cat.Supcd = supref.supno" ;

//    Start condition
    $condition = "WHERE cat.Owner_Comp = '" . $comp . "'";
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker = '$carmaker'";
    }

    if ($isBrandNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "brand = '$brandname'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName = '$modelname'";
    }

    if($isSuppliercodeValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . " Supcd = '$suppliercode'";

    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
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
                . "Vincode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "Prtno LIKE '%$search%' || "
                . "ordprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
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

function ctc_get_supcatalogue_sub_category($carmaker, $modelname, $brandName, $modelcode, $subCatMaker, $subModelName, $suppliercode) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== '' && trim($carmaker) !== '0');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '' && trim($modelname) !== '0');
    $isbrandNameValid = (isset($brandName) && trim($brandName) !== ''  && trim($brandName) !== '0');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== ''  && trim($modelcode) !== '0');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '' && trim($subCatMaker) !== '0');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '' && trim($subModelName) !== '0');
    $isSuppliercodeValid = (isset($suppliercode) && trim($suppliercode) !== '' && trim($suppliercode) !== '0');

    $sqlSubCategory = "SELECT ModelName, Count(1) totalRow FROM supcatalogue AS cat";

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

    if ($isbrandNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$brandName'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
    }

    if($isSuppliercodeValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Supcd = '$suppliercode'";

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

function ctc_get_supannounce($comp, $date) {
    $ctcdb = new ctcdb();

    $sql = "SELECT title, detail, PrtPic FROM supannounce WHERE Owner_Comp='" . $comp . "' AND `start`<='" . $date . "' AND `end`>='" . $date . "' order by `start` desc ";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_sup_lotsize($Itnbr, $suppliercode) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isSuppliercodeValid = (isset($suppliercode) && trim($suppliercode) !== '');
    /*
    $sql = "SELECT StockQty as Lotsize"
            . " FROM supstock"
            . " WHERE partno = '" . $Itnbr . "' AND Owner_Comp = '" . $comp . "'  and supno='" . $supno . "'";
*/
    $sql = "select StockQty  as Lotsize "
            . " from supstock "
            . "	join supcatalogue "
            . "		on supcatalogue.Supcd = supstock.supno and supcatalogue.ordprtno = supstock.partno  "
            . " WHERE partno = '" . $Itnbr . "' AND supcatalogue.Owner_Comp = '" . $comp . "' ";

    if($isSuppliercodeValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Supcd = '$suppliercode'";
        
    }

    $sql = $sql . ' GROUP BY 1';
    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supcatalogue_by_id($id) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    /*$sql = 'SELECT ID, Vincode, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, "" Cgprtno, ordprtno, Prtnm, brand, Start, End, Cc, Lotsize '
            . ', (SELECT Price FROM supprice WHERE prtno = cat.ordprtno AND Owner_Comp = "' . $comp . '" AND Cusno = "' . $cusno . '" LIMIT 1) AS SellPrice'
            . ', (SELECT StockQty FROM supstock WHERE cat.supcd = supstock.supno and cat.ordprtno = supstock.partno ) AS StockQty'
            . ' FROM supcatalogue AS cat'
    */
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
        . " ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO "
        . " , IFNULL( (SELECT supprice.Price "
        . "    FROM supprice  join supref on "
        . "     supprice.Owner_comp = supref.Owner_comp "
        . "      and supprice.supno = supref.supno and supprice.Cusno = supref.Cusno and supprice.shipto = supref.shipto "
        . "     WHERE  cat.ordprtno = supprice.partno  AND supprice.Owner_Comp = cat.Owner_Comp and cat.Supcd = supprice.supno  "
	    . "      AND supprice.Owner_Comp = '" . $comp . "' limit 1 ),'-') AS stdprice "
        . " , IFNULL(( "
        . "     SELECT StockQty FROM supstock  "
        . "     WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno  and cat.Owner_Comp = supstock.Owner_Comp "
        . " ),'-') AS StockQty "
        . " ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno "
        . " FROM supcatalogue AS cat "
        . " WHERE ID ='" . $id . "' AND Owner_Comp = '" . $comp . "'"
        . " LIMIT 1";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function ctc_get_supcatalogue_by_idandcusno($id, $cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    /*$sql = 'SELECT ID, Vincode, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, "" Cgprtno, ordprtno, Prtnm, brand, Start, End, Cc, Lotsize '
            . ', (SELECT Price FROM supprice WHERE prtno = cat.ordprtno AND Owner_Comp = "' . $comp . '" AND Cusno = "' . $cusno . '" LIMIT 1) AS SellPrice'
            . ', (SELECT StockQty FROM supstock WHERE cat.supcd = supstock.supno and cat.ordprtno = supstock.partno ) AS StockQty'
            . ' FROM supcatalogue AS cat'
    */
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
    ." ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO,  supref.Cusno "
     ." , IFNULL( (  "
     ."    SELECT supprice.Price   "
     ."    FROM supprice   "
     ."    where cat.Supcd = supprice.supno  "
     ."        and cat.Owner_Comp = supprice.Owner_comp  "
     ."        and cat.Supcd = supprice.supno "
     ."        and cat.ordprtno = supprice.partno  "
     ."        and supprice.Owner_comp = supref.Owner_comp  "
     ."        and supprice.supno = supref.supno  "
     ."        and supprice.Cusno = supref.Cusno  "
     ."        and supprice.shipto = supref.shipto "
     ."    ),'-') AS stdprice  "
     ." , IFNULL((  "
     ."    SELECT StockQty FROM supstock   "
     ."     WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno  and cat.Owner_Comp = supstock.Owner_Comp  "
     ." ),'-') AS StockQty  "
     ." FROM supcatalogue AS cat  "
     ." left join supref  on cat.Owner_comp = supref.Owner_comp  "
     ."      and cat.Supcd = supref.supno and supref.Cusno = '".$cusno."'"
     . " WHERE ID ='" . $id . "' AND cat.Owner_Comp = '" . $comp . "' ";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supcatalogue($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $Brand, $Supplier, $search) {
    
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== ''&& trim($carmaker) !== '0');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== ''&& trim($modelname) !== '0');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== ''&& trim($modelcode) !== '0');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== ''&& trim($subCatMaker) !== '0');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== ''&& trim($subModelName) !== '0');
    $isSearchValid = (isset($search) && trim($search) !== ''&& trim($search) !== '0');

    $isBrandValid = (isset($Brand) && trim($Brand) !== '');
    $isSubSupplierValid = (isset($Supplier) && trim($Supplier) !== '');

    //--------------TODO Check ERP status if 0 use 'qbm008pr' else use 'bm008pr'-------------------//
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
        . " ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO "
        . "  ,   IFNULL( ( "
        . "     SELECT supprice.Price "
        . "     FROM supprice  "
        . "     where cat.Supcd = supprice.supno "
        . "         and cat.Owner_Comp = supprice.Owner_comp "
        . "         and cat.Supcd = supprice.supno "
        . "         and cat.ordprtno = supprice.partno "
        . "         and supprice.Owner_comp = supref.Owner_comp "
        . "         and supprice.supno = supref.supno "
        . "         and supprice.Cusno = supref.Cusno "
        . "         and supprice.shipto = supref.shipto"
        . "      ),'-') AS stdprice "
        . " , IFNULL(( "
        . "     SELECT StockQty FROM supstock  "
        . "     WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno  and cat.Owner_Comp = supstock.Owner_Comp "
        . " ),'-') AS StockQty "
        . " ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno "
        . " FROM supcatalogue AS cat "
        . "left join supref  on cat.Owner_comp = supref.Owner_comp "
        . " and cat.Supcd = supref.supno ";

        
    //Start condition

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
    
    if ($isBrandValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$Brand'";
    }


    if($isSubSupplierValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Supcd = '$Supplier'";

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
    if ($isSearchValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }

        $condition = $condition . "(CarMaker LIKE '%$search%' || "
        . "ModelName LIKE '%$search%' || "
        . "ModelCode LIKE '%$search%' || "
        . "EngineCode LIKE '%$search%' || "
        . "Vincode LIKE '%$search%' || "
        . "Cprtn LIKE '%$search%' || "
        . "Prtno LIKE '%$search%' || "
        . "ordprtno LIKE '%$search%' || "
        . "Brand LIKE '%$search%' || "
        . "Remark LIKE '%$search%' || "
        . "Prtnm LIKE '%$search%')";
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
   //return $sql;
    return $result;
}


function ctc_get_stdprice($Itnbr) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();


    $sql = "select distinct price "
        . "from supprice,supref "
        . "where supprice.supno = supref.supno and supprice.shipto =  supref.shipto and supprice.Owner_Comp =  supref.Owner_Comp "
        . "and supprice.partno = '" . $Itnbr . "' AND supref.Owner_Comp = '" . $comp . "'";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

//for usertype supplier
function ctc_get_supno_supannounce($comp, $date, $supno) {
    $ctcdb = new ctcdb();

    $sql = "SELECT title, detail, PrtPic FROM supannounce WHERE Owner_Comp='" . $comp . "' AND `start`<='" . $date . "' AND `end`>='" . $date . "' and supno='" . $supno . "' order by `start` desc";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supno_supcatalogue_carmaker($supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $sql = "SELECT DISTINCT CarMaker FROM supcatalogue WHERE Owner_Comp='" . $comp . "'  and Supcd='" . $supno . "'  ORDER BY CarMaker";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supno_supcatalogue_modelname($carmaker,$supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    if (!isset($carmaker) || trim($carmaker) === '') {
        $sql = "SELECT DISTINCT ModelName FROM supcatalogue WHERE Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY ModelName";
    } else {
        $sql = "SELECT DISTINCT ModelName FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY ModelName";
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supno_supcatalogue_modelcode($carmaker, $modelname, $supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY ModelName";
        } else {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY ModelName";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'  ORDER BY ModelName";
        } else {
            $sql = "SELECT DISTINCT ModelCode FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND ModelName LIKE '%$modelname%' AND Owner_Comp='" . $comp . "' and supno='" . $supno . "'  ORDER BY ModelCode";
        }
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_supno_supbrand($carmaker, $modelname,$supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY brand";
        } else {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE ModelName = '$modelname' AND Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY brand";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE CarMaker = '$carmaker' AND Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'  ORDER BY brand";
        } else {
            $sql = "SELECT DISTINCT brand FROM supcatalogue WHERE CarMaker = '$carmaker' AND ModelName = '$modelname' AND Owner_Comp='" . $comp . "' and supno='" . $supno . "'  ORDER BY brand";
        }
    }
/*
     $sql = "SELECT distinct brand  
     FROM supcatalogue 
     WHERE Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'  
     ORDER BY brand";
        */

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_calaloguebysup_modelname($carmaker,$supno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $supno=$_SESSION['supno'];
    if (!isset($carmaker) || trim($carmaker) === '') {
        $sql = "SELECT DISTINCT ModelName FROM supcatalogue WHERE Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY ModelName";
    } else {
        $sql = "SELECT DISTINCT ModelName FROM supcatalogue WHERE CarMaker LIKE '%$carmaker%' AND Owner_Comp='" . $comp . "' and Supcd='" . $supno . "'   ORDER BY ModelName";
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    
    return $result;
}

function ctc_get_supstdprice($Itnbr) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $supno=$_SESSION['supno'];


    $sql = "select distinct price "
        . "from supprice,supref "
        . "where supprice.supno = supref.supno and supprice.shipto =  supref.shipto and supref.Owner_Comp = supprice.Owner_Comp "
        . "and supprice.partno = '" . $Itnbr . "' AND supref.Owner_Comp = '" . $comp . "' and supref.supno = '".$supno."'";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


// for customer

function ctc_get_supplier_forcus($cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $sql = "SELECT DISTINCT supmas.supno, supmas.supnm, supmas.logo 
            FROM supmas 
                JOIN supref on supmas.supno = supref.supno and supmas.Owner_Comp = supref.Owner_Comp
            WHERE supmas.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
            ORDER BY supmas.supno";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    //return $sql;
    return $result;
}


function ctc_get_supannounce_forcus($comp, $date, $cusno) {
    $ctcdb = new ctcdb();

    $sql = "SELECT title, detail, PrtPic 
        FROM supannounce 
            JOIN supref on supannounce.supno = supref.supno 
            and supannounce.cusno = supref.Cusno and supannounce.Owner_Comp = supref.Owner_Comp
        WHERE supannounce.Owner_Comp='" . $comp . "'  AND  supref.cusno = '".$cusno."'  AND `start`<='" . $date . "' AND `end`>='" . $date . "' order by  `start` desc";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    //return $sql;
    return $result;
}


function ctc_get_supcatalogue_carmaker_forcus($cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $sql = "SELECT DISTINCT CarMaker
        FROM supcatalogue 
            JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
        WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
        ORDER BY CarMaker";
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
   // return $sql;
    return $result;
}


function ctc_get_supcatalogue_modelname_forcus($carmaker, $cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    if (!isset($carmaker) || trim($carmaker) === '') {
        $sql = "SELECT DISTINCT ModelName
        FROM supcatalogue 
            JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
        WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
        ORDER BY ModelName";
    } else {
        $sql = "SELECT DISTINCT ModelName
        FROM supcatalogue 
            JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
        WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
            AND CarMaker LIKE '%$carmaker%' 
        ORDER BY ModelName";
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    //return $sql;
    return $result;
}

function ctc_get_supcatalogue_modelcode_forcus($carmaker, $modelname, $cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');
    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
            ORDER BY ModelCode";
        } else {
            $sql = "SELECT DISTINCT ModelCode
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' AND ModelName LIKE '%$modelname%'
            ORDER BY ModelCode";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT ModelCode
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' AND CarMaker LIKE '%$carmaker%' 
            ORDER BY ModelCode";
        } else {
            $sql = "SELECT DISTINCT ModelCode
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' AND CarMaker LIKE '%$carmaker%'  AND ModelName LIKE '%$modelname%'
            ORDER BY ModelCode";
        }
    }

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    //return $sql;
    return $result;
}

function ctc_get_supbrand_forcus($cusno,$carmaker,$modelname  ) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isCarMakerEmpty = (!isset($carmaker) || trim($carmaker) === '');
    $isModelNameEmpty = (!isset($modelname) || trim($modelname) === '');

    if ($isCarMakerEmpty) {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT brand
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
            ORDER BY brand";
        } else {
            $sql = "SELECT DISTINCT brand
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' AND ModelName LIKE '%$modelname%'
            ORDER BY brand";
        }
    } else {
        if ($isModelNameEmpty) {
            $sql = "SELECT DISTINCT brand
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' AND CarMaker LIKE '%$carmaker%' 
            ORDER BY brand";
        } else {
            $sql = "SELECT DISTINCT brand
            FROM supcatalogue 
                JOIN supref on supcatalogue.Supcd = supref.supno and supcatalogue.Owner_Comp = supref.Owner_Comp
            WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' AND CarMaker LIKE '%$carmaker%'  AND ModelName LIKE '%$modelname%'
            ORDER BY brand";
        }
    }

    /*$sql = "SELECT DISTINCT brand
    FROM supcatalogue 
        JOIN supref on supcatalogue.Supcd = supref.supno 
    WHERE supcatalogue.Owner_Comp='" . $comp . "'  and  cusno = '".$cusno."' 
    ORDER BY brand";
    */
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
    //return $sql;
}

function ctc_get_supcatalogue_table_forcus($carmaker, $modelname, $brandname, $modelcode, $subCatMaker, $subModelName, $search, $subGroup, $order, $orderType, $start, $length, $suppliercode, $cusno) {

    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $d = ctc_get_session_cusno();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== ''&& trim($carmaker) !== '0');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== ''&& trim($modelname) !== '0');
    $isBrandNameValid = (isset($brandname) && trim($brandname) !== ''&& trim($brandname) !== '0');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== '' && trim($modelcode) !== '0');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== ''&& trim($subCatMaker) !== '0');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== '' && trim($subModelName) !== '0');
    $isSearchValid = (isset($search) && trim($search) !== '');
    $isSubGroupValid = (isset($subGroup) && trim($subGroup) !== '');
    $isSuppliercodeValid = (isset($suppliercode) && trim($suppliercode) !== ''&& trim($suppliercode) !== '0');

    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
        . " ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO "
        . " ,IFNULL(( "
        . "     select price  "
        . "     from supprice  "
        . "     where cat.Owner_Comp = supprice.Owner_comp  "
        . "         and cat.Supcd = supprice.supno and cat.ordprtno = supprice.partno  "
        . "         and supref.Cusno = supprice.Cusno  "
        . "         and supref.shipto = supprice.shipto  "
        . " ),'-') as stdprice "
        . " ,IFNULL(( "
        . "    select StockQty  "
        . "    from supstock   "
        . "    where cat.Owner_Comp = supstock.Owner_Comp "
        . "       and trim(supstock.partno) = trim(cat.Prtno) and supstock.supno = cat.Supcd "
        . "   limit 1 "
        . "  ),'-') as StockQty "
        . " ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno "
        . " FROM supcatalogue AS cat "
        . " JOIN supref on cat.Supcd = supref.supno and cat.Owner_Comp = supref.Owner_Comp ";
    $sqlCount = "SELECT Count(1) totalrow"
            . " FROM supcatalogue AS cat"
            . " JOIN supref on cat.Supcd = supref.supno  and cat.Owner_Comp = supref.Owner_Comp ";
          

//    Start condition
    $condition = " WHERE cat.Owner_Comp = '" . $comp . "'  AND cusno = '".$cusno."'  ";
    if ($isCarMakerValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "CarMaker = '$carmaker'";
    }

    if ($isBrandNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$brandname'";
    }

    if ($isModelNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelName = '$modelname'";
    }

    if($isSuppliercodeValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Supcd = '$suppliercode'";

    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
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
                . "Vincode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "Prtno LIKE '%$search%' || "
                . "ordprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
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
  //return $sql;
}

function ctc_get_supcatalogue_sub_category_forcus($carmaker, $modelname, $brandName, $modelcode, $subCatMaker, $subModelName, $suppliercode, $cusno) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== ''&& trim($carmaker) !== '0');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '' && trim($modelname) !== '0');
    $isbrandNameValid = (isset($brandName) && trim($brandName) !== ''&& trim($brandName) !== '0');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== ''&& trim($modelcode) !== '0');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== ''&& trim($subCatMaker) !== '0');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== ''&& trim($subModelName) !== '0');
    $isSuppliercodeValid = (isset($suppliercode) && trim($suppliercode) !== '' && trim($suppliercode) !== '0');

    $sqlSubCategory = "SELECT ModelName, Count(1) totalRow FROM supcatalogue AS cat"
    . " JOIN supref on cat.Supcd = supref.supno and cat.Owner_Comp = supref.Owner_Comp ";

    //Start condition
    $condition = 'WHERE cat.Owner_Comp = "' . $comp . '"  AND cusno = "' .$cusno.'"';
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

    if ($isbrandNameValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand = '$brandName'";
    }

    if ($isModelCodeValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "ModelCode = '$modelcode'";
    }

    if($isSuppliercodeValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Supcd = '$suppliercode'";

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


function ctc_get_supcatalogue_forcus($carmaker, $modelname, $modelcode, $subCatMaker, $subModelName, $Brand, $Supplier,$cusno ,$search) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();

    $isCarMakerValid = (isset($carmaker) && trim($carmaker) !== ''&& trim($carmaker) !== '0');
    $isModelNameValid = (isset($modelname) && trim($modelname) !== '' && trim($modelname) !== '0');
    $isModelCodeValid = (isset($modelcode) && trim($modelcode) !== ''&& trim($modelcode) !== '0');
    $isSubCatMakerValid = (isset($subCatMaker) && trim($subCatMaker) !== '' && trim($subCatMaker) !== '0');
    $isSubModelNameValid = (isset($subModelName) && trim($subModelName) !== ''&& trim($subModelName) !== '0');

    $isBrandValid = (isset($Brand) && trim($Brand) !== '' && trim($Brand) !== '0');
    $isSubSupplierValid = (isset($Supplier) && trim($Supplier) !== ''&& trim($Supplier) !== '0');
    $isSearchValid = (isset($search) && trim($search) !== '');

    //--------------TODO Check ERP status if 0 use 'qbm008pr' else use 'bm008pr'-------------------//
    /*$sql = " SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO
    , (SELECT Price FROM supprice WHERE prtno = cat.ordprtno AND Owner_Comp = '" . $comp . "' AND Cusno =  '" . $cusno . "'  LIMIT 1) AS SellPrice
    , (SELECT StockQty FROM supstock WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno ) AS StockQty
    ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno
    FROM supcatalogue AS cat 
     JOIN supref on cat.Supcd = supref.supno ";*/
     $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
        . " ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO "
        . " , IFNULL( (SELECT supprice.Price "
        . "    FROM supprice  join supref on "
        . "     supprice.Owner_comp = supref.Owner_comp "
        . "      and supprice.supno = supref.supno and supprice.Cusno = supref.Cusno and supprice.shipto = supref.shipto "
        . "     WHERE  cat.ordprtno = supprice.partno  AND supprice.Owner_Comp = cat.Owner_Comp and cat.Supcd = supprice.supno  "
        . "      AND supprice.Owner_Comp = '" . $comp . "' AND supprice.Cusno = '" . $cusno . "'  limit 1),'-') AS stdprice "
        . " , IFNULL(( "
        . "     SELECT StockQty FROM supstock  "
        . "     WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno  and cat.Owner_Comp = supstock.Owner_Comp "
        . " ),'-') AS StockQty "
        . " ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno "
        . " FROM supcatalogue AS cat "
        . " JOIN supref on cat.Supcd = supref.supno and  cat.Owner_Comp = supref.Owner_Comp";

    //    Start condition
    $condition = "WHERE cat.Owner_Comp = '" . $comp . "'  AND cusno = '".$cusno."'  ";
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
    if ($isBrandValid) {
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Brand  = '$Brand'";
    }


    if($isSubSupplierValid){
    
        if (isset($condition) && trim($condition) !== '') {
            $condition = $condition . " && ";
        }
        $condition = $condition . "Supcd = '$Supplier'";

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
                . "Vincode LIKE '%$search%' || "
                . "Cprtn LIKE '%$search%' || "
                . "Prtno LIKE '%$search%' || "
                . "ordprtno LIKE '%$search%' || "
                . "Brand LIKE '%$search%' || "
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


function ctc_get_current_supcart_item($customerNo, $comp) {
    $ctcdb = new ctcdb();

    $sqlCount = "SELECT Count(1) totalrow FROM supshoppingcart"
            . " WHERE Cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "'";
    $sthCount = $ctcdb->db->prepare($sqlCount);
    $sthCount->execute();
    $resultCount = $sthCount->fetchAll(PDO::FETCH_ASSOC);

    $totalRecord = (int) $resultCount[0]['totalrow'];

    return $totalRecord;
}

function ctc_save_supshoppingcart($customerNo, $comp, $id, $quantity, $datetime, $supno) {
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

        $sqlCount = "SELECT qty FROM supshoppingcart"
                . " WHERE cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "' AND ID='" . $id . "' AND supno='" . $supno . "' ";
        $sthCount = $ctcdb->db->prepare($sqlCount);
        $sthCount->execute();
        $resultCount = $sthCount->fetchAll(PDO::FETCH_ASSOC);
        $qty = (int) $resultCount[0]['qty'];

        $sql = '';
        if ($qty == 0) {
            $sql = "INSERT INTO supshoppingcart (Owner_Comp, cusno, ID, supno, qty, TransactionDate)"
                    . " VALUES ('" . $comp . "','" . $customerNo . "',  '" . $id . "', '" . $supno . "', '" . $quantity . "', '" . $datetime . "')";
            $stmt = $ctcdb->db->prepare($sql);
            $stmt->execute($data);
        } else {
            $quantity = $qty + $quantity;
            $sql = "UPDATE supshoppingcart"
                    . " SET qty='" . $quantity . "', TransactionDate='" . $datetime . "'"
                    . " WHERE cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "' AND ID='" . $id . "' AND supno='" . $supno . "'";
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


function ctc_update_supshoppingcart($customerNo, $comp, $id, $quantity,$datetime,  $supno) {
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

            $quantity = $qty + $quantity;
            $sql = "UPDATE supshoppingcart"
                    . " SET qty='" . $quantity . "', TransactionDate='" . $datetime . "'"
                    . " WHERE cusno='" . $customerNo . "' AND Owner_Comp='" . $comp . "' AND ID='" . $id . "' AND supno='" . $supno . "'";
            $stmt = $ctcdb->db->prepare($sql);
            $stmt->execute($data);

        $ctcdb->db->commit();
    } catch (Exception $e) {
        $ctcdb->db->rollback();
        $errorCode = '9999';
        $message = $e->getMessage();
    }

    $reportClass = new stdClass();
    $reportClass->errorCode = $errorCode;
    $reportClass->message = $message;
    //return $sql;
    return $reportClass;
}

function ctc_get_supcatalogue_by_id_cusno($id) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    /*$sql = 'SELECT ID, Vincode, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, "" Cgprtno, ordprtno, Prtnm, brand, Start, End, Cc, Lotsize '
            . ', (SELECT Price FROM supprice WHERE prtno = cat.ordprtno AND Owner_Comp = "' . $comp . '" AND Cusno = "' . $cusno . '" LIMIT 1) AS SellPrice'
            . ', (SELECT StockQty FROM supstock WHERE cat.supcd = supstock.supno and cat.ordprtno = supstock.partno ) AS StockQty'
            . ' FROM supcatalogue AS cat'
    */
    $sql = "SELECT ID, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cc, Start, End, Cprtn, Prtno,Prtnm,ordprtno,Supcd as supno "
        . " ,Remark,Lotsize, Vincode as vincode, Brand as brand,MTO "
        . " , IFNULL( (SELECT supprice.Price "
        . "    FROM supprice  join supref on "
        . "     supprice.Owner_comp = supref.Owner_comp "
        . "      and supprice.supno = supref.supno and supprice.Cusno = supref.Cusno and supprice.shipto = supref.shipto "
        . "     WHERE  cat.ordprtno = supprice.partno  AND supprice.Owner_Comp = cat.Owner_Comp and cat.Supcd = supprice.supno  "
	    . "      AND supprice.Owner_Comp = '" . $comp . "' AND supprice.Cusno = '".$cusno."' AND supref.Cusno = '".$cusno."'),'-') AS stdprice "
        . " , IFNULL(( "
        . "     SELECT StockQty FROM supstock  "
        . "     WHERE cat.Supcd = supstock.supno and cat.ordprtno = supstock.partno  and cat.Owner_Comp = supstock.Owner_Comp "
        . " ),'-') AS StockQty "
        . " ,'' Prtnohis, '' as Custprthis, '' as cgprtnohis, '' as Cgprtno "
        . " FROM supcatalogue AS cat "
        . " WHERE ID ='" . $id . "' AND Owner_Comp = '" . $comp . "' "
        . " LIMIT 1";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
function ctc_aws_get_supcatalogue_by_id_cusno($id) {
    $ctcdb = new ctcdb();
    $comp = ctc_get_session_comp();
    $cusno = ctc_get_session_cusno();

    /*$sql = 'SELECT ID, Vincode, CarMaker, ModelName, PrtPic, ModelCode, EngineCode, Cprtn, Prtno, "" Cgprtno, ordprtno, Prtnm, brand, Start, End, Cc, Lotsize '
            . ', (SELECT Price FROM supprice WHERE prtno = cat.ordprtno AND Owner_Comp = "' . $comp . '" AND Cusno = "' . $cusno . '" LIMIT 1) AS SellPrice'
            . ', (SELECT StockQty FROM supstock WHERE cat.supcd = supstock.supno and cat.ordprtno = supstock.partno ) AS StockQty'
            . ' FROM supcatalogue AS cat'
    */
    $sql = "
		SELECT
			ID,
			CarMaker,
			ModelName,
			PrtPic,
			ModelCode,
			EngineCode,
			Cc,
			START,
			END,
			Cprtn,
			cat.Prtno,
			Prtnm,
			ordprtno,
			Supcd AS supno,
			Remark,
			Lotsize,
			Vincode AS vincode,
			Brand AS brand,
			MTO,
			supawsexc.price AS stdprice,

			'-' AS StockQty,
			'' Prtnohis,
			'' AS Custprthis,
			'' AS cgprtnohis,
			'' AS Cgprtno,
			supawsexc.sell
			FROM
				supcatalogue AS cat
				LEFT JOIN awscusmas on awscusmas.Owner_Comp = cat.Owner_Comp and awscusmas.cusno2 = '$cusno'
				LEFT JOIN supawsexc on supawsexc.Owner_Comp = cat.Owner_Comp and supawsexc.cusno1 = awscusmas.cusno1 and cat.ordprtno = supawsexc.prtno
				
			WHERE
				ID = '$id' AND cat.Owner_Comp = '$comp'
			LIMIT 1";

    //Query with LIMIT Filter
    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
?>