<? session_start() ;
?>

<?
	
if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusno'];
	$_SESSION['cusnm'];
	$_SESSION['cusad1'];
	$_SESSION['cusad2'];
	$_SESSION['cusad3'];
	$_SESSION['type'];
	$_SESSION['zip'];
	$_SESSION['state'];
	$_SESSION['phone'];
	$_SESSION['password'];
	$_SESSION['alias'];


	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$cusad1=$_SESSION['cusad1'];
	$cusad2=$_SESSION['cusad2'];
	$cusad3=$_SESSION['cusad3'];
	$type=$_SESSION['type'];
	$zip=$_SESSION['zip'];
	$state=$_SESSION['state'];
	$phone=$_SESSION['phone'];
	$password=$_SESSION['password'];
	$alias=$_SESSION['alias'];
  
}else{	
header("Location: login.php");
}
	require('db/conn.inc');
	$conn = new PDO('odbc:djoblib', 'edp12', 'nuel2811');
	if(!$conn) echo "odbc problem";
	$sql="Select CUSNO, SHPNO, CORNO, SORNO, PRDYM, ORDDT,DLVDT,PRTNO, FBQTY from T12401R";
	$sql_result=$conn->prepare($sql);
	$sql_result->execute();
	$i="0";
	while($rec=$sql_result->fetch(PDO::FETCH_ASSOC))
	 {
		
		$icusno=$rec['CUSNO'];
		$ishpno= $rec['SHPNO'];
		$isorno= $rec['SORNO'];
		$icorno=$rec['CORNO'];
		$iperiode=$rec['PRDYM'];
		$iorddt=$rec['ORDDT'];
		$idlvdt=$rec['DLVDT'];
		$iprtno= $rec['PRTNO'];
		$iqty= $rec['FBQTY'];
		
		$query = "INSERT INTO feedback(orderno, cusno, corno, partno, periode, orderdate,dlvQty, dlvdate) VALUES('$isorno','$icusno','$icorno','$iprtno', '$iperiode','$iorddt',$iqty,$idlvdt )";
		$sqlq = mysql_query ($query) ;
	
	 }
	
		
	
	
		





?>