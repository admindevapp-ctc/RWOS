<?php

$current=trim($_GET['current']);
require_once('../../core/ctc_cookie.php');

//echo '<ul>';
//if($current=='mainAdm'){
//	echo '<li id="current"><a href="mainAdm.php">Regular Order</a></li>';
//}else{
//	echo '<li ><a href="mainAdm.php">Regular Order</a></li>';
//}
//if($current=='mainaddAdm'){
//	echo '<li id="current"><a href="mainaddAdm.php" target="_self">Additional Order</a></li>';
//}else{
//	echo '<li><a href="mainaddAdm.php" target="_self">Additional Order</a></li>';
//}

//if($current=='mainAllAdm'){
//	echo '<li id="current"><a href="mainAllAdm.php" target="_self">All Order</a></li>';
//}else{
//	echo '<li ><a href="mainAllAdm.php" target="_self">All Order</a></li>';
//}
//echo '</ul>';
//echo '<div class="headerbar1">Master</div>';
echo "<ul>";
if($current=='mainRFQ'){
	echo "<li id=\"current\"><a href=\"mainRFQ.php\">RFQ Administration</a></li>";
}else{
	echo "<li><a href=\"mainRFQ.php\">RFQ Administration</a></li>";
}
if($current=='mainRFQHis'){
	echo "<li id=\"current\"><a href=\"mainRFQHis.php\">RFQ History</a></li>";
}else{
	echo "<li><a href=\"mainRFQHis.php\">RFQ History</a></li>";
}
echo "</ul>";
echo "<div class=\"headerbar2\">Summary Order</div>";
echo "<ul>";
if($current=='mainrpt'){
	echo "<li id=\"current\"><a href=\"mainrptAdm.php\">Detail Summary</a></li>";
}else{
	echo "<li><a href=\"mainrptAdm.php\">Detail Summary</a></li>";
}
if($current=='mainrptproduct'){
	echo "<li id=\"current\"><a href=\"mainrptproduct.php\">by Product</a></li>";
}else{
	echo "<li><a href=\"mainrptproduct.php\">by Product</a></li>";
}
if($current=='mainrptpo'){
	echo "<li id=\"current\"><a href=\"mainrptpo.php\">by Purchase Order</a></li>";
}else{
	echo "<li><a href=\"mainrptpo.php\">by Purchase Order</a></li>";
}
if($current=='mainrptSlsAct'){
	echo "<li id=\"current\"><a href=\"mainrptSlsAct.php\">Plan Vs Actual</a></li>";
}else{
	echo "<li><a href=\"mainrptSlsAct.php\">Plan Vs Actual</a></li>";
}

//Part catalogue
echo "</ul>";
echo "<div class=\"headerbar2\">Part Catalogue</div>";
echo "<ul>";
if($current=='partcatalogue'){
	echo "<li id=\"current\"><a href=\"cata_cataloguemain.php\">Part Catalogue</a></li>";
}else{
	echo "<li><a href=\"cata_cataloguemain.php\">Part Catalogue</a></li>";
}
if($current=='cataloguemainreg'){
	echo "<li id=\"current\"><a href='https://aftermarket.denso.com.sg/' target=\"_blank\">Product Info.(Regional)</a></li>";
}else{
	echo "<li><a href='https://aftermarket.denso.com.sg/' target=\"_blank\">Product Info.(Regional)</a></li>";	
}

//echo "</ul>";
if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='VN0' ){
}
else {
//non denso
echo "</ul>";
echo "<div class=\"headerbar2\">Order Non Denso</div>";
echo "<ul>";
if($current=='supcatalogue'){
	echo "<li id=\"current\"><a href=\"supcata_cataloguemain.php\">Supplier Catalogue</a></li>";
}else{
	echo "<li><a href=\"supcata_cataloguemain.php\">Supplier Catalogue</a></li>";
}
if($current=='orderhistory'){
	echo "<li id=\"current\"><a href='supmainrpt.php' target=\"_blank\">Order History</a></li>";
}else{
	echo "<li><a href='supmainrpt.php'>Order History</a></li>";	
}

echo "</ul>";

}
echo '</div>';

?>