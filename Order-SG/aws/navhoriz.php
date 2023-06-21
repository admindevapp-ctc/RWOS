<?php

require_once('../../language/Lang_Lib.php');
require_once('../../core/ctc_cookie.php');

$current = trim($_GET['selection']);

echo "<ul>";

if ($current == 'requestDueDate') {
    echo "<li id=\"current\"><a href=\"main.php?" . paramEncrypt("ordertype=Request"). "\" target=\"_self\">".get_lng($_SESSION["lng"], "L0017")."</a></li>";
} else {
    echo "<li><a href='main.php?"."ordertype=Request"."' target=\"_self\">".get_lng($_SESSION["lng"], "L0017")."</a></li>";
}
if ($current == 'profile') {
    echo "<li id=\"current\"><a href=\"Profile.php\" target=\"_self\">".get_lng($_SESSION["lng"], "L0018")."</a></li>";
} else {
    echo "<li><a href=\"Profile.php\" target=\"_self\">".get_lng($_SESSION["lng"], "L0018")."</a></li>";
}
if ($current == 'part') {
    echo "<li id=\"current\"><a href=\"mainitem.php\" target=\"_self\">".get_lng($_SESSION["lng"], "L0019")."</a></li>";
} else {
    echo "<li><a href=\"mainitem.php\" target=\"_self\">".get_lng($_SESSION["lng"], "L0019")."</a></li>";
}

echo "<li ><a href=\"logout.php\" target=\"_self\">".get_lng($_SESSION["lng"], "L0020")."</a></li>";
echo "</ul>";
?>