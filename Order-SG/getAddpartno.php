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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

if (trim($_GET['partno']) == '') {
	$error = 'Error : Part No  should be filled';
}


if ($error) {
	echo $error;
} else {
	$cdate=date('Ymd');
	$partno=strtoupper(trim($_GET['partno']));
	$periode=trim($_GET['periode']);
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	$action=trim($_GET['action']);
	$qty=$_GET['qty'];
	$ordtype=strtoupper(substr($_GET['ordtype'],0,1));
	$shpno=trim($_GET['shpno']);
	$oecus=trim($_GET['oecus']);
	$shipment=trim($_GET['shipment']);	
	require('db/conn.inc');
	/** check Discount  **/
	
	
	$cdate=date('Ym');
	$tglorder=date('Ymd');
	$partno=strtoupper(trim($_GET['partno']));
	$orderno=trim($_GET['orderno']);
	$corno=trim($_GET['corno']);
	$action=trim($_GET['action']);
	$shpno=trim($_GET['shpno']);
	$qty=$_GET['qty'];
	$ordtype=$_GET['ordtype'];
	
	require('db/conn.inc');
	$YMD=date('Ymd');
	$err='';
	$qrycusmas="select * from cusmas where cusno= '$shpno' ";
//	echo $qrycusmas;
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
	if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
		$cusgr=$hslcusmas['CusGr'];
		$route=$hslcusmas['route'];
		$oecus=$hslcusmas['OECus'];
			}
	$flag='1';
	if(strtoupper($route)=='N'){
		$query="select * from specialpriceaws where trim(Itnbr) = '$partno' and cusno= '$shpno' ";
		$flag='2';
	}else{
		$query="select * from specialprice where trim(Itnbr) = '$partno' and cusno= '$shpno' ";
	}
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
		if($flag=='1'){
			$curcd=$hasil['CurCD'];
			$bprice=$hasil['Price'];
			$curcddlr=$hasil['CurCD'];
			$bpricedlr=$hasil['Price'];
		}else{
			$curcd=$hasil['CurCDAWS'];
			$bprice=$hasil['PriceAWS'];
			$curcddlr=$hasil['CurCD'];
			$bpricedlr=$hasil['Price'];
		}
			$qrycur="select * from excrate  where trim(CurCD) = '$curcd' and EfDateFr<='$YMD' order by EfDateFr desc ";
			$sqlcur=mysqli_query($msqlcon,$qrycur);		
			if($hslcur = mysqli_fetch_array ($sqlcur)){
				$exrate=$hslcur['Rate'];
			}
			
			$slsprice=$bprice;
			
			$qrymaster="select * from BM008pR where trim(ITNBR) ='$partno'" ;
			$sqlmaster=mysqli_query($msqlcon,$qrymaster);
			if($hslmaster = mysqli_fetch_array ($sqlmaster)){
					$partdes=$hslmaster['ITDSC'];
					$lot=$hslmaster['Lotsize'];
					$ittyp=$hslmaster['ITTYP'];
					$itcat=$hslmaster['ITCAT'];
					$sisa=$qty%$lot;
					if($sisa==0){
						if(strtoupper($oecus)!='Y'){
						// Check Cut of Date
							$qrycut="select * from cutofdate where period=".$cdate;
							$sqlcut=mysqli_query($msqlcon,$qrycut);
							if($hasilcut = mysqli_fetch_array ($sqlcut)){
							      $cutdate=$hslcut['CutOfDate'];
								  
								  $qrydue="select * from crduedate where CUSGR='".$cusgr ."' and ITTYP='".$ittyp."' and ITCAT='".$itcat."'" ;
								  //echo $qrydue;
								  $sqldue=mysqli_query($msqlcon,$qrydue);
								  if($hasildue = mysqli_fetch_array ($sqldue)){
								      if($cutdate>=$tglorder){
										  $blndue=$hasildue['RBCUT'];
										  $tgldue=$hasildue['DBCUT'];
										  
									  }else{
										   $blndue=$hasildue['RACUT'];
										   $tgldue=$hasildue['DACUT'];
									  }
										  
										$cyear=date('Y');
										$cmonth=date('m'); 
										$cday=date('d');
									
										$qryinv="select * from freeInventory where trim(prtno) ='$partno'" ;
										$sqlinv=mysqli_query($msqlcon,$qryinv);
										if($hasilinv = mysqli_fetch_array ($sqlinv)){
											$qtyinv=$hasilinv['qty'];
											if($qtyinv>$qty){
												$pbln=(int)$cmonth+1;
												$tgldue=$cday;
											}else{
												$pbln=(int)$cmonth+$blndue;
											}
										}else{
											$pbln=(int)$cmonth+$blndue;
										}
																		
									
										if($pbln>12){
											$pbln=$pbln-12;
											$pbln=str_pad((int) $pbln,2,"0",STR_PAD_LEFT);
											$pyear=(int)$cyear+1;
										}else{
		
											$pbln=str_pad((int) $pbln,2,"0",STR_PAD_LEFT);
											$pyear=$cyear;
										}
										if($tgldue=="-"){
											$tgldue=str_pad((int) $cday,2,"0",STR_PAD_LEFT);
										}
										$cduedt=$pyear.$pbln.$tgldue;
										$tduedt=$tgldue."/".$pbln."/".$pyear;
									/**	$disc=0;
										$dlrdisc=0;
										$vttlprice=number_format($qty*$bprice,2,".",",");
										$vttlex=number_format($bprice*$qty*$exrate,2,".",",");
										$desc=$partdes."||".$curcd."||".$bprice."||".$vttlprice."||".$vttlex."||".$tduedt;		
										// Check in tmp table
										$query2="select * from ".$table. " where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."'";
										//echo $query2;
				$sql2=mysqli_query($msqlcon,$query2);
				if($hasil2 = mysqli_fetch_array ($sql2)){
					if($action=='add'){
						$desc='Error : Order Part No already found' ;
					} else{
						$query4="update ".$table. " set qty=".$qty ." where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."'";
						mysqli_query($msqlcon,$query4);
					}
				}else{
					$query3="insert into ".$table. " (CUST3, orderno,orderdate,cusno,partno,partdes,ordstatus,qty,CurCD, bprice, SGCurCD, SGPrice,disc,dlrdisc,slsprice,Corno,DueDate, DlrCurCD, DlrPrice) values('$cusno','$orderno','$tglorder','$shpno','$partno','$partdes','$ordtype',$qty, '$curcd', $bprice,'SD', $exrate, $disc, $dlrdisc,$slsprice, '$corno', '$cduedt','$curcddlr', $bpricedlr )";
					//echo $query3;
					mysqli_query($msqlcon,$query3);
					
			//echo $query3;	
				}**/
										
								
										
								  }else{
									 $err='1';
									  $desc="Error: No Profile Master!";
								  }
								  
							}else{
								$err='1';
								$desc="Error: No Cut of date";	
							}
					} else {  //oe
							if($shipment=='A'){
							//add 45 days
								$addDays=45;	
							}else{
						   		$addDays=60;	
							}
							$cduedt= date('Ymd', strtotime("+".$addDays." days"));
							$tduedt= date('d/m/Y', strtotime("+".$addDays." days"));	
			
						}
						if($err!='1'){
						$disc=0;
						$dlrdisc=0;
						$vttlprice=number_format($qty*$bprice,2,".",",");
						$vttlex=number_format($bprice*$qty*$exrate,2,".",",");
						$desc=$partdes."||".$curcd."||".$bprice."||".$vttlprice."||".$vttlex."||".$tduedt;		
						// Check in tmp table
						$query2="select * from ".$table. " where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."'";
						//echo $query2;
						$sql2=mysqli_query($msqlcon,$query2);
						if($hasil2 = mysqli_fetch_array ($sql2)){
							if($action=='add'){
								$desc='Error : Order Part No already found' ;
							} else{
								$query4="update ".$table. " set qty=".$qty ." where trim(partno) ='".$partno. "' and trim(orderno)='".$orderno."'";
								mysqli_query($msqlcon,$query4);
							}
						}else{
								$query3="insert into ".$table. " (CUST3, orderno,orderdate,cusno,partno,partdes,ordstatus,qty,CurCD, bprice, SGCurCD, SGPrice,disc,dlrdisc,slsprice,Corno,DueDate, DlrCurCD, DlrPrice, OECus, Shipment) values('$cusno','$orderno','$tglorder','$shpno','$partno','$partdes','$ordtype',$qty, '$curcd', $bprice,'SD', $exrate, $disc, $dlrdisc,$slsprice, '$corno', '$cduedt', '$curcddlr', $bpricedlr, '$oecus', '$shipment')";
								//echo $query3;
								mysqli_query($msqlcon,$query3);
					
			
							}
						}

					
					
					
					}else{
					   $desc="Error: Order Not in Lot Size!, Lot Size=".$lot;	
					}
					
					
					
				}else{
					$desc="Error: Not found in Item Master";
				}
		
						
			
		 } else{
			
			
			require('checkPhaseOut.php');
				$jawab=checkPhaseOut($partno);
				if($jawab[0]=='E'){
					$desc='Error : '. $jawab[1];
				}else{
					$xsub=$jawab[1];
					if(strtoupper($route)=='N'){
						$query="select * from specialpriceaws where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
						$flag='2';
					}else{
						$query="select * from specialprice where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
					}
					$sql=mysqli_query($msqlcon,$query);		
					if($hasil = mysqli_fetch_array ($sql)){
							$desc='Error : for order please use :'.$xsub;
					}else{
							$desc='Error : not Authorized to use this Part No';			
					}
				}
			
			//Check with Phase out number
			/**
			$qryphase="select * from phaseout where trim(ITNBR) ='$partno'" ;
			$sqlphase=mysqli_query($msqlcon,$qryphase);
			if($hslphase = mysqli_fetch_array ($sqlphase)){	
				$xsub=$hslphase['SUBITNBR'];
				$xdesc=$hslphase['ITDSC'];
				if($xsub==""){
					$desc='Error : '. $xdesc; 
				}else{
					$flag='1';
					if(strtoupper($route)=='N'){
						$query="select * from specialpriceaws where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
						$flag='2';
					}else{
						$query="select * from specialprice where trim(Itnbr) = '$xsub' and cusno= '$shpno' ";
					}
					$sql=mysqli_query($msqlcon,$query);		
					if($hasil = mysqli_fetch_array ($sql)){
						$desc='Error : for order please use :'.$xsub;
					}else{
						$desc='Error : not Authorized to use this Part No';			
					}
								
								
				}			
			}else{
   				$desc='Error : not Authorized to use this Part No'; 
	
			}
			**/
// phase out
			
			 
		 }
	
	echo $desc;

	}

?>
