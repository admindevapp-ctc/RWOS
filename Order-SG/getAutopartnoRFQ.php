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
	$param = str_replace('-','',$q);
	
 $qrycusmas="select cusno from cusmas where cust3= '$cusno' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas[cusno];
	  if($flag==''){
	  	$comp=$comp .$cros;
		$flag='1';
	  }else{
		  	$comp=$comp .','.$cros;
	  }
	}
	$comp=$comp .')';
	
	/* protect against sql injection */
	//mysql_real_escape_string($param, $server);
	/* query the database */
	//$param='JK';
	
		$query="select Itnbr from sellpriceaws where replace(Itnbr, '-','') like '__$param%' or replace(Itnbr, '-','') like '$param%' and  cusno in  $comp "; 
		$query=$query . " union "; 
		$query=$query . "select itnbr from sellprice ";
		$query=$query . "where replace(Itnbr, '-','') like '__$param%' or replace(Itnbr, '-','') like '$param%' and  cusno in  $comp order by Itnbr";
		// where trim(Itnbr) like '$param%' and  cusno=". $shpno;
		$query=$query . " LIMIT 0, 5";
	//echo $query;
	$x=0;
	$sql=mysqli_query($msqlcon,$query);	
	//$count = mysqli_num_rows($sql);
	$return_arr =array();
	while($hasil = mysqli_fetch_array ($sql)){
		/**if($x==0){ 
		 	$hsl="[";
		 	
		}
		$x++;
		$partno =$hasil['Itnbr'];
		
		$hsl=$hsl.'{"id":"'.$partno.'", "value":"'.$partno.'"}';
		if($x<$count){
			$hsl=$hsl.",";
		}**/
		$row_array['value'] = $hasil['Itnbr'];
    	array_push($return_arr,$row_array);
		
	}

//$hsl=$hsl."]";
//echo $hsl;
echo json_encode($return_arr);
?>
