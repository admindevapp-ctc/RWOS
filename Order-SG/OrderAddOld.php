<? session_start() ?>
<?
if (session_is_registered('cusno'))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['cusad1'];
		$_SESSION['cusad2'];
		$_SESSION['cusad3'];
		$_SESSION['type'];
		$_SESSION['zip'];
		$_SESSION['state'];
		$_SESSION['phone'];
		$_SESSION['password'];
		

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$cusad1=$_SESSION['cusad1'];
	$cusad2=$_SESSION['cusad2'];
	$cusad3=$_SESSION['cusad3'];
	$type=$_SESSION['type'];
	$zip=$_SESSION['zip'];
	$state=$_SESSION['state'];
	$phone=$_SESSION['phone'];
	$password=$_SESSION['password'];
	
   
}else{	
header("Location: login.php");
}

$vaction=trim($_GET['action']);
require('db/conn.inc');
//echo $action;
$vordno=trim($_GET['ordno']);
$vcusno=trim($_GET['cusno']);
switch ($action)
{

case 'new':
	checknew($xordno);
	break;
case 'edit':
	checkedit($vcusno, $vordno, $action);
	break;
case 'delete':
	//delete header 
	checkdelete($vcusno, $vordno);
	break;
}


		
function checknew($xordno){
		echo "<script> document.location.href='addorder.php'; </script>";	
	
}

function checkedit($vcusno, $vordno, $action){
	//echo $action;
	$query="select * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.cusno=orderdtl.cusno  inner join bm008pr on orderdtl.partno=bm008pr.ITNBR where trim(orderhdr.orderno) = '$vordno' and trim(orderhdr.cusno)='".$vcusno."'";
//echo "<br>". $query;
	$sql=mysqli_query($msqlcon,$query);		
	$x=0;
	while($hasil = mysqli_fetch_array ($sql)){
			$cdate=$hasil['orderdate'];
			$partno=$hasil['partno'];
			$partdesc=$hasil['ITDSC'];
			$odrsts=$hasil['odrsts'];
			$qty=$hasil['Qty'];
			$corno=$hasil['Corno'];
			$duedate=$hasil['DueDate'];
			$dlvby=$hasil['DlvBy'];
			$query2="insert into tmporder values('$vordno','$vperiode','$cdate','$vcusno','$partno','$partdesc','$odrsts',$qty,'$corno','$duedate','$dlvby')";
			mysqli_query($msqlcon,$query2);
			//echo $query2."<br>";
			$x=$x+1;
		}
	
	
	if($x==0){	
		echo "<script> document.location.href='mainAdd.php'; </script>";
	}else{
			echo "<script>document.location.href='edtAddorder.php?action=$action&ordno=$vordno&cusno=$vcusno&odrsts=$odrsts&cdate=$cdate&corno=$corno&dlvby=$dlvby';</script>";
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
