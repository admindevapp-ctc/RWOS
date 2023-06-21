<?
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
echo '<ul>';

if($current=='mainCusAdm'){
echo '<li id="current"><a href="maincusadm.php">Customer Maintenance</a></li>';
}else{
echo '<li ><a href="maincusadm.php">Customer Maintenance</a></li>';
}
if($current=='mainCusRemAdm'){
echo '<li id="current"><a href="mainCusRemAdm.php">Customer Remark</a></li>';
}else{
echo '<li ><a href="mainCusRemAdm.php">Customer Remark</a></li>';
}


if($current=='mainItem'){
echo '<li id="current"><a href="mainitem.php">Item Maintenance</a></li>';
}else{
echo '<li><a href="mainitem.php">Item Maintenance</a></li>';
}
if($current=='mainUsrAdm'){
echo '<li id="current"><a href="mainUsrAdm.php">User Maintenance</a></li>';
}else{
echo '<li><a href="mainUsrAdm.php">User Maintenance</a></li>';
}
if($current=='mainCutAdm'){
echo '<li id="current"><a href="mainCutAdm.php">Cut Of Date</a></li>';
}else{
echo '<li><a href="mainCutAdm.php">Cut Of Date</a></li>';
}
if($current=='mainExcAdm'){
echo '<li id="current"><a href="mainExcAdm.php">Exchange Rate</a></li>';
}else{
echo '<li><a href="mainExcAdm.php">Exchange Rate</a></li>';
}
if($current=='mainPhaseAdm'){
echo '<li id="current"><a href="mainPhaseAdm.php">Phase Out </a></li>';
}else{
echo '<li><a href="mainPhaseAdm.php">Phase Out</a></li>';
}


if($current=='mainSlsPlnAdm'){
echo '<li id="current"><a href="mainSlsPlnAdm.php">Sales Plan</a></li>';
}else{
echo '<li><a href="mainSlsPlnAdm.php">Sales Plan</a></li>';
}

if($current=='mainSlsAdm'){
echo '<li id="current"><a href="mainSlsAdm.php">Sales Price</a></li>';
}else{
echo '<li><a href="mainSlsAdm.php">Sales Price</a></li>';
}

if($current=='mainSlsAwsAdm'){
echo '<li id="current"><a href="mainSlsawsAdm.php">AWS Sales Price</a></li>';
}else{
echo '<li><a href="mainSlsawsAdm.php">AWS Sales Price</a></li>';
}

if($current=='mainSpecialAdm'){
echo '<li id="current"><a href="mainSpecialAdm.php">Special Price</a></li>';
}else{
echo '<li><a href="mainSpecialAdm.php">Special Price</a></li>';
}
if($current=='mainSpecialAwsAdm'){
echo '<li id="current"><a href="mainSpecialAwsAdm.php">AWS Special Price</a></li>';
}else{
echo '<li><a href="mainSpecialAwsAdm.php">AWS Special Price</a></li>';
}
//Part catalogue
if($current=='partcatalogue'){
echo '<li id="current"><a href="partcatalogue.php">Catalogue Maintenance</a></li>';
}else{
echo '<li><a href="cata_partcatalogue.php">Catalogue Maintenance</a></li>';
}

//Announcement
if($current=='announcement'){
echo '<li id="current"><a href="anna_mainadm.php">Announcement</a></li>';
}else{
echo '<li><a href="anna_mainadm.php">Announcement</a></li>';
}

echo '</ul>';
//echo '<div class="headerbar3">FeedBack</div>';
//echo '<ul>';
//if($current=='mainFedAdm'){
//echo '<li id="current"><a href="mainfedadm.php">FeedBack Database</a></li>';
//}else{
//echo '<li><a href="mainfedadm.php">FeedBack Database</a></li>';
//}
//echo '</ul>';
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

if ($comp=='PH0' ||$comp=='MA0' || $comp=='IN0' || $comp=='PH0' || $comp=='XM0' || $comp=='VN0' ){
}
else {
//start comment add new menu for admin
// 23 Mar 2021
echo '</ul>';
echo "<div class=\"headerbar2\">Order Non Denso</div>";
echo "<ul>";
if($current=='sup_mainadm'){
	echo "<li id=\"current\"><a href=\"sup_mainadm.php\">Supplier MA</a></li>";
}else{
	echo "<li><a href=\"sup_mainadm.php\">Supplier MA</a></li>";
}
if($current=='sup_mainref'){
	echo "<li id=\"current\"><a href=\"sup_mainref.php\">Supplier & Customer Ref</a></li>";
}else{
	echo "<li><a href=\"sup_mainref.php\">Supplier & Customer Ref</a></li>";
}
if($current=='supmainrpt'){
	echo "<li id=\"current\"><a href=\"supmainrpt.php\">Order History</a></li>";
}else{
	echo "<li><a href=\"supmainrpt.php\">Order History</a></li>";
}
if($current=='supcata_cataloguemain'){
	echo "<li id=\"current\"><a href=\"supcata_cataloguemain.php\">Supplier Catalogue</a></li>";
}else{
	echo "<li><a href=\"supcata_cataloguemain.php\">Supplier Catalogue</a></li>";
}
if($current=='sup_mainSlsAdm'){
	echo "<li id=\"current\"><a href=\"sup_mainSlsAdm.php\">Supplier Price</a></li>";
}else{
	echo "<li><a href=\"sup_mainSlsAdm.php\">Supplier Price</a></li>";
}
echo '</ul>';
//End comment
}
echo '</div>';

?>