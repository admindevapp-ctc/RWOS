
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
$vaction=trim($_GET['action']);
$vcusno1=trim($_POST['vcusno1']);
$xaction=trim($_POST['vaction']);
$vshpto1=trim($_POST['ssl_shpto1']);
$vcusno2=trim($_POST['ssl_cusno2']);
$vshpto2=trim($_POST['vcusshpto2']);
$vamsgroup=trim($_POST['vgroup']);
$vcusnm2=trim($_POST['vcusnm2']);
$vaddr1=trim($_POST['vaddr1']);
$vaddr2=trim($_POST['vaddr2']);
$vaddr3=trim($_POST['vaddr3']);
$vemail1=trim($_POST['vemail1']);
$vemail2=trim($_POST['vemail2']);
$vemail3=trim($_POST['vemail3']);


require('../db/conn.inc');

if($vaction==''){
	$vaction=$xaction;	
}
$gflag='';

if($vaction=='add'){
	if($errorx==''){	
		//check CUST3 and Alias
		//check 1st custcode & 1st shipto & 2nd custcode & 2nd shipto is duplicate show message 'Data duplicate, please check again'
		$query="Select * from  awscusmas  where  cusno1='$vcusno1' and ship_to_cd1='$vshpto1' and cusno2='$vcusno2' and  ship_to_cd2='$vshpto2' and Owner_Comp='$comp'";
	 	//echo $query;
		$sql=mysqli_query($msqlcon,$query);
		if($hsl = mysqli_fetch_array ($sql)){
			$errorx="<span style='color:red'><b>Data duplicate, please check again</b></span>";
			$gflag='1';
		}else{
            $query="insert into awscusmas(Owner_Comp,	cusno1,	ship_to_cd1, cusno2,	ship_to_cd2,
            	cusgrp,	ship_to_adrs1,	ship_to_adrs2,	ship_to_adrs3,	mail_add1,	mail_add2,	mail_add3
            ) 
            values('$comp', '$vcusno1', '$vshpto1', '$vcusno2', '$vshpto2',  
            '$vamsgroup', '$vaddr1', '$vaddr2', '$vaddr3','$vemail1', '$vemail2', '$vemail3')";
            mysqli_query($msqlcon,$query);
           // echo $query;
		}
	 }
}else{
	if($vaction=='edit'){
			if($errorx==''){	
	 			$query="update awscusmas set cusgrp='$vamsgroup', ship_to_adrs1='$vaddr1',ship_to_adrs2='$vaddr2', ship_to_adrs3='$vaddr3'
                , mail_add1='$vemail1', mail_add2='$vemail2',mail_add3= '$vemail3' 
                where cusno1 = '$vcusno1' and ship_to_cd1 = '$vshpto1' and cusno2 = '$vcusno2' and ship_to_cd2 = '$vshpto2' and Owner_Comp='$comp' ";
	  			//echo $query;
				mysqli_query($msqlcon,$query);
			}
	}else{
		if($vaction=='delete'){
            $vcusno1=trim($_GET['cusno1']);
            $vcusno2=trim($_GET['cusno2']);
            $vshpto1=trim($_GET['shpto1']);
            $vshpto2=trim($_GET['shpto2']);
				
			$query_po="SELECT awsorderhdr.orderno FROM awsorderhdr WHERE 1 AND awsorderhdr.ordflg = '' AND Dealer = '$vcusno1' and awsorderhdr.Owner_Comp = '$comp' and awsorderhdr.cusno = '$vcusno2' and awsorderhdr.shipto = '$vshpto2' limit 1";
			
			$query_sup_po = "
				SELECT
					supawsorderhdr.orderno
				FROM
					supawsorderhdr
				WHERE
					1 AND supawsorderhdr.ordflg = '' AND Dealer = '$vcusno1' AND supawsorderhdr.Owner_Comp = '$comp' AND supawsorderhdr.cusno = '$vcusno2' AND supawsorderhdr.shipto = '$vshpto2'
				LIMIT 1";
	  		$result_po = mysqli_query($msqlcon,$query_po);
			$result_sup_po = mysqli_query($msqlcon,$query_sup_po);

			if(mysqli_num_rows($result_po) == 1 || mysqli_num_rows($result_sup_po) == 1){
				?>
				<script>
					alert('There are some PO not approve remaining. Cannot delete the AWS customer detail.');
				</script>
				<?php
			}else{
				$query="delete from awscusmas where cusno1 = '$vcusno1' and cusno2 = '$vcusno2' and ship_to_cd1 = '$vshpto1' and ship_to_cd2 = '$vshpto2' and Owner_Comp='$comp'";
				mysqli_query($msqlcon,$query);
				$errorx='';
			}
			
			
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
	echo "<script> document.location.href='aws_cusmas.php'; </script>";
}




?>
