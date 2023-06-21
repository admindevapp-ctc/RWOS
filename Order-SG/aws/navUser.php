<?php

require_once('../../language/Lang_Lib.php');
$current = trim($_GET['current']);
echo "<div class=\"hmenu\">";
echo "<div class=\"headerbar\">" . get_lng($_SESSION["lng"], "L0021") . "</div>";//ORDER TO DENSO
echo "<ul>";

require('../db/conn.inc');
/*
if ($comp=='MA0'){
	$querymenu_request = "SELECT `Owner_Comp`, `ordtype`, `setday`, `endday`, menu_sts FROM duedate  WHERE Owner_Comp = '".$comp."' and ordtype = 'R'";
    $sqlmenu_request  = mysqli_query($msqlcon,$querymenu_request);
    if ($ArrayRequest = mysqli_fetch_array($sqlmenu_request)) {
        $menu_request  = $ArrayRequest['menu_sts'];
        if($menu_request == '1'){
            if ($current == 'requestDueDate') {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li id=\"current\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";//Requested Due Date Order
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";//Requested Due Date Order
            }
        }
    }

    if($current=='cataloguemain'){
        echo "<li id=\"current\"><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") . "</a></li>";//Calalogue Order
    }else{
        echo "<li><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") ."</a></li>";	//Calalogue Order
    }

}
*/
    

    $querymenu_request = "SELECT * FROM awsduedate  WHERE Owner_Comp = '".$comp."' and ordtype = 'R'";
    $sqlmenu_request  = mysqli_query($msqlcon,$querymenu_request);
    if ($ArrayRequest = mysqli_fetch_array($sqlmenu_request)) {
        $menu_request  = $ArrayRequest['menu_sts'];
        // echo "Request".  $menu_request. "<br/>";
        if($ArrayRequest){
            if ($current == 'requestDueDate') {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li id=\"current\"><a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";//Requested Due Date Order
            } else {
                $url = "main.php?" . paramEncrypt("ordertype=Request");
                echo "<li> <a href=\"$url\">" . get_lng($_SESSION["lng"], "L0023") . "</a></li>";//Requested Due Date Order
            }
        }
    }




    if($current=='cataloguemain'){
        echo "<li id=\"current\"><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") . "</a></li>";//Calalogue Order
    }else{
        echo "<li><a href=\"cata_cataloguemain.php\" target=\"_self\">". get_lng($_SESSION["lng"], "L0325") ."</a></li>";	//Calalogue Order
    }



if ($current == 'History') {
    echo "<li id=\"current\"><a href=\"history.php\" target=\"_self\">" . get_lng($_SESSION["lng"], "L0026") . "</a></li>";//Order History
} else {
    echo "<li><a href=\"history.php\" target=\"_self\">" . get_lng($_SESSION["lng"], "L0026") . "</a></li>";//Order History
}

if ($current == 'mainrpt') {
    echo "<li id=\"current\"><a href=\"mainrpt.php\">" . get_lng($_SESSION["lng"], "L0028") . "</a></li>";//Detail Report
} else {
    echo "<li><a href=\"mainrpt.php\">" . get_lng($_SESSION["lng"], "L0028") . "</a></li>";//Detail Report
}

echo "</ul>";


//start NON DENSO

// if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='VN0' ){ // Zia's open supplier order menu
// }else {

	echo "</ul>";
	echo "<div class=\"headerbar2\">" . get_lng($_SESSION["lng"], "M0001") . "</div>";//ORDER TO PARTNERS
	echo "<ul>";
	if ($current == 'supcata_cataloguemain') {
		echo "<li id=\"current\"><a href=\"supcata_cataloguemain.php\">" . get_lng($_SESSION["lng"], "M0002") . "</a></li>";//Catalogue Order
	} else {
		echo "<li><a href=\"supcata_cataloguemain.php\">" . get_lng($_SESSION["lng"], "M0002") . "</a></li>";//Catalogue Order
	}
	if ($current == 'suphistory') {
		echo "<li id=\"current\"><a href=\"suphistory.php\">" . get_lng($_SESSION["lng"], "M0003") . "</a></li>";//Order History
	} else {
		echo "<li><a href=\"suphistory.php\">" . get_lng($_SESSION["lng"], "M0003") . "</a></li>";//Order History
	}
	if ($current == 'supmainrpt') {
		echo "<li id=\"current\"><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M0004") . "</a></li>";//Detail Report
	} else {
		echo "<li><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M0004") . "</a></li>";//Detail Report
	}
	echo "</ul>";

// }
//End NON DENSO

echo "<ul>";


echo "<div class=\"headerbar3\">" . get_lng($_SESSION["lng"], "M0005") . "</div>";//PRODUCT INFO <br>(Web Link)

if($current=='cataloguemainreg'){
	echo "<li id=\"current\"><a href='https://aftermarket.denso.com.sg/' target=\"_blank\">". get_lng($_SESSION["lng"], "L0333") . "</a></li>";//DENSO Products
}else{
	echo "<li><a href='https://aftermarket.denso.com.sg/' target=\"_blank\">". get_lng($_SESSION["lng"], "L0333") ."</a></li>";	//DENSO Products
}

if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='VN0' ){
}
else {
    if($current=='cataloguemainreg'){
        echo "<li id=\"current\"><a href='https://koyo.jtekt.co.jp/en/products/field/automotive-aftermarket/' target=\"_blank\">". get_lng($_SESSION["lng"], "M0008") . "</a></li>";//JTEKT/Koyo Products
    }else{
        echo "<li><a href='https://koyo.jtekt.co.jp/en/products/field/automotive-aftermarket/' target=\"_blank\">". get_lng($_SESSION["lng"], "M0008") ."</a></li>";	//JTEKT/Koyo Products
    }
}
//END  PRODUCT INFO Web Link


echo "</div>";

?>