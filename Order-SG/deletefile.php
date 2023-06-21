<?php
require('db/conn.inc');
$full  = "server/php/files/";
$thumbs = "server/php/files/thumbnail/";

$filename=$_GET['filename'];
$corno=$_GET['corno'];
$shpno=$_GET['shpno'];
$filefull=$full.$filename;
$filetum=$thumbs.$filename;
if(file_exists($filefull))
{
	unlink($filefull);
	if(file_exists($filetum))
	{
		unlink($filetum);
	}
}
$query="delete from attachment where trim(corno)='$corno' and trim(cusno) ='$shpno' and namefile='$filename'";
		//echo $query;
		mysqli_query($msqlcon,$query);
?>