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
$xcusno=trim($_GET['cusno']);
$xshipto=trim($_GET['shipto']);
//echo ">>".$xid . "action >>".$xaction;
  
$vsupno=trim($_POST['ddlsupno']);
$vcusno=trim($_POST['ddlcusno']);
$vshpno=trim($_POST['ddlshpto']);
$vaction=trim($_POST['vaction']);
require('../db/conn.inc');



if($vaction=='' && $xid!=''){
	$vaction=$xaction;	
}
$gflag='';
$errorx=='';
//echo $vaction;
if($vaction=='add'){
	//echo "Add";
	if($errorx==''){	
		//check CUST3 and Alias
		
		$query="Select * from  supref  where  supno='$vsupno' and Owner_Comp='$comp' and Cusno = '$vcusno'";
	 	//echo $query;
		$sql=mysqli_query($msqlcon,$query);
		if($hsl = mysqli_fetch_array ($sql)){
			$errorx="Shipto already created";
			$gflag='1';
		}else{
			
			$query="insert into supref (Owner_comp, supno, Cusno, shipto) values('$comp', '$vsupno', '$vcusno', '$vshpno')";
	 		//echo $query;
			mysqli_query($msqlcon,$query);
		}
	 }
}else{
	if($vaction=='edit'){
			if($errorx==''){	
	 			$query="update supref set shipto='$vshpno' where trim(supno) = '$vsupno' and Owner_Comp='$comp' and Cusno = '$vcusno'";
				//echo $query;
	  			mysqli_query($msqlcon,$query);
			}
	}else{
		if($vaction=='delete'){
			$query="delete from supref where trim(supno) = '$xid'and Owner_comp='$comp' and Cusno='$xcusno'";
			//echo $query;
			mysqli_query($msqlcon,$query);
			$errorx='';
		}

	}
		
		
}
//echo $query;

if($errorx!=''){	
	/*
	if($gflag=='1'){
			echo $errorx ;
	}else{
			echo $errorx . ' should be filled';
	}*/
	echo "<script> document.location.href='sup_refmas.php?action=".$vaction."&result=error&msg=".$errorx."'; </script>";
}else{
	//echo 'ok';
	echo "<script> document.location.href='sup_mainref.php'; </script>";
}

?>
