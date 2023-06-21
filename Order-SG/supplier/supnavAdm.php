<?
$current=trim($_GET['current']);
//require_once('../../core/ctc_init.php');
//require_once('../../core/ctc_cookie.php');

echo '<h3 class="headerbar">'. get_lng($_SESSION["lng"], "MH01") .'</h3>';
echo '<ul>';

//Customer Detail
if($current=='supmainCusAdm'){
echo '<li id="current"><a href="supmaincusadm.php">' . get_lng($_SESSION["lng"], "M005") . '</a></li>';
}else{
echo '<li ><a href="supmaincusadm.php">' . get_lng($_SESSION["lng"], "M005") . '</a></li>';
}

//Sales Price
if($current=='supmainSlsAdm'){
echo '<li id="current"><a href="sup_mainSlsAdm.php">' . get_lng($_SESSION["lng"], "M006") . '</a></li>';
}else{
echo '<li><a href="sup_mainSlsAdm.php">' . get_lng($_SESSION["lng"], "M006") . '</a></li>';
}

//Catalogue Maintenance 
if($current=='supCatalogue'){
echo '<li id="current"><a href="supcata_cataloguemain.php">' . get_lng($_SESSION["lng"], "M007") . ' </a></li>';
}else{
echo '<li><a href="supcata_cataloguemain.php">' . get_lng($_SESSION["lng"], "M007") . '</a></li>';
}

//Stock
if($current=='supstockAdm'){
    echo '<li id="current"><a href="supstock_mainadm.php">' . get_lng($_SESSION["lng"], "M008") . '</a></li>';
    }else{
    echo '<li><a href="supstock_mainadm.php">' . get_lng($_SESSION["lng"], "M008") . '</a></li>';
    }

    
//Announcement
if($current=='supannouncement'){
echo '<li id="current"><a href="supanna_mainadm.php">' . get_lng($_SESSION["lng"], "M009") . '</a></li>';
}else{
echo '<li><a href="supanna_mainadm.php">' . get_lng($_SESSION["lng"], "M009") . '</a></li>';
}

echo '</ul>';

echo "<div class=\"headerbar2\">" . get_lng($_SESSION["lng"], "MH02") . "</div>";
echo "<ul>";
if($current=='supmainrpt'){
	echo "<li id=\"current\"><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M010") . "</a></li>";
}else{
	echo "<li><a href=\"supmainrpt.php\">" . get_lng($_SESSION["lng"], "M010") . "</a></li>";
}
echo '</ul>';
//End comment

echo '</div>';

?>