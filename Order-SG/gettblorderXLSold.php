<? session_start() ;
ob_start();
?>
<?
if(isset($_SESSION['cusno']))
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
//header("Location: login.php");
}
require('db/conn.inc');
$periode=trim($_GET['periode']);
$page=trim($_GET['page']);
$sort=trim($_GET['sort']);
$namafield=trim($_GET['namafield']);

$filename=$Cusno.$periode.".xls";
$filename="text.xls";
include_once("class.export_excel.php");
$excel_obj=new ExportExcel("$filename");

//first of all unset these variables
unset($_SESSION['report_header']);
unset($_SESSION['report_values']);
//note that the header contain the three columns and its a array
//$_SESSION['report_header']=array("Order Number","Dealer PO","Order Date","Order Status","Part Number", "Part Description","Order QTY"); 
$_SESSION['report_header']=array("Order Number","Dealer PO","Order Date"); 

	/* Database connection information */
	
	$sQuery = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
			  " inner join bm008pr on orderdtl.partno=bm008pr.ITNBR".	
			  " where orderhdr.cusno=".$cusno." and (periode=".$periode. " or SUBSTR(DueDate,1,6)=".$periode. ") ";
//			$sQuery = $sQuery . " order by partno, orderhdr.orderdate";		   
	  	

		
	$i = 1;
	$rResult = mysqli_query($msqlcon, $sQuery ) or die(mysqli_error());
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$corno=$aRow['Corno'];
			$desc=$aRow['ITDSC'];
			if($corno=="")$corno="-";
			$qty=number_format($aRow['Qty']);
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$odrsts=$aRow['odrsts'];
			switch($odrsts){
				case "U":
					$ordsts="Urgent";
					break;
				case "R":
					$ordsts="Regular";
					break;
				case "N":
					$ordsts="Normal";
					break;
				case "C":
					$ordsts="Campaign";
					break;
			}
	
			$_SESSION['report_values'][$i][0]=$orderno." ";
			$_SESSION['report_values'][$i][1]=$corno. " ";
			$_SESSION['report_values'][$i][2]=$orddate. " ";
			//$_SESSION['report_values'][$i][3]=$ordsts;
			//$_SESSION['report_values'][$i][4]=$partno;
			//$_SESSION['report_values'][$i][5]=$desc;
			//$_SESSION['report_values'][$i][6]=$qty;
			
			
		$i++;
	
	}
//print_r($_SESSION['report_values']);
$excel_obj->setHeadersAndValues($_SESSION['report_header'],$_SESSION['report_values']); 
//now generate the excel file with the data and headers set
$excel_obj->GenerateExcelFile();

 
// memanggil function penanda akhir file excel

	

?>
