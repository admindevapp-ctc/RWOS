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
header("Location:../../login.php");
}



	
	/* Database connection information */
	require('../db/conn.inc');
	//$periode=trim($_GET['periode']);
	$shpno=trim($_GET['shpno']);
	$itnbr=trim($_GET['itnbr']);
	$curcd=trim($_GET['curcd']);
	$price=trim($_GET['price']);
	$dcurcd=trim($_GET['dcurcd']);
	$dprice=trim($_GET['dprice']);
	$dlrcd=trim($_GET['dlrcd']);
	$action=trim($_GET['action']);
	
	// check from customer master database
	
	$sql = "SELECT * from cusmas where cusno='$shpno'" ;
	$result = mysqli_query($msqlcon,$sql);
	if (! $aRow = mysqli_fetch_array( $result ) ){
			echo "Error -customer  not found";
	}else{
		$cust2=$aRow['CUST2'];
		 $cust3=$aRow['CUST3'];
		$sql = "SELECT * from bm008pr where itnbr='$itnbr'" ;
		$result = mysqli_query($msqlcon,$sql);
		if (! $aRow = mysqli_fetch_array( $result ) ){
			echo "Error -Item Number  not found";
		}else{
			$sql = "SELECT * from sellpriceaws where itnbr='$itnbr'and cusno='$shpno'"  ;
			$result = mysqli_query($msqlcon,$sql);
			if (! $aRow = mysqli_fetch_array( $result ) ){
				if($action=='add'){
				   $sql = "insert into sellpriceaws values('$shpno', '$itnbr', $price, '$curcd', $dprice, '$dcurcd', '$dlrcd')" ;
				 
				   $result = mysqli_query($msqlcon,$sql);	
				}else{
				  echo "Error - sellprice  not found";
				}
			}else{
				if($action=='add'){
					  echo "Error - sellprice found";
				}else{
					if($action=='edit'){
						$sql = "update sellpriceaws set PriceAWS= $price,  CurCDAWS='$curcd', price=$dprice, CurCD='$dcurcd', DlrCD='$dlrcd'  where cusno='$shpno'  and Itnbr='$itnbr'" ;
						echo $sql;
						$result = mysqli_query($msqlcon,$sql);
					}else{
						if($action=='delete'){
							$sql="delete from  sellpriceaws  where cusno='$shpno'  and Itnbr='$itnbr'" ;
							$result = mysqli_query($msqlcon,$sql);
							echo "<script> document.location.href='mainSlsAwsAdm.php'; </script>";
						}
					}  //edit
				}  //if action==add
		
			}  //sellprice
		}
	}
	
	
	
?>
