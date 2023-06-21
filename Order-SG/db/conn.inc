<?
{ $host = "order.denso.com"; }
$host = "order.denso.com";

$user = "root";
$pass = "P@ssw0rD";

{ $dbnm = "ordering-sg"; }
$dbnm = "ordering-sg";
$msqlcon = mysqli_connect ($host, $user, $pass);
if ($msqlcon) {
   	$buka = mysqli_select_db ($msqlcon,$dbnm);
	mysqli_set_charset( $msqlcon, 'utf8');
	if (!$buka) {
	 	die ("Database tidak dapat dibuka");
	  }
	} else {
	die ("Server MySQL tidak terhubung");
  }

?>
