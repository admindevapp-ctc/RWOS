<? session_start() ;
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
header("Location: login.php");
}


	$per_page=10;
	
	/* Database connection information */
	require('db/conn.inc');
	require('fpdf/fpdf.php');
	$periode=trim($_GET['periode']);
	$page=trim($_GET['page']);
	//total Row Count
	$sql = "SELECT * from orderhdr inner join orderdtl on orderhdr.orderno=orderdtl.orderno".
		 " inner join bm008pr on orderdtl.partno=bm008pr.ITNBR".	
		  " where orderhdr.cusno=".$cusno." and (periode=".$periode. " or SUBSTR(DueDate,1,6)=".$periode. ") order by partno, orderhdr.orderdate";
	$rResult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	$count = mysqli_num_rows($rResult);
	$i="0";
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
	//	echo "test";
	if($i=="0"){
			$pdf=new FPDF();
			$pdf->AddPage('L');
			$pdf->SetFont('Arial','B',12);
			
			$pdf->Image('images/denso.jpg',7,8,60);
			$pdf->SetY(10);
			$pdf->Cell(0,20,'ORDER BY PERIODE',0,1,'C');
			//$pdf->ln(); //jarak baris 10
			$pdf->SetFillColor(236,232,233);
			$pdf->SetDrawColor(128,0,0);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(30,10,'Customer  :',0,0,'L');
			$pdf->Cell(30,10,$cusno,0,0,'L');
			$pdf->Cell(40,10,$cusnm,0,1,'L');
			$pdf->Cell(30,12,'PERIODE   :',0,0,'L');
			$pdf->Cell(0,12,$periode,0,1,L);
			$pdf->ln(); //jarak baris 10
			$pdf->Cell(40,8, 'Part Number',1,0,'C',1);
			$pdf->Cell(70,8, 'Part Name',1,0,'C',1);
			$pdf->Cell(50,8, 'PO Number',1,0,'C',1);
			$pdf->Cell(30,8, 'Order NO',1,0,'C',1);
			$pdf->Cell(30,8, 'Order Date',1,0,'C',1);
			$pdf->Cell(20,8, 'Type',1,0,'C',1);
			$pdf->Cell(30,8, 'QTY',1,1,'C',1);
			$pdf->SetFont('Arial','',10);
			$i="1";
		}		
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$partdes=$aRow['ITDSC'];
			$corno=$aRow['Corno'];
			if($corno=="")$corno="-";
			$qty=number_format($aRow['Qty']);
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$odrsts=$aRow['odrsts'];
			switch($odrsts){
				case "U":
					$ordsts='Urgent';
					break;
				case "R":
					$ordsts='Regular';
					break;
				case "N":
					$ordsts='Normal';
					break;
				case "C":
					$ordsts='Campaign';
					break;
			}
			$pdf->Cell(40,8, $partno,1,0);
			$pdf->Cell(70,8, $partdes,1,0);
			$pdf->Cell(50,8, $corno,1,0);
			$pdf->Cell(30,8, $orderno,1,0);
			$pdf->Cell(30,8, $orddate,1,0);
			$pdf->Cell(20,8, $ordsts,1,0,'C');
			$pdf->Cell(30,8, $qty,1,1,'R');
		
	}
	//if($i=="1") $pdf->Output();  //output to browser
	if($i=="1") $pdf->Output('test.pdf', 'D');
	
	
?>
