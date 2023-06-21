<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
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
		if($type!='a')header("Location:../main.php");
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
$xid=trim($_GET['id']);
$xaction=trim($_GET['action']);
//echo $xid . $xaction;
$vcusno=trim($_POST['vcusno']);
$vcusnm=trim($_POST['vcusnm']);
$vtype=trim($_POST['vtype']);
$valias=trim($_POST['valias']);
$vgroup=trim($_POST['vgroup']);
$vdealer=trim($_POST['vdealer']);
$vaction=trim($_POST['vaction']);
$vcust2=trim($_POST['vcust2']);
$vcust3=trim($_POST['vcust3']);
$vaddr1=trim($_POST['vaddr1']);
$vaddr2=trim($_POST['vaddr2']);
$vaddr3=trim($_POST['vaddr3']);
$vcoy=trim($_POST['vcoy']);
$vroute=trim($_POST['vroute']);

require('../db/conn.inc');
if($vcusnm==''){
	$errorx='Customer Name';
}
if($vgroup==''){
	if($errorx==""){
		$errorx='group';
	}else{
		$errorx=$errorx.', group ';
	}
}

if($valias==''){
	if($errorx==""){
		$errorx='alias ';
	}else{
		$errorx=$errorx.', alias ';
	}
}

if($vcust3==''){
	if($errorx==""){
		$errorx='cust3 ';
	}else{
		$errorx=$errorx.', cust3 ';
	}
}

if($vdealer==''){
	if($errorx==""){
		$errorx='dealer ';
	}else{
		$errorx=$errorx.', dealer ';
	}
}

if($vaction=='' && $xid!=''){
	$vaction=$xaction;	
}

echo $vaction;
if($vaction=='add'){
	if($errorx==''){	
		$query="insert into cusmas(Cusno,Cusnm,alias, ESCA1, ESCA2, ESCA3, COY, CUST2, CUST3, Custype, xDealer, CusGr, route) values('$vcusno', '$vcusnm', '$valias', '$vaddr1', '$vaddr2',  '$vaddr3', '$vcoy', '$vcust2', '$vcust3','$vtype', '$vdealer', '$vgroup', '$vroute')";
	 	mysqli_query($msqlcon,$query);
		}
}else{
	if($vaction=='edit'){
			if($errorx==''){	
	 			$query="update cusmas set Cusnm='$vcusnm', alias='$valias',ESCA1='$vaddr1', 					ESCA2='$vaddr2', ESCA3='$vaddr3', COY='$vcoy',CUST2= '$vcust2', CUST3='$vcust3', Custype='$vtype', xDealer= '$vdealer', CusGR='$vgroup', route='$vroute' where trim(Cusno) = '$vcusno'";
	  			mysqli_query($msqlcon,$query);
			}
	}else{
		if($vaction=='delete'){
			$query="delete from Cusmas where trim(Cusno) = '$xid'";
	  			mysqli_query($msqlcon,$query);
				$errorx='';
		}

	}
		
		
}
//echo $query;
if($errorx!=''){	
	echo $errorx . ' should be filled';
}else{
	//echo 'ok';
	echo "<script> document.location.href='mainCusAdm.php'; </script>";
}





?>
