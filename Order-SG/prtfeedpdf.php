<? session_start() ?>
<?
//if (session_is_registered('cusno'))
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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}


include('chklogin.php');



	
	/* Database connection information */
	   require('fpdf/fpdf.php');
	   include "crypt.php";
	   require('db/conn.inc');
	   $var = decode($_SERVER['REQUEST_URI']);
	   $vordno=trim($var['ordno']);
	   $vcusno=trim($var['cusno']);
	   $vshpno=trim($var['shpno']);
	   $vperiode=trim($var['periode']);
	   $vcorno=trim($var['corno']);
	   $vorderdt=trim($var['orderdt']);
	  
			$pdf=new FPDF();
			$pdf->AddPage('L');
			$pdf->SetFont('Arial','B',12);
			
			$pdf->Image('images/denso.jpg',7,8,60);
			$pdf->SetY(10);
			$pdf->Cell(0,20,'DEALER FEEDBACK',0,1,'C');
			//$pdf->ln(); //jarak baris 10
			$pdf->SetFillColor(236,232,233);
			$pdf->SetDrawColor(128,0,0);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(30,10,'Customer  :',0,0,'L');
			$pdf->Cell(30,10,$vcusno,0,0,'L');
			$pdf->Cell(110,10,$cusnm,0,0,'L');
			$pdf->Cell(30,10,'PO Number :',0,0,'L');
			$pdf->Cell(20,10,$vcorno,0,1,'L');
			$xshpno=$vshpno;
			$pdf->Cell(30,10,'Cust.  No   :',0,0,'L');
			$pdf->Cell(140,10,$vshpno,0,0,L);
			$odrsts=substr($vordno,-1);
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
			
			$pdf->Cell(30,10,'Order Type  :',0,0,'L');
			$pdf->Cell(20,10,$ordsts,0,1,'L');

			$pdf->Cell(30,10,'Order No   :',0,0,'L');
			$pdf->Cell(140,10,$vordno,0,0,'L');
			
			$orddate=substr($vorderdt,-2)."/".substr($vorderdt,4,2)."/".substr($vorderdt,0,4);
			$pdf->Cell(30,10,'Order Date  :',0,0,'L');
			$pdf->Cell(20,10,$orddate,0,1,'L');
			
			$pdf->ln(); //jarak baris 10
			$pdf->Cell(70,8, 'Part Number',1,0,'C',1);
			$pdf->Cell(40,8, 'Order Qty',1,0,'C',1);
			//$pdf->Cell(184,8, 'Feed Back / Answer',1,1,'C',1);
			$pdf->Cell(40,8, 'Back Order',1,0,'C',1);
			$pdf->Cell(40,8, 'Ready Stock',1,0,'C',1);
			$pdf->Cell(40,8, 'S/I Issue',1,0,'C',1);
			$pdf->Cell(40,8, 'New Order',1,1,'C',1);
			$pdf->SetFont('Arial','',8);

		  	$query="select * from orderdtl left join feedback on orderdtl.cusno=feedback.cusno and left(orderdtl.corno,10)=left(feedback.corno,10) and orderdtl.partno=feedback.partno where trim(orderdtl.cust3) ='".$vcusno. "' and trim(orderdtl.orderno)='".$vordno."'  order by feedback.partno";
		
		$flag="1";
		$sql=mysqli_query($msqlcon,$query);	
		$mcount = mysqli_num_rows($sql);
			while($hasil = mysqli_fetch_array ($sql)){
				$partno=$hasil['partno'];
				$qty=number_format($hasil['qty']);
				$qty2=number_format($hasil['qty2']);
				$qty3=number_format($hasil['qty3']);
				$qty4=number_format($hasil['qty4']);
				$qty5=number_format($hasil['qty5']);
				$pdf->Cell(70,8, $partno,1,0);
				$pdf->Cell(40,8, $qty,1,0, 'R');
				$pdf->Cell(40,8, $qty2,1,0,'R');
				$pdf->Cell(40,8, $qty4,1,0,R);
				$pdf->Cell(40,8, $qty5,1,0,R);
				$pdf->Cell(40,8, $qty3,1,1,'R');

				
		  }
	
		$pdf->Output('test.pdf','I'); 
		  ?>