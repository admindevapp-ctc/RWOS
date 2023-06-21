<? session_start() ;
?>
<?
if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
		$_SESSION['user'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$_SESSION['type'];
		$_SESSION['custype'];
		

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
header("Location: login.php");
}

if (trim($_GET['tahun']) == '') {
	$error[] = '* Year  should be filled';
}

if ($_GET['bulan'] == '') {
	$error[] = '* Please fill the month field!';
}

if ($error) {
	echo '<b>Error</b>: <br />'.implode('<br />', $error);
} else {
	$thn=$_GET['tahun'];
	$bln=$_GET['bulan'];
	$cyear=date('Y');
	$cmonth=date('m');
	$cymd=date('Ymd');
	$yymm=$thn.$bln;
	
	$pbln=(int)$bln-1;
	if($pbln==0){
		$pbln=12;
		$pyear=(int)$thn-1;
	}else{
		
		$pbln=str_pad((int) $pbln,2,"0",STR_PAD_LEFT);
		$pyear=$thn;
	}
	$pym=$pyear.$pbln;
	
	$thnbln=$cyear.$cmonth;
	if($thnbln>$yymm){
		echo "Error - Can't create previous month Order";
	} else {
		require('db/conn.inc');
		$query="select * from userid where trim(cusno) = '$cusno'";
		$sql=mysqli_query($msqlcon,$query);		
		$hasil = mysqli_fetch_array ($sql);
		$order=$hasil['ordno'];
		if($order==''){
			$order='1';
		}else{
			$order=int($order)+1;
		}
		$xorder=str_pad((int) $order,5,"0",STR_PAD_LEFT);
	
		$ordno=$cusno.$thn.$bln.$xorder;
	    $hsl[]=$ordno;
	
		$query="select * from sc003pr where trim(YRMON) = '$pym'";
		$sql=mysqli_query($msqlcon,$query);		
		$hasil = mysqli_fetch_array ($sql);
		$calcd=$hasil['CALCD'];
		$arr1=str_split($calcd);
		$y=0;
		$r=0;
		for($i=0;$i<count($arr1);$i++){
			if($y==10){
				break; 
			}
		
			if($arr1[$i]=='1'){
				$y=$y+1;
			}
			$r=$r+1;
		}
		$oYMD=$pym.$r;
		if($cymd>$oYMD){
			echo "Error - Order Date should be not more than ". $r."/".$pbln."/".$pyear;
		}else{
			$hsl[]= "Firm Order";
		
		
		$hsl[]=$r;
		$hsl[]=$ymd;
		$json .= implode(',', $hsl);
		//echo $json;
		echo $ordno;
		}
		//echo $pym;
	}
}
?>
