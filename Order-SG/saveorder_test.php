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
if (trim($_GET['orderno']) == '') {
	$error = 'Error : Order No  should be filled';
}


if ($error) {
	echo $error;
} else {
	$vcusno=trim($_GET['vcusno']);
	$shpno=trim($_GET['shpno']);
	$periode=trim($_GET['periode']);
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	$action=trim($_GET['action']);
	$reason=trim($_GET['reason']);
	$mpartno=trim($_GET['mpartno']);
	require('db/conn.inc');

	// get dealer
	$query="select * from Cusmas  where trim(cusno)='".$shpno."'"; 
	$sql=mysqli_query($msqlcon,$query);
	if($tmphsl=mysqli_fetch_array($sql)){
		$dealer=$tmphsl['xDealer'];
		$route=$tmphsl['route'];
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
		
		// delete header and detail
		$query5="delete from orderhdr where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."'"; 
		mysqli_query($msqlcon,$query5);
		$query5="delete from orderdtl where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."' and corno='".$corno."'"; 
		mysqli_query($msqlcon,$query5);
	}else{
		//update Order Number if not close
		if($action!='close'){
			$type=substr($orderno,-1);
			if($type=='R'){
				$newordno=substr(substr($orderno,-8),0,7);
				$query10="update tc000pr set rordno='".$newordno. "' where trim(cusno)='".$cusno."'"; 			
			}
				
	//echo $query10;
			mysqli_query($msqlcon,$query10);	
		}
		
	}
	if($action!='close'){
		$query="select * from ".$table. " where trim(orderno)='".$orderno."' and trim(cusno)='".$shpno."'"; 
		$x="1";
		$sql=mysqli_query($msqlcon,$query);
		while($tmphsl=mysqli_fetch_array($sql)){
			
			$partno=$tmphsl['partno'];	
			$partdes=addslashes($tmphsl['partdes']);	
			$qty=$tmphsl['qty'];
			$orderdate=$tmphsl['orderdate'];
			$duedate=$tmphsl['DueDate'];
			$orderstatus=$tmphsl['ordstatus'];
			$bprice=$tmphsl['bprice'];
			$SGPrice=$tmphsl['SGPrice'];
			$curcd=$tmphsl['CurCD'];
			$disc=$tmphsl['disc'];
			$dlrdisc=$tmphsl['dlrdisc'];
			$slsprice=$tmphsl['slsprice'];
			$dlrcurcd=$tmphsl['DlrCurCD'];
			$dlrprice=$tmphsl['DlrPrice'];
			$oecus=$tmphsl['OECus'];
			$shipment=$tmphsl['Shipment'];
			if($x=='1'){
					
				// add order header 
				if($dealer==$shpno){
					$ordflg="1";
				}else{
					$ordflg="";
				};
				$query2="insert into orderhdr (CUST3, orderno, cusno, ordtype,orderdate,orderprd, corno, ordflg, Dealer, OECus, Shipment) values('$cusno', '$orderno','$shpno', '$orderstatus','$orderdate','$periode','$corno','$ordflg','$dealer', '$oecus', '$shipment')";
				mysqli_query($msqlcon,$query2);
				//echo $query2."<br>";
				$x='2';
			}			
				$query3="insert into orderdtl (CUST3, orderno, cusno, partno,itdsc, orderdate,qty,CurCD, bprice, SGCurCD,SGPrice, disc, dlrdisc, slsprice,Corno, DueDate, ordflg, DlrCurCD, DlrPrice) values('$cusno','$orderno','$shpno', '$partno','$partdes','$orderdate', $qty,'$curcd',  $bprice,'SD', $SGPrice,  $disc, $dlrdisc, $slsprice, '$corno', '$duedate', '$ordflg','$dlrcurcd', $dlrprice)";
				mysqli_query($msqlcon,$query3);
				//echo $query3;
				
				$query9="update freeinventory set qty=qty-$qty where qty>=$qty and  prtno='$partno'"; 
				mysqli_query($msqlcon,$query9);
				//echo $query9;
		
		}
		
	}
			if($x==2 || $action=='close' || ($action=='edit' && $x!=2)){	
				$query4="delete from ".$table. " where trim(orderno)='".$orderno."' and 		trim(cusno)='".$shpno."'";  
		//echo $query;
			mysqli_query($msqlcon,$query4);
		
		echo "<script> document.location.href='main.php'; </script>"; 
		}
if($action=="Reject"){
	
        
	$spartno = explode(',',$mpartno);
	for($i=0;$i<count($spartno);$i++){
		$vpartno=$spartno[$i];
		$query="update orderdtl set ordflg='R' where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "'";
		mysqli_query($msqlcon,$query);
	
		//========================================================================
		// check message
		//========================================================================
		$query1="select * from rejectorder where trim(orderno)='".$orderno."' and trim(partno) ='".$vpartno. "'"; 
		//echo $query1;
		$sql=mysqli_query($msqlcon,$query1);
			if($tmphsl=mysqli_fetch_array($sql)){
				$query2="update rejectorder set message='". $reason. "' where trim(orderno) ='".$orderno."' and trim(partno) ='".$vpartno. "'";
			}else{
				$query2="insert into rejectorder(orderno, partno, message) values( '$orderno', '$vpartno', '$reason')";
			}
	
	
	mysqli_query($msqlcon,$query2);
	//echo $query2;
	
	
	}
	
	 
}

if($action=="Approve"){
	$spartno = explode(',',$mpartno);
	for($i=0;$i<count($spartno);$i++){
		$vpartno=$spartno[$i];
		$query="update orderdtl set ordflg='1' where trim(orderno)='$orderno' and trim(partno) ='$vpartno'";
		//echo $query;
		mysqli_query($msqlcon,$query) or die(mysqli_error()); ;
	}
	
}

if($action=="Reject" || $action=="Approve"){
	$query2="select * from orderdtl where trim(orderno)='".$orderno."'" ;
	$result2 = mysqli_query($msqlcon,$query2);
	$count2 = mysqli_num_rows($result2);
	
	$query="select * from orderdtl where trim(ordflg)='' and trim(orderno)='".$orderno."'" ;
	$result = mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($result);
	$YMD= date("Ymd");
    $Jam=date("Hi");
	if($count<=0){
	   		$query1="update orderhdr set ordflg='1', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."'" ;
		$result = mysqli_query($msqlcon,$query1);
	}else{
		if($count2>$count){
			$query1="update orderhdr set ordflg='U', ordflagdate='$YMD', ordflagtime='$Jam', ordflaguser='$user' where trim(orderno)='".$orderno."'" ;
		$result = mysqli_query($msqlcon,$query1);
			
		}
	}
}

}	
?>
