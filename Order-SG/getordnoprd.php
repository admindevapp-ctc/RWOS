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


	$cyear=date('Y');
	$cmonth=date('m');
	$cdate=date('d');
	$cymd=date('Ymd');

	$xmonth=str_pad((int) $cmonth,2,"0",STR_PAD_LEFT);
	$experiode=$cyear.$xmonth;
	$xperiode=substr($experiode,-4);	
	
	require('db/conn.inc');
	//$cusno='SDML';
	$query="select * from tc000pr where trim(cusno) = '$cusno'";
	$sql=mysqli_query($msqlcon,$query);		
	$hasil = mysqli_fetch_array ($sql);
	$order=$hasil['ROrdno'];
	if(strlen(trim($order))!=7){
		$vordno=$xperiode."001";
	}else{
		$ordprd=substr($order,0,4);
		if($ordprd!=$xperiode){
				$vordno=$xperiode."001";
		}else{
			$ordval=(int)substr($order,-3);
			$ordval1=$ordval+1;
			$strordval=str_pad((int) $ordval1,3,"0",STR_PAD_LEFT);
			$vordno=$xperiode.$strordval;
		}
	}
		
		
	$xordno=$alias.$vordno."R";
	
	$query="select * from orderhdr where orderno='".$xordno."'";
	//echo $query;
    $sql=mysqli_query($msqlcon,$query);
   	$hasil = mysqli_fetch_array ($sql);
    if(!$hasil){
		//echo "ora ketemu";
		$xordno=$alias.$vordno."R";
		echo $cyear."||".$xmonth."||".$xordno;
	}else{
		//echo "ketemu";
		$query="select orderno from orderhdr where CUST3='".$cusno."' and ordtype = 'R' order by orderno desc limit 1";
    	$sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
		if($hasil){
			$orderno=$hasil['orderno'];
			$sqx=substr(substr($orderno,0,9),-3);
			$prdx=substr(substr($orderno,0,6),-4);
			//echo "sequence=".$sqx . "<br>";
			if($prdx!=$xperiode){
				$vordno=$xperiode."001";
				//echo "periode=".$prdx . "<br>";
				//echo "System=".$xperiode . "<br>";
				//echo "sequence=".$sqx . "<br>";
			}else{
				$ordval=(int)$sqx;
				$ordvall=$ordval+1;
				//echo "int=".$ordvall. "<br>";
				$strordval=str_pad($ordvall,3,"0",STR_PAD_LEFT);
				$vordno=$xperiode.$strordval;
		
			}
				$xordno=$alias.$vordno."R";
				echo $cyear."||".$xmonth."||".$xordno;
		}
	}

?>
