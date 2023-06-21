<?php
$step=trim($_GET['step']);
if($step == "1"){
  echo   '<ul>';  
  echo   '<li id="current"><a href="supmaincusadm.php" target="_self">' . get_lng($_SESSION["lng"], "L0443") .'</a></li>';  
  echo   '<li><a href="supprofile.php" target="_self">' . get_lng($_SESSION["lng"], "L0018") . '</a></li>';  
  echo   '<li ><a href="../logout.php" target="_self">' . get_lng($_SESSION["lng"], "L0020") . '</a></li>';   
  echo   '</ul>';  
}
else if($step == "2"){
  echo   '<ul>';  
  echo   '<li id="current"><a href="../supmaincusadm.php" target="_self">' . get_lng($_SESSION["lng"], "L0443") .'</a></li>';  
  echo   '<li><a href="../supprofile.php" target="_self">' . get_lng($_SESSION["lng"], "L0018") . '</a></li>';  
  echo   '<li ><a href="../../logout.php" target="_self">' . get_lng($_SESSION["lng"], "L0020") . '</a></li>';   
  echo   '</ul>';  
}
?>