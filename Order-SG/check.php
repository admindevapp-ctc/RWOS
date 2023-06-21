<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<html>
<body>

<?php
require('db/conn.inc');

$query="SELECT * FROM orderhdr where trflg =' ' GROUP BY Corno, cusno, orderdate, orderno ORDER BY Corno, cusno, orderdate, orderno";
$result=mysqli_query($msqlcon,$query);
$num=mysql_numrows($result);mysql_close();?>
<table border="1" cellspacing="0" cellpadding="0">
<tr>
<td>
<font face="Arial, Helvetica, sans-serif">Customer </font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Order No</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Part No</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Qty</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Price</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">ordflg</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">orderdate</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Corno</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Trnflg</font>
</td>
</tr>
<?php $i=0;while ($i < $num) 
{$f1=mysql_result($result,$i,"CUST3");
$f2=mysql_result($result,$i,"orderno");
$f3=mysql_result($result,$i,"partno");
$f4=mysql_result($result,$i,"qty");
$f5=mysql_result($result,$i,"bprice");
$f6=mysql_result($result,$i,"ordflg");
$f7=mysql_result($result,$i,"orderdate");
$f8=mysql_result($result,$i,"Corno");
$f9=mysql_result($result,$i,"tranflg");
?>
<tr>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f1; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f2; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f3; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f4; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f5; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f6; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f7; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f8; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php 
if($f9==''){
echo "<font color='red'>Not Trns</font>";}else{
	echo "Transfered";
}	
 ?></font>
</td>
</tr>
<?php $i++;}
?>

</body>
</html>