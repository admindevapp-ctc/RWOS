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

if (trim($_GET['prtno']) == '') {
	$error = 'Error : Part No  should be filled';
}


if ($error) {
	echo $error;
} else {
	$cdate=date('Ym');
	$tglorder=date('Ymd');
	$prtno=strtoupper(trim($_GET['prtno']));
	$action=trim($_GET['action']);
	$desc=$_GET['desc'];
	//echo ' action adalah : ' .$action;
	
	$fld=array();
	$fldtmp=array();
	$chk=chkpartno($prtno, $cusno);
	if($chk!=''){
		
		$error =  $chk;
		echo $error;
		exit;
	}
		
	
	if($action=='add'){
		if(isset($_SESSION['sip'])){
			$idxprt=getkey($_SESSION['sip'], $prtno);
			//echo 'hasil='.$idxprt;
			if($idxprt==-1){
				$fldtmp=($_SESSION['sip']);
				$fld['prtno']=$prtno;
				$fld['action']=trim($_GET['action']);
				$fld['desc']=trim($_GET['desc']);
				$fld['sts']='P';
				$result1[]=$fld;
				$result=array_merge($fldtmp, $result1);	
				$_SESSION['sip']=$result;
			}else{
				$error = 'Error : Part No  Already defined! ';
				echo $error;
			}
	 }else{
		$fld['prtno']=$prtno;
		$fld['action']=trim($_GET['action']);
		$fld['desc']=trim($_GET['desc']);
		$fld['sts']='P';
		$result[]=$fld;
		$_SESSION['sip']=$result;
	}
}else{
		$mpartno = explode(',',$_GET['prtno']);
		echo ' check jumlah action : ' .$action;
		if($action=='delete'){
			$fldtmp=($_SESSION['sip']);
			print_r($fldtmp);
			for($i=0;$i<count($mpartno);$i++){
					$partno=$mpartno[$i];
					$idxprt=getkey($fldtmp, $partno);
					//echo 'diketemukan : '.$partno .array_search($partno,$fldtmp);
					//echo "index ke :".$idxprt;
				if($idxprt!=-1)	unset($fldtmp[$idxprt]);
			}
			if(empty($fldtmp)){
				unset($_SESSION['sip']);
			}else{
				$_SESSION['sip']=array_values($fldtmp);
			}
		}else{
			if($action=='edit'){
			$fldtmp=($_SESSION['sip']);
			$idxprt=getkey($fldtmp, $prtno);
			echo 'update file ke ='.$idxprt;
			//'unset($fldtmp[$idxprt]);
			//$fldtmp[$idxprt]['prtno']=$prtno;
			//$fldtmp[$idxprt]['action']=trim($_GET['action']);
			$fldtmp[$idxprt]['desc']=trim($_GET['desc']);
			$_SESSION['sip']=array_values($fldtmp);
			}	
		}
	
	}
	
	require('db/conn.inc');
	$YMD=date('Ymd');
	
	// check order
	//$arrlength=count($_SESSION['sip']);
	//print_r($_SESSION['sip']);
	//echo "panjang array " . $arrlength;
	
}

function getkey($products, $needle)
{
   foreach($products as $key => $product)
   {
	  //echo 'partno='. $product['prtno'];
      if( $product['prtno'] == $needle ){
         return $key;
		 break;
  	  }
   }
	return -1;
}
function chkpartno($prtno, $cusno){
  $pjg=strlen($prtno);
  if(substr($prtno,-1)=='0'){
	$prtno=substr($prtno, 0, $pjg-1);
  }
	require('db/conn.inc');
		$param = str_replace('-','',$prtno);
	$qrycusmas="select cusno from cusmas where cust3= '$cusno' ";
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	$comp='(';
	$flag='';		
	while($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
	  $cros=$hslcusmas['cusno'];
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
	
		$query=$query . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
		$query=$query . "where replace(sellprice.Itnbr, '-','') like '%$param%' and  cusno in  $comp";
		$query=$query ." union ";
		$query=$query . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
		$query=$query . "where replace(sellprice.Itnbr, '-','') like '%$param%' and  cusno in  $comp";

//	echo $query;
	$x=0;
	$sql=mysqli_query($msqlcon,$query);	
	$count = mysqli_num_rows($sql);
	if($count!=1){
		if($count>1){
			return false;
		}else{
			$qry="select * from phaseout where replace(Itnbr, '-','') like '%$param%' ";		
			//echo $qry;
			$sqlx=mysqli_query($msqlcon,$qry);
			if($hsl = mysqli_fetch_array ($sqlx)){
				if(trim($hsl['SUBITNBR'] )==''){
					//return "Error :". $hsl['ITDSC'];
					return "Error - Phase Out :". $hsl['ITNBR']. "-". $hsl['ITDSC'];
				}else{
					$ganti=$hsl['SUBITNBR'];
					
					$qry1=$qry1 . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
					$qry1=$qry1 . "where sellprice.Itnbr = '%$ganti%' and  cusno in  $comp";
					$qry1=$qry1 ." union ";
					$qry1=$qry1 . "select sellprice.Itnbr, ITDSC, Price, CurCD from sellprice inner join bm008pr on sellprice.itnbr=bm008pr.ITNBR ";
					$qry1=$qry1 . "where sellprice.Itnbr= '%ganti%' and  cusno in  $comp";
					$sql1=mysqli_query($msqlcon,$qry1);	
					if($hsl1 = mysqli_fetch_array ($sql1)){
							$semua="Error :  Phase Out Subtitution Part No =". $ganti ; 
					}
					
					
					
					
					
					
					
				}
			} 
			
		}
	}else{
		if($hasil = mysqli_fetch_array ($sql)){
			$itnbr=$hasil['Itnbr'];
			$desc=$hasil['ITDSC'];
			//$harga=$hasil['Price'];
			//$curcd=$hasil['CurCD'];
			$semua="Error :  Part No :" . $itnbr. "(". $desc . ") can be found in DB!" ; 
		}
		return $semua;
   	
	}


	



	
	
}
?>
