<style>
.lowercase {
  text-transform: lowercase !important;
}

.badge {
    display: inline-block;
    min-width: 10px;
    padding: 3px 5px;
    font-size: 10px;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color: #777;
    border-radius: 10px;
	margin-left: 3px;
}
.badge-success {
    background-color: #ad1d36 !important;
}
</style>
<?php

require_once('../language/Lang_Lib.php');
//date_default_timezone_set("Asia/Bangkok");
$current = trim($_GET['current']);
//DENSO Menu
echo "<div class=\"hmenu\">";
echo "<div class=\"headerbar\">" . get_lng($_SESSION["lng"], "L0021") . "</div>";
echo "<ul>";


//Commemt at Dec 2022
//Edit for AWS

require('db/conn.inc');
	

if ($comp=='MA0'){
	$querymenu_request = "SELECT `Owner_Comp`, `ordtype`, `setday`, `endday`, menu_sts FROM duedate  WHERE Owner_Comp = '".$comp."' and ordtype = 'R'";
    $sqlmenu_request  = mysqli_query($msqlcon,$querymenu_request);
    if ($ArrayRequest = mysqli_fetch_array($sqlmenu_request)) {
        $menu_request  = $ArrayRequest['menu_sts'];
        // echo "Request".  $menu_request. "<br/>";
        if($menu_request == '1'){
            if ($current == 'requestDueDate') {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li id=\"current\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";
            }
        }
    }

    if($current=='cataloguemain'){
        echo "<li id=\"current\"><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") . "</a></li>";
    }else{
        echo "<li><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") ."</a></li>";	
    }

}


// elseif ($comp=='IN0'){
	// $querymenu_normal = "SELECT `Owner_Comp`, `ordtype`, `setday`, `endday`, menu_sts FROM duedate  WHERE Owner_Comp = '".$comp."' and ordtype = 'N'";
    // $sqlmenu_normal = mysqli_query($msqlcon,$querymenu_normal);
    // if ($ArrayNormal = mysqli_fetch_array($sqlmenu_normal)) {
        // $menu_normal  = $ArrayNormal['menu_sts'];
       // // echo "Normal".  $menu_normal . "<br/>";
        // if($menu_normal == '1'){
            // if ($current == 'main') {
                // $url = "main.php?" . paramEncrypt("ordertype=Normal");
                // echo "<li id=\"current\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0022") . "</a></li>";
            // } else {
                // $url = "main.php?" . paramEncrypt("ordertype=Normal");
                // echo "<li> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0022") . "</a></li>";
            // }
        // }
    // }
// }

else{
	
	
	
	
	
    $querymenu_normal = "SELECT `Owner_Comp`, `ordtype`, `setday`, `endday`, menu_sts FROM duedate  WHERE Owner_Comp = '".$comp."' and ordtype = 'N'";
    $sqlmenu_normal = mysqli_query($msqlcon,$querymenu_normal);
    if ($ArrayNormal = mysqli_fetch_array($sqlmenu_normal)) {
        $menu_normal  = $ArrayNormal['menu_sts'];
       // echo "Normal".  $menu_normal . "<br/>";
        if($menu_normal == '1'){
            if ($current == 'main') {
                $url = "main.php?" . paramEncrypt("ordertype=Normal");
                echo "<li id=\"current\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0022") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Normal");
                echo "<li> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0022") . "</a></li>";
            }
        }
    }
    

    $querymenu_request = "SELECT `Owner_Comp`, `ordtype`, `setday`, `endday`, menu_sts FROM duedate  WHERE Owner_Comp = '".$comp."' and ordtype = 'R'";
    $sqlmenu_request  = mysqli_query($msqlcon,$querymenu_request);
    if ($ArrayRequest = mysqli_fetch_array($sqlmenu_request)) {
        $menu_request  = $ArrayRequest['menu_sts'];
        // echo "Request".  $menu_request. "<br/>";
        if($menu_request == '1'){
            if ($current == 'requestDueDate') {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li id=\"current\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";
            }
        }
    }




    if($current=='cataloguemain'){
        echo "<li id=\"current\"><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") . "</a></li>";
    }else{
        echo "<li><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") ."</a></li>";	
    }

    // --start-- 03/10/2019 Prachaya inphum CTC
    // --start-- 25/10/2019 Prachaya inphum CTC
    $currenttime = date('H:i') ; //currenttime
    //$checkTimeDis = date('H:i',strtotime('17:03')); //time to disable menu
    //$checkTimeEna = date('H:i',strtotime('23:59')); //time to enable menu

    //CTC--Get time disable function from database 17/09/2020
  
    $setdayQry="select * from duedate where ordtype='U' and Owner_Comp='$comp' ";
    $setdaysql=mysqli_query($msqlcon,$setdayQry);
    $time="";
    $endtime="";
    if($result = mysqli_fetch_array ($setdaysql)){
        $time=$result['setday'];
        $endtime=$result['endday'];
        $menu_urgent  = $result['menu_sts'];
    }else{
        $time = "00:00";
        $endtime = "00:00";
    }

    $checkTimeDis = date('H:i',strtotime($time)); //time to disable menu
    $checkTimeEna = date('H:i',strtotime($endtime)); //time to enable menu
if($menu_urgent == '1'){
    if ($checkTimeDis < $checkTimeEna) {
        if ($current == 'urgentOrder') {
            if ($currenttime >= $checkTimeDis && $currenttime < $checkTimeEna) {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li id=\"current\" class=\"urgent disabled\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li id=\"current\" class=\"urgent\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            }
        } else {
            if ($currenttime >= $checkTimeDis && $currenttime < $checkTimeEna) {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li class=\"urgent disabled\"> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li class=\"urgent\"> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            }
        }
    } else if ($checkTimeDis > $checkTimeEna) {
        if ($current == 'urgentOrder') {
            if ($currenttime >= $checkTimeDis || $currenttime < $checkTimeEna) {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li id=\"current\" class=\"urgent disabled\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li id=\"current\" class=\"urgent\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            }
        } else {
            if ($currenttime >= $checkTimeDis || $currenttime < $checkTimeEna) {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li class=\"urgent disabled\"> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                echo "<li class=\"urgent\"> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            }
        }
    } else {
            if ($current == 'urgentOrder') {
                    $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                    echo "<li id=\"current\" class=\"urgent\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            } else {
                    $url = "main.php?" . paramEncrypt("ordertype=Urgent");
                    echo "<li class=\"urgent\"> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0024") . "</a></li>";
            }
    }
}
}
// 25/10/2019 Prachaya inphum CTC --end--
// 03/10/2019 Prachaya inphum CTC --end--


/*
if ($current == 'advancedOrder') {
    echo "<li id=\"current\"><a href=\"mainen_advance.php\">" . get_lng($_SESSION["lng"], "L0025") . "</a></li>";
} else {
    echo "<li> <a href=\"mainen_advance.php\">" . get_lng($_SESSION["lng"], "L0025") . "</a></li>";
}
 Campaign Order close because DSTH do not want to start
if($current=='mainAdd'){
echo "<li id=\"current\"><a href=\"mainadd.php\" target=\"_self\">Campaign Order</a></li>";
}else{
echo "<li><a href=\"mainadd.php\" target=\"_self\">Campaign Order</a></li>";
}*/

if ($current == 'History') {
    echo "<li id=\"current\"><a href=\"history.php\" target=\"_self\">" . get_lng($_SESSION["lng"], "L0026") . "</a></li>";
} else {
    echo "<li><a href=\"history.php\" target=\"_self\">" . get_lng($_SESSION["lng"], "L0026") . "</a></li>";
}

if ($current == 'mainrpt') {
    echo "<li id=\"current\"><a href=\"mainrpt.php\">" . get_lng($_SESSION["lng"], "L0028") . "</a></li>";
} else {
    echo "<li><a href=\"mainrpt.php\">" . get_lng($_SESSION["lng"], "L0028") . "</a></li>";
}

echo "</ul>";


//start NON DENSO

if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='VN0' ){
}
else {

echo "</ul>";
echo "<div class=\"headerbar2\">" . get_lng($_SESSION["lng"], "M0001") . "</div>";
echo "<ul>";
if ($current == 'supcata_cataloguemain') {
    echo "<li id=\"current\"><a href=\"supcata_cataloguemain.php\">" . get_lng($_SESSION["lng"], "M0002") . "</a></li>";
} else {
    echo "<li><a href=\"supcata_cataloguemain.php\">" . get_lng($_SESSION["lng"], "M0002") . "</a></li>";
}
if ($current == 'suphistory') {
    echo "<li id=\"current\"><a href=\"suphistory.php\">" . get_lng($_SESSION["lng"], "M0003") . "</a></li>";
} else {
    echo "<li><a href=\"suphistory.php\">" . get_lng($_SESSION["lng"], "M0003") . "</a></li>";
}
if ($current == 'supmainrpt') {
    echo "<li id=\"current\"><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M0004") . "</a></li>";
} else {
    echo "<li><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M0004") . "</a></li>";
}
echo "</ul>";

}
//End NON DENSO

//start Comment edit for support AWS 
//edit at Dec 2022
require('db/conn.inc');
// $queryall = "SELECT * FROM `userid` WHERE `Cusno` LIKE '$cusno' AND Owner_Comp = '$comp'";
$queryall = "SELECT * FROM `cusmas` WHERE 1 AND Custype = 'A' and `xDealer` = '$cusno' AND Owner_Comp = '$comp'";
// $queryall = "select * from awsorderhdr inner join cusmas on awsorderhdr.cusno=cusmas.Cusno AND awsorderhdr.Owner_Comp=cusmas.Owner_Comp where awsorderhdr.Dealer ='" . $cusno . "' and cusmas.Owner_Comp ='".$comp."'"; 
$sqlall = mysqli_query($msqlcon,$queryall);
$countall = mysqli_num_rows($sqlall);

$query = "
		SELECT COUNT(*) AS row_count
		FROM (
			SELECT 'awsorderhdr' AS 'table', Owner_Comp, orderdate, 'DENSO' AS supno, 'DENSO' AS supnm, awsorderhdr.CUST3, awsorderhdr.cusno, corno, orderno, ordtype, ordflg, shipto
			FROM awsorderhdr 
			WHERE dealer = '" . $cusno . "' 
			AND ordflg = '' 
			AND awsorderhdr.cusno != awsorderhdr.dealer 
			AND awsorderhdr.Owner_Comp = '" . $comp . "'
			
			UNION
			
			SELECT 'supawsorderhdr' AS 'table', supawsorderhdr.Owner_Comp, orderdate, supawsorderhdr.supno AS supno, supnm, supawsorderhdr.CUST3, supawsorderhdr.cusno, corno, orderno, ordtype, ordflg, shipto
			FROM supawsorderhdr 
			JOIN supmas ON supawsorderhdr.supno = supmas.supno AND supawsorderhdr.Owner_Comp = supmas.Owner_Comp
			WHERE dealer = '" . $cusno . "' 
			AND ordflg = '' 
			AND supawsorderhdr.cusno != supawsorderhdr.dealer 
			AND supawsorderhdr.Owner_Comp = '" . $comp . "'
		) a
		INNER JOIN cusmas ON a.cusno = cusmas.Cusno AND a.Owner_Comp = cusmas.Owner_Comp;
	";

	// Execute the query and fetch the result
	$result = mysqli_query($msqlcon, $query);
	$row = mysqli_fetch_assoc($result);

	// Retrieve the row count
	$row_count = $row['row_count'];





	
/*

if ($count >= 1) {
    echo "<div class=\"headerbarAws\">Your AWS Order</div>";
    echo "<ul>";
    if ($current == 'mainAws') {
        echo "<li id=\"current\"><a href=\"mainAws.php\">AWS Regular Order</a></li>";
    } else {
        echo "<li><a href=\"mainAws.php\">AWS Regular Order</a></li>";
    }

    if ($current == 'mainAwsAdd') {
        echo "<li id=\"current\"><a href=\"mainAwsadd.php\">AWS Additional Order</a></li>";
    } else {
        echo "<li><a href=\"mainAwsadd.php\">AWS Additional Order</a></li>";
    }
    echo "</ul>";
}
*/

if ($countall >= 1) {
    echo "<div class=\"headerbar2\" style=\"text-transform: none;\">".get_lng($_SESSION["lng"], "M0015")."</div>";
    echo "<ul>";
    if ($current == 'mainAws') {
        echo "<li id=\"current\"><a href=\"mainAws.php\">" . get_lng($_SESSION["lng"], "M0009") ."<span class='badge badge-success'>$row_count</span>    </a></li>";
    } else {
        echo "<li><a href=\"mainAws.php\">" . get_lng($_SESSION["lng"], "M0009") ."<span class='badge badge-success'>$row_count</span> </a></li>";
    }
    if ($current == 'leadtime') {
        echo "<li id=\"current\"><a href=\"leadtime.php\">" . get_lng($_SESSION["lng"], "M0010") . "</a></li>";
    } else {
        echo "<li><a href=\"leadtime.php\">" . get_lng($_SESSION["lng"], "M0010") . "</a></li>";
    }
    if ($current == 'aws_groupma') {
        echo "<li id=\"current\"><a href=\"aws_groupma.php\">" . get_lng($_SESSION["lng"], "M0011") . "</a></li>";
    } else {
        echo "<li><a href=\"aws_groupma.php\">" . get_lng($_SESSION["lng"], "M0011") . "</a></li>";
    }
    if ($current == 'saledenso') {
        echo "<li id=\"current\"><a href=\"aws_saledenso.php\">" . get_lng($_SESSION["lng"], "M0012") . "</a></li>";
    } else {
        echo "<li><a href=\"aws_saledenso.php\">" . get_lng($_SESSION["lng"], "M0012") . "</a></li>";
    }
    if ($current == 'salenondenso') {
        echo "<li id=\"current\"><a href=\"aws_salenondenso.php\">" . get_lng($_SESSION["lng"], "M0013") . "</a></li>";
    } else {
        echo "<li><a href=\"aws_salenondenso.php\">" . get_lng($_SESSION["lng"], "M0013") . "</a></li>";
    }
    if ($current == 'mainrptAws') {
        echo "<li id=\"current\"><a href=\"aws_mainrptAws.php\">" . get_lng($_SESSION["lng"], "M0014") . "</a></li>";
    } else {
        echo "<li><a href=\"aws_mainrptAws.php\">" . get_lng($_SESSION["lng"], "M0014") . "</a></li>";
    }
   /* if ($current == 'mainrptAws') {
        echo "<li id=\"current\"><a href=\"mainrptAws.php\">Summary AWS Order</a></li>";
    } else {
        echo "<li><a href=\"mainrptAws.php\">Summary AWS Order</a></li>";
    }*/
    echo "</ul>";
}

//End AWS

//start PRODUCT INFO Web Link
echo "<ul>";
/*
if ($current == 'mainrpt') {
    echo "<li id=\"current\"><a href=\"mainrpt.php\">" . get_lng($_SESSION["lng"], "L0028") . "</a></li>";
} else {
    echo "<li><a href=\"mainrpt.php\">" . get_lng($_SESSION["lng"], "L0028") . "</a></li>";
}
if ($current == 'mainrptproduct') {
    echo "<li id=\"current\"><a href=\"mainrptproduct.php\">" . get_lng($_SESSION["lng"], "L0029") . "</a></li>";
} else {
    echo "<li><a href=\"mainrptproduct.php\">" . get_lng($_SESSION["lng"], "L0029") . "</a></li>";
}
if ($current == 'mainrptpo') {
    echo "<li id=\"current\"><a href=\"mainrptpo.php\">" . get_lng($_SESSION["lng"], "L0030") . "</a></li>";
} else {
    echo "<li><a href=\"mainrptpo.php\">" . get_lng($_SESSION["lng"], "L0030") . "</a></li>";
}

//New Add by Zia
if ($current == 'mainrptitem') {
    echo "<li id=\"current\"><a href=\"mainrptitem.php\">" . get_lng($_SESSION["lng"], "L0031") . "</a></li>";
} else {
    echo "<li><a href=\"mainrptitem.php\">" . get_lng($_SESSION["lng"], "L0031") . "</a></li>";
}
*/

//AWS Move this menu to 2 nd customer section
/*
if ($countall >= 1) {
    if ($current == 'mainrptAws') {
        echo "<li id=\"current\"><a href=\"mainrptAws.php\">Summary AWS Order</a></li>";
    } else {
        echo "<li><a href=\"mainrptAws.php\">Summary AWS Order</a></li>";
    }
}
*/
//end AWS Move this menu to 2 nd customer section


echo "<div class=\"headerbar3\">" . get_lng($_SESSION["lng"], "M0005") . "</div>";
//if($current=='cataloguemain'){
//	echo "<li id=\"current\"><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") . "</a></li>";
//}else{
//	echo "<li><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") ."</a></li>";	
//}
if($current=='cataloguemainreg'){
	echo "<li id=\"current\"><a href='https://aftermarket.denso.com.sg/' target=\"_blank\">". get_lng($_SESSION["lng"], "L0333") . "</a></li>";
}else{
	echo "<li><a href='https://aftermarket.denso.com.sg/' target=\"_blank\">". get_lng($_SESSION["lng"], "L0333") ."</a></li>";	
}

if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='VN0' ){
}
else {
/*	
if($current=='cataloguemainreg'){
	echo "<li id=\"current\"><a href='https://aftermarket.denso.com.sg/' target=\"_blank\" disabled=\"disabled\">". get_lng($_SESSION["lng"], "M0006") . "</a></li>";
}else{
	echo "<li><a href='' target=\"_blank\" disabled=\"disabled\">". get_lng($_SESSION["lng"], "M0006") ."</a></li>";	
}
if($current=='cataloguemainreg'){
	echo "<li id=\"current\"><a href='https://aftermarket.denso.com.sg/' target=\"_blank\" disabled=\"disabled\">". get_lng($_SESSION["lng"], "M0007") . "</a></li>";
}else{
	echo "<li><a href='https://aftermarket.denso.com.sg/' target=\"_blank\" disabled=\"disabled\">". get_lng($_SESSION["lng"], "M0007") ."</a></li>";	
}
*/
if($current=='cataloguemainreg'){
	echo "<li id=\"current\"><a href='https://koyo.jtekt.co.jp/en/products/field/automotive-aftermarket/' target=\"_blank\">". get_lng($_SESSION["lng"], "M0008") . "</a></li>";
}else{
	echo "<li><a href='https://koyo.jtekt.co.jp/en/products/field/automotive-aftermarket/' target=\"_blank\">". get_lng($_SESSION["lng"], "M0008") ."</a></li>";	
}

}
//END  PRODUCT INFO Web Link


//echo "</ul>";
//echo "<div class=\"headerbar3\">R F Q</div>";
//echo "<ul>";
//if($current=='RFQ'){
//	echo "<li id=\"current\"><a href=\"mainRFQ.php\">Quotation Request</a></li>";
//}else{
//	echo "<li><a href=\"mainRFQ.php\">Quotation Request</a></li>";
//}
//if($current=='RFQHis'){
//	echo "<li id=\"current\"><a href=\"mainRFQHis.php\">History</a></li>";
//}else{
//	echo "<li><a href=\"mainRFQHis.php\">History</a></li>";
//}
//echo "</ul>";
//echo "<div class=\"headerbar3\">Order Answer</div>";
//echo "<ul>";
//if($current=='feedback'){
//	echo "<li id=\"current\"><a href=\"feedback.php\">Regular Order</a></li>";
//}else{
//	echo "<li><a href=\"feedback.php\">Regular Order</a></li>";
//}
//echo "</ul>";

//NON DENSO Menu
/*
if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='XVN0' || $comp=='XVN0' ){
}
else {
//start Add new menu 2021 April
echo "</ul>";
echo "<div class=\"headerbar2\">" . get_lng($_SESSION["lng"], "M0001") . "</div>";
echo "<ul>";
if ($current == 'supcata_cataloguemain') {
    echo "<li id=\"current\"><a href=\"supcata_cataloguemain.php\">" . get_lng($_SESSION["lng"], "M0002") . "</a></li>";
} else {
    echo "<li><a href=\"supcata_cataloguemain.php\">" . get_lng($_SESSION["lng"], "M0002") . "</a></li>";
}
if ($current == 'suphistory') {
    echo "<li id=\"current\"><a href=\"suphistory.php\">" . get_lng($_SESSION["lng"], "M0003") . "</a></li>";
} else {
    echo "<li><a href=\"suphistory.php\">" . get_lng($_SESSION["lng"], "M0003") . "</a></li>";
}
if ($current == 'supmainrpt') {
    echo "<li id=\"current\"><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M0004") . "</a></li>";
} else {
    echo "<li><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M0004") . "</a></li>";
}
echo "</ul>";
//End Add new menu 2021 April
}
*/
echo "</div>";

?>