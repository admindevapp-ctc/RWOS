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
header("Location: ../login.php");
}

if (trim($_GET['orddate']) == '') {
	$error[] = '* order date  should be filled';
}

if ($_GET['ordtype'] == '') {
	$error[] = '* OrderType!';
}

if ($error) {
	echo '<b>Error</b>: <br />'.implode('<br />', $error);
} else {
	$orddate=$_GET['orddate'];
	//change format order
	if($orddate!=''){
		$ordyy=substr($orddate,-2);
		$orddd=substr($orddate,0,2);
		$ordmm=substr($orddate,3,2);
	}else{
	$ordyy=date('y');
	$ordmm=date('m');
	$orddd=date('d');
	$yymm=$thn.$bln;	
	}
	/**echo $ordyy."<br>";
	echo $ordmm."<br>";
	echo $orddd."<br>";**/
	$bulan=array("A","B","C","D","E","F","G","H","I","J","K","L");
	$idx=(int)$ordmm-1;
	$idxmm=$bulan[$idx];
	
	//$ordyymmdd=$ordyy.$ordmm.$orddd;
	$ordyymmdd=$ordyy.$idxmm.$orddd;
	//echo $ordyymmdd."<br>";
	$ordtype=$_GET['ordtype'];
	require('db/conn.inc');
	
	$query="select * from tc000pr where trim(cusno) = '$cusno'";
	$sql=mysqli_query($msqlcon,$query);		
	$hasil = mysqli_fetch_array ($sql);
	$order=$hasil['Ordno'];
	/**echo "cusno=".$cusno."<br>";
	echo "order no=".$order."<br>";**/
	if(strlen(trim($order))!=7){
		$vordno=$ordyymmdd."01";
	}else{
		$ordprd=substr($order,0,5);
		if($ordprd!=$ordyymmdd){
			$vordno=$ordyymmdd."01";
		//	echo "order periode=".$ordprd;
		}else{
			$ordval=(int)substr($order,-2);
			//echo "order value=".$ordval;
			$ordval1=$ordval+1;
			$strordval=str_pad((int) $ordval1,2,"0",STR_PAD_LEFT);
			$vordno=$ordyymmdd.$strordval;
		}
	}
	//echo 
	
	$xordno=$alias.$vordno.$ordtype;
	
	$query="select * from orderhdr where orderno='".$xordno."'";
	//echo $query;
    $sql=mysqli_query($msqlcon,$query);
   	$hasil = mysqli_fetch_array ($sql);
    if(!$hasil){
		//echo "ora ketemu";
		$xordno=$alias.$vordno.$ordtype;
		echo $xordno;
	}else{
		//echo "ketemu";
		$query="select orderno from orderhdr where CUST3='".$cusno."' and ordtype != 'R' order by orderno desc limit 1";
    	$sql=mysqli_query($msqlcon,$query);
   		$hasil = mysqli_fetch_array ($sql);
		if($hasil){
			$orderno=$hasil['orderno'];
			//echo $orderno;
			$sqx=substr(substr($orderno,0,9),-2);
			$prdx=substr(substr($orderno,0,7),-5);
			//echo "sequence=".$sqx . "<br>";
			//echo $prdx.$ordyymmdd;
			if($prdx!=$ordyymmdd){
				$vordno=$ordyymmdd."01";
				//echo "periode=".$prdx . "<br>";
				//echo "System=".$xperiode . "<br>";
				//echo "sequence=".$sqx . "<br>";
			}else{
				$ordval=(int)$sqx;
				$ordvall=$ordval+1;
				//echo "int=".$ordvall. "<br>";
				$strordval=str_pad($ordvall,2,"0",STR_PAD_LEFT);
				$vordno=$ordyymmdd.$strordval;
		
			}
				$xordno=$alias.$vordno.$ordtype;
				//echo $cyear."||".$xmonth."||".$xordno;
				echo $xordno;
		}
	}
	
	
	
	
	
	
}
?>
