<html>
<head>
</head>
<body>
<?
ini_set('display_errors',FALSE);
$host="order.denso.com";
$user="root";
$pass="P@ssw0rD";
$db="ordering";


$koneksi=mysql_connect($host,$user,$pass);
$tanggal=date("Y-m-d H:i:s");

if ($koneksi)
{
	//echo "berhasil : )";
}else{
	?><script language="javascript">alert("Gagal Koneksi Database MySql !!")</script><?
}

?>

</body>
</html>
