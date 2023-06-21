<? session_start() ;
?>
<?
if(isset($_SESSION['cusno']))
{       
	 if($_SESSION['redir']!='denso-sg'){
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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}



require('db/conn.inc');
	$q = strtoupper($_GET["term"]);
	$shpno=$_GET["shpno"];
	$param = $q;
	/* protect against sql injection */
	//mysql_real_escape_string($param, $server);
	/* query the database */
	//$param='JK';
	$qrycusmas="select * from cusmas where cusno= '$shpno' ";
	//echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
		$route=$hslcusmas['route'];
	}
	if(strtoupper($route)=='N'){
		$query="select * from specialpriceaws where trim(Itnbr) like '$param%' and  cusno=". $shpno;
	
	}else{
		$query="select * from specialprice where trim(Itnbr) like '$param%' and  cusno=". $shpno;
	}
	$query=$query . " LIMIT 0, 10";
	//echo $query;
	$x=0;
	$sql=mysqli_query($msqlcon,$query);	
	$count = mysqli_num_rows($sql);
	while($hasil = mysqli_fetch_array ($sql)){
		if($x==0){ 
		 	$hsl="[";
		 	
		}
		$x++;
		$partno =$hasil['Itnbr'];
		
		$hsl=$hsl.'{"id":"'.$partno.'", "value":"'.$partno.'"}';
		if($x<$count){
			$hsl=$hsl.",";
		}
		
	}

$hsl=$hsl."]";
echo $hsl;
?>
