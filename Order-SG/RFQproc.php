<? session_start() ?>
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
include "crypt.php";
//echo $action;
$var = decode($_SERVER['REQUEST_URI']);
$xrfqno=trim($var['rfqno']);
$action=trim($var['action']);
$shpno=trim($var['shpno']);
$history=trim($var['history']);
if($history!='Y'){
	$history='N';
}
if($shpno==''){
	$shpno=$cusno;
}

$cyear=date('Y');
$cmonth=date('m');
$ym=$cyear.$cmonth;
$cymd=date('Ymd');
 //get order no 
/**echo 'action adalah :'.$action ;
echo '<br>';
echo 'rfqno :'.$xrfqno ;
**/

if ($action=='') {
	$query="select * from RFQNo where period='".$ym."' ";
//echo $query;
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
		$order =$hasil["RFQNo"]+1;
		$xorder=str_pad((int) $order,3,"0",STR_PAD_LEFT);
		//$query="update RFQNo set RFQNo=". $order  . " where period='".$ym."' ";
	}else{
		$xorder='001';
		//$query="insert into RFQNo(`period`,`RFQNo`) values('$ym',1)";
	
	}
	//echo $query;
	if(isset($_SESSION['sip'])){
			unset($_SESSION['sip']);	
	}
	$noorder='RFQ'.$xorder."/".$cmonth."/".$cyear;
	mysqli_query($msqlcon,$query);
	$action='new';
	$link= "RFQHdr.php?".paramEncrypt("action=$action&rfqno=$noorder&history=$history&shpno=$shpno");
}else{
	if ($action=='edit'){
		$fld=array();
		$result=array();
		$query="select * from RFQdtl where RFQNO='".$xrfqno."' ";
		$sql=mysqli_query($msqlcon,$query);		
		while($hasil = mysqli_fetch_array ($sql)){
			$fld['prtno']=$hasil['PRTNO'];
			$fld['action']=trim($action);
			$fld['desc']=$hasil['ITDSC'];
			$fld['sts']=$hasil['STS'];
			$fld['rpldt']=$hasil['RPLDT'];
			$fld['diasrmk']=$hasil['DIASRMK'];
			if($hasil['DIASANS']=='DISCONTINUED, PLS ORDER SUBTITUTION PN' && $hasil['SUBTITUTE']!=''){
				$fld['diasans']=$hasil['DIASANS'] . " : ". $hasil['SUBTITUTE'];	
			}else{
				$fld['diasans']=$hasil['DIASANS'];
			}
			$result1[]=$fld;
			//$result=array_merge($result, $result1);	
		}
		//print_r($result1);
		if(isset($_SESSION['sip'])){
			unset($_SESSION['sip']);	
		}
		$_SESSION['sip']=$result1;
		$link= "RFQHdr.php?".paramEncrypt("action=$action&rfqno=$xrfqno&history=$history&shpno=$shpno");
	}else{
		if ($action=='delete'){
			$query="delete from RFQdtl where RFQNO='".$xrfqno."' ";
			mysqli_query($msqlcon,$query);
			$query="delete from RFQhdr where RFQNO='".$xrfqno."' ";
			mysqli_query($msqlcon,$query);
			$link= "mainRFQ.php";
		}else{
		if ($action=='move'){
			$query="update RFQhdr set STS='X' where RFQNO='".$xrfqno."' ";
			mysqli_query($msqlcon,$query);
			$link= "mainRFQ.php";
		}
		}	
			
	}
		
}
echo "<script> document.location.href='". $link . "'; </script>";

?>
