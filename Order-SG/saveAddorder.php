<? session_start() ;
?>
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
include('chkloginajax.php');
$action=trim($_GET['action']);

if($action!='close'){
	if (trim($_GET['orderno']) == '') {
		$error = 'Error : Order No  should be filled';
	}
}

if ($error) {
	echo $error;
} else {
	
	//$periode=trim($_GET['periode']);
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	$shpno=trim($_GET['shpno']);
	$periode="";
	//echo $corno;
	require('db/conn.inc');
	
	// get dealer
	$query="select * from Cusmas  where trim(cusno)='".$shpno."'"; 
	$sql=mysqli_query($msqlcon,$query);
	if($tmphsl=mysqli_fetch_array($sql)){
		$dealer=$tmphsl['xDealer'];
	}
	
	
	if($action=='edit'){
		// select * from order detail inner join qty
		$query8="select orderdtl.*, freeinventory.prtno  from orderdtl inner join freeinventory on orderdtl.partno=freeinventory.prtno where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."'"; 
		$sql8=mysqli_query($msqlcon,$query8);
		while($tmphsl8=mysqli_fetch_array($sql8)){
			$subqty=$tmphsl8['qty'];
			$part=$tmphsl8['partno'];
			$query9="update freeinventory set qty=qty +$subqty where prtno='$part'"; 
			mysqli_query($msqlcon,$query9);
			//echo $query9;
		}
		
		$query5="delete from orderhdr where trim(orderno)='$orderno' and trim(cusno)='$shpno' and  trim(corno)='$corno'"; 
		mysqli_query($msqlcon,$query5);
		$query5="delete from orderdtl where trim(orderno)='$orderno' and trim(cusno)='$shpno' and  trim(corno)='$corno'";
		mysqli_query($msqlcon,$query5);
	}else{
		//update Order Number if not close
		if($action!='close'){
			$type=substr($orderno,-1);
			if($type=='R'){
				$newordno=substr(substr($orderno,-8),0,7);
				$query10="update tc000pr set ROrdno='".$newordno. "' where trim(cusno)='".$cusno."'";
			}else{
				$newordno=substr(substr($orderno,-8),0,7);
				$query10="update tc000pr set Ordno='".$newordno. "' where trim(cusno)='".$cusno."'";
			}
		//echo $newordno;
			mysqli_query($msqlcon,$query10);	
		}
	}
	
	if($action!='close'){
		$query="select * from ".$table. " where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."'"; 
		$x="1";
		$sql=mysqli_query($msqlcon,$query);
		while($tmphsl=mysqli_fetch_array($sql)){
			$partno=$tmphsl['partno'];	
			$partdes=$tmphsl['partdes'];	
			$qty=$tmphsl['qty'];
			$orderdate=$tmphsl['orderdate'];
			$orderstatus=$tmphsl['ordstatus'];
			$bprice=$tmphsl['bprice'];
			$SGPrice=$tmphsl['SGPrice'];
			$curcd=$tmphsl['CurCD'];
			$disc=$tmphsl['disc'];
			$dlrdisc=$tmphsl['dlrdisc'];
			$slsprice=$tmphsl['slsprice'];
			$duedate=$tmphsl['DueDate'];
			$dlvby=$tmphsl['DlvBy'];
			$dlrcurcd=$tmphsl['DlrCurCD'];
			$dlrprice=$tmphsl['DlrPrice'];
			$oecus=$tmphsl['OECus'];
			$shipment=$tmphsl['shipment'];
			if($x=='1'){
				// add order header 
				if($dealer==$shpno){
					$ordflg="1";
				}else{
					$ordflg="";
				};
				
				$query2="insert into orderhdr (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno, Dlvby, ordflg, Dealer, OECus, Shipment) values('$cusno', '$orderno','$shpno', '$orderstatus','$orderdate','$periode','$corno','$dlvby','$ordflg','$dealer', '$oecus','$shipment')";
				mysqli_query($msqlcon,$query2);
				$x='2';
			}
			
				$query3="insert into orderdtl (CUST3, orderno, cusno, partno,itdsc, orderdate,qty, CurCD, bprice, SGCurCD,SGPrice, disc, dlrdisc,slsprice,Corno,DueDate, ordflg, DlrCurCD, DlrPrice) values('$cusno','$orderno','$shpno', '$partno','$partdes','$orderdate', $qty, '$curcd', $bprice,'SD', $SGPrice, $disc, $dlrdisc, $slsprice, '$corno','$duedate','$ordflg', '$dlrcurcd', $dlrprice)";
				mysqli_query($msqlcon,$query3);
				
				$query9="update freeinventory set qty=qty-$qty where prtno='$partno'"; 
				mysqli_query($msqlcon,$query9);
				$query4=$query4.$query3."<br>";
		}
		
	}	
		
		// delete attachment if close
		if($action=='close'){
		$full  = "server/php/files/";
		$thumbs = "server/php/files/thumbnail/";
		$qryhdr="select * from  orderhdr where trim(orderno)='$orderno' and trim(cusno)='$shpno'"; 		
		$sqlhdr=mysqli_query($msqlcon,$qryhdr);
		if($tmphslhdr=mysqli_fetch_array($sqlhdr)){
		}else{
			$qryattach="select * from attachment where trim(corno)='$corno' and trim(cusno) ='$shpno'";
			//echo $qryattach . "<br>";

			$jml=0;
			$sqlattach=mysqli_query($msqlcon,$qryattach);
			while($tmpattach=mysqli_fetch_array($sqlattach)){
				$jml=$jml+1;
				$filename=$tmpattach['namefile'];
				$filefull=$full.$filename;
				$filetum=$thumbs.$filename;
			    if(file_exists($filefull)){
					unlink($filefull);
					if(file_exists($filetum)){
						unlink($filetum);
					}//filexist
				}  //if file exits
				
			}// while 
			if($jml>0){
					$qrydel="delete from attachment where trim(corno)='$corno' and trim(cusno) ='$shpno'";
				    mysqli_query($msqlcon,$qrydel);
					
			}  //jml
		   } //qry hdr
		}
		if($x==2 || $action=='close' || ($action=='edit' && $x!=2)){	
				$query4="delete from ".$table. " where trim(orderno)='".$orderno."' and 		trim(cusno)='".$shpno."'";  
		//echo $query;
			mysqli_query($msqlcon,$query4);
		//echo $query."<br>".$query2."<br>".$query4;
	echo "<script> document.location.href='mainadd.php'; </script>";
		}
	
}
	
?>
