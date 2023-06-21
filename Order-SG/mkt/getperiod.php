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
	$periode=trim($_GET['period']);
	$cutdate=trim($_GET['cutdate']);
	$action=trim($_GET['action']);
	
	
	$tgl=substr($cutdate,-4).substr($cutdate,0,2).substr($cutdate,3,2);

	//check di database
	$sql = "SELECT * from CutofDate where Period='$periode'" ;
	$result = mysqli_query($msqlcon,$sql);
	if ( $aRow = mysqli_fetch_array( $result ) ){
		if($action=='add'){
			echo "Error - Order Period found";
		}else{
			if($action=='edit'){
				$sql = "update CutofDate set CutofDate='$tgl' where Period='$periode'" ;
				$result = mysqli_query($msqlcon,$sql);
			}else{
				if($action=='delete'){
					$sql = "delete from  CutofDate where Period='$periode'" ;
					$result = mysqli_query($msqlcon,$sql);
					echo "<script> document.location.href='mainCutAdm.php'; </script>";
				}
			}
		}
	}else{
		if($action=='edit' || $action=='delete' ){
			echo "Error - Order Period can't be found";
		}else{
			$sql = "insert into CutofDate values('$periode', '$tgl')" ;
			$result = mysqli_query($msqlcon,$sql);	
		}
		
	}
	
?>
