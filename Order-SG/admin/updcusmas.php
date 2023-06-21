<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
		$comp = ctc_get_session_comp(); // add by CTC
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
$voecus=trim($_POST['voecus']);

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
}else{
	// Check Dealer is exist
	if($vcusno!=$vdealer){
		$dealer = ctc_get_customer($vdealer);
		if(!$dealer){
			if($errorx==""){
				$errorx='Dealer Code not in country. Please check again! ';
			}else{
				$errorx=$errorx.', Dealer Code not in country. Please check again! ';
			}
		}
	}
}

if($vaction=='' && $xid!=''){
	$vaction=$xaction;	
}
$gflag='';
//echo $vaction;
if($vaction=='add'){
	if($errorx==''){	
		//check CUST3 and Alias
		
		$query="Select * from  cusmas  where  Alias='$valias' and  CUST3<>'$vcust3' and Owner_Comp='$comp'";
	 	//echo $query;
		$sql=mysqli_query($msqlcon,$query);
		if($hsl = mysqli_fetch_array ($sql)){
			$errorx="Alias already used by another CUST3=". $hsl['CUST3'];
			$gflag='1';
		}else{
			$qrycus="Select * from  cusmas  where  Cusno='$vcusno' and  CUST3='$vcust3' and Owner_Comp='$comp'";
	 	//echo $query;
		$sqlcus=mysqli_query($msqlcon,$qrycus);
		if($hslcus = mysqli_fetch_array ($sqlcus)){
			$errorx="Customer Number already exist. Please check again!";
			$gflag='1';
		}else{
			
			
			$query="insert into cusmas(Cusno,Cusnm,alias, ESCA1, ESCA2, ESCA3, COY, CUST2, CUST3, Custype, xDealer, CusGr, route, OECus,Owner_Comp) values('$vcusno', '$vcusnm', '$valias', '$vaddr1', '$vaddr2',  '$vaddr3', '$vcoy', '$vcust2', '$vcust3','$vtype', '$vdealer', '$vgroup', '$vroute', '$voecus','$comp')";
	 		mysqli_query($msqlcon,$query);
			
		$qry1="Select * from  TC000PR  where  cusno='$vcust3' and Owner_Comp='$comp'";
	 	$sql1=mysqli_query($msqlcon,$qry1);
		if($hsl = mysqli_fetch_array ($sql1)){
		}else{
		
		$query="insert into tc000pr(cusno,Owner_Comp) values('$vcust3','$comp')";
	 	mysqli_query($msqlcon,$query);
		}
			
		}
		}
	 }
}else{
	if($vaction=='edit'){
			if($errorx==''){	
	 			$query="update cusmas set Cusnm='$vcusnm', alias='$valias',ESCA1='$vaddr1', ESCA2='$vaddr2', ESCA3='$vaddr3', COY='$vcoy',CUST2= '$vcust2', CUST3='$vcust3', Custype='$vtype', xDealer= '$vdealer', CusGR='$vgroup', route='$vroute', OECus='$voecus' where trim(Cusno) = '$vcusno' and Owner_Comp='$comp' ";
	  			mysqli_query($msqlcon,$query);
				//echo $query;
			}
	}else{
		if($vaction=='delete'){
			$query="delete from Cusmas where trim(Cusno) = '$xid' and Owner_Comp='$comp'";
	  			mysqli_query($msqlcon,$query);
				$errorx='';
		}

	}
		
		
}
//echo $query;
if($errorx!=''){	
	if($gflag=='1'){
			echo $errorx ;
	}else{
			echo $errorx . ' should be filled';
	}
}else{
	//echo 'ok';
	echo "<script> document.location.href='maincusadm.php'; </script>";
	}





?>
