<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['password'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
		$_SESSION['user'];
		$_SESSION['dealer'];
		$_SESSION['group'];
		$_SESSION['type'];
		$_SESSION['custype'];
		

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
header("Location: login.php");
}
$vaction=trim($_GET['action']);




//Order Number and Date 
$cyear=date('Y');
$cmonth=date('m');
$cdate=date('d');
$cymd=date('Ymd');
if((int)$cdate>15){
	$cmonth=(int)$cmonth+2;
}else{
	$cmonth=(int)$cmonth+1;
}
if((int)$cmonth>12){
	$cmonth=(int)$cmonth-12;
	$cyear=(int)$cyear+1;
}
$xmonth=str_pad((int) $cmonth,2,"0",STR_PAD_LEFT);
$experiode=$cyear.$xmonth;
$xperiode=substr($experiode,-4);
require('db/conn.inc');
$query="select * from userid where trim(cusno) = '$cusno'";
$sql=mysqli_query($msqlcon,$query);		
$hasil = mysqli_fetch_array ($sql);
$order=$hasil['rordno'];
if(strlen(trim($order))!=7){
	$vordno=$xperiode."001";
}else{
	$ordprd=substr($order,0,4);
	if($ordprd!=$xperiode){
		$vordno=$xperiode."001";
		}else{
		$ordval=(int)substr($order,-3);
		$ordval1=$ordval+1;
		$strordval=str_pad((int) $ordval1,3,"0",STR_PAD_LEFT);
		$vordno=$xperiode.$strordval;
		}
}
		
		
		
		
$xordno=$alias.$vordno."R";
//echo $xordno;
//echo $table;
//echo $cusno;
//echo $action;

//echo "customer number=".$cusno;
$vperiode=trim($_GET['periode']);
$vordno=trim($_GET['ordno']);
$vcusno=trim($_GET['cusno']);
switch ($vaction)
{

case 'new':
	checknew($xordno, $cusno, $table);
	break;
case 'edit':
	checkedit($vcusno, $vordno, $vperiode,$action, $table);
	break;
case 'delete':
	//delete header 
	checkdelete($vcusno, $vordno, $vperiode);
	break;
}


		
function checknew($xordno, $cusno, $table){
	//echo $action;
	$query="select * from orderhdr where trim(orderno) = '$xordno'";
   	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
		echo "<script> alert('Order document has already found');</script>";	
		echo "<script>document.location.href='main.php';</script>";	
	}else{
		
		$query9="delete  from ".$table. "  where trim(orderno) = '$xordno' and trim(cusno)='".$cusno."'";
	mysqli_query($msqlcon,$query9);
	   	
	//echo $query9;
		echo "<script> document.location.href='order2.php'; </script>";	
	}
}

function checkedit($vcusno, $vordno, $vperiode,$action, $table){
	//echo $action;
	$query="select * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.cusno=orderdtl.cusno  inner join bm008pr on orderdtl.partno=bm008pr.ITNBR where trim(orderhdr.orderno) = '$vordno' and trim(orderhdr.cusno)='".$vcusno."'";
//echo "<br>". $query;
	$sql=mysqli_query($msqlcon,$query);		
	$x=0;
	while($hasil = mysqli_fetch_array ($sql)){
			$cdate=$hasil['orderdate'];
			$partno=$hasil['partno'];
			$partdesc=$hasil['ITDSC'];
			$odrstst=$hasil['ordtype'];
			$bprice=$hasil['bprice'];
			$disc=$hasil['disc'];
			$slsprice=$hasil['slsprice'];
			$qty=$hasil['qty'];
			$corno=$hasil['Corno'];
			$query2="insert into ".$table. " (orderno,orderprd, orderdate, cusno, partno, partdes, ordstatus,qty, bprice,disc, slsprice, Corno) values('$vordno','$vperiode','$cdate','$vcusno','$partno','$partdesc','$odrstst',$qty,$bprice, $disc, $slsprice, '$corno')";
			mysqli_query($msqlcon,$query2);
			//echo $query2."<br>";
			$x=$x+1;
		}
	
	
	if($x==0){	
		echo "<script> document.location.href='main.php'; </script>";	
        //echo $query;
	}else{
			echo "<script>document.location.href='edtorder.php?action=$action&ordno=$vordno&cusno=$vcusno&periode=$vperiode&cdate=$cdate&corno=$corno';</script>";
	//echo $query2;
	}
}



function checkdelete($vcusno, $vordno, $vperiode){
	//echo $action;
	$query="delete  from orderhdr where trim(orderno) = '$vordno' and trim(cusno)='".$vcusno."'";
	mysqli_query($msqlcon,$query);		
	$query1="delete  from orderdtl where trim(orderno) = '$vordno' and trim(cusno)='".$vcusno."'";
	
    mysqli_query($msqlcon,$query1);		

	echo "<script> document.location.href='main.php'; </script>";
	
}




?>
