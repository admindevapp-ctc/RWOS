<?php
$host = "order.denso.com";
$host = "order.denso.com";

$user = "root";
$pass = "P@ssw0rD";
$dbnm = "ordering-sg";

$conn = mysql_connect ($host, $user, $pass);
if ($conn) {
	$buka = mysqli_select_db ($dbnm);
	if (!$buka) {
		die ("Database tidak dapat dibuka");	
	}
} else {
	die ("Server MySQL tidak terhubung");	
}

?>