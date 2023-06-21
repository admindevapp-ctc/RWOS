<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];

		$comp = ctc_get_session_comp(); // add by CTC

	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
	$per_page=10;
	
	/* Database connection information */
	require('../language/conn.inc');
	require('fpdf/fpdf.php');
	$ordno=trim($_GET['ordno']);
	$corno=trim($_GET['corno']);
	$shpno=trim($_GET['shpno']);
	//total Row Count
	$sql = "SELECT * from orderhdr ".
		" inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Corno=orderdtl.Corno and orderhdr.Owner_Comp=orderdtl.Owner_Comp ".
		" inner join bm008pr on orderdtl.partno=bm008pr.ITNBR and orderdtl.Owner_Comp=bm008pr.Owner_Comp ".
		" inner join shiptoma on orderhdr.shipto=shiptoma.ship_to_cd and orderhdr.cusno=shiptoma.cusno and orderhdr.Owner_Comp=shiptoma.Owner_Comp ".	//12/20/2018 CTc P.Pawan
		" where  orderhdr.orderno='".$ordno."' and orderhdr.corno='".$corno."' and orderhdr.Owner_Comp='".$comp."' order by partno " ;  // edit by CTC
	$sql_sum = "SELECT SUM(qty) as 'sumqty', SUM(bprice * qty) as 'sumprice'".
		" from orderhdr ". 
		" inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.corno=orderdtl.corno and  orderhdr.Owner_Comp=orderdtl.Owner_Comp ".
		" inner join cusmas on orderhdr.cusno=cusmas.cusno and orderhdr.Owner_Comp=cusmas.Owner_Comp ".
		" inner join shiptoma on orderhdr.shipto=shiptoma.ship_to_cd and orderhdr.cusno=shiptoma.cusno and orderhdr.Owner_Comp=shiptoma.Owner_Comp".	
		" where orderhdr.orderno='".$ordno. "' and Cusmas.Cust3='". $cusno. "' and orderhdr.corno='".$corno. "' and orderhdr.Owner_Comp='".$comp. "' order by partno " ;  // edit by CTC
	$rResult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
	$sumResult = mysqli_query($msqlcon, $sql_sum ) or die(mysqli_error());
	$count = mysqli_num_rows($rResult);
	$i="0";
	$grand_qty = 0;
	$grand_total = 0;
	$grand_total2 = 0;
	while ( $sumquery = mysqli_fetch_array( $sumResult ) )
	{
		$grand_qty = $sumquery['sumqty'];
		$grand_total = $sumquery['sumprice'];;
	}
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
	$curcd=strtoupper($aRow['CurCD']);
	$row_comp=$aRow['Owner_Comp'];
	$county = ctc_get_counrty_comp($row_comp);
	if($i=="0"){
			$pdf=new FPDF();
			$pdf->AddPage();
			$pdf->AddFont('tahoma','','tahoma.php');
			$pdf->SetFont('Arial','B',12);
			
			$pdf->Image('images/denso.jpg',7,8,40);
			// start add by CTC
			$pdf->SetY(10);
			$pdf->SetX(48);
			$pdf->Cell(0,20,$county['Country'],0,1,'L');
			// end add by CTC
			$pdf->SetY(10);
			$pdf->Cell(0,20,'DEALER ORDER',0,1,'C');
			//$pdf->ln(); //jarak baris 10
			$pdf->SetFillColor(236,232,233);
			$pdf->SetDrawColor(128,0,0);
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(30,10,'Customer  :',0,0,'L');
			//$pdf->Cell(30,10,$cusno,0,0,'L');
			$pdf->Cell(90,10,$cusnm.' ( '.$cusno.' )',0,0,'L');
			$corno=$aRow['Corno'];
			$pdf->Cell(30,10,'PO Number :',0,0,'L');
			$pdf->Cell(20,10,$corno,0,1,L);
			$periode=$aRow['orderprd'];
			$shpCd=$aRow['shipto'];
			$pdf->Cell(30,10,'Ship To  :',0,0,'L');
			$pdf->Cell(90,10,$shpCd,0,0,L);
			$odrsts=$aRow['ordtype'];
			switch($odrsts){
				case "U":
					$ordsts='Urgent';
					break;
				case "R":
					$ordsts='Request Due Date';
					break;
				case "N":
					$ordsts='Normal';
					break;
				case "C":
					$ordsts='Campaign';
					break;
			}
			
			$pdf->Cell(30,10,'Order No  :',0,0,'L');
			$pdf->Cell(20,10,$ordno,0,1,'L');
			$pdf->Cell(32,10,'Ship To Address  :',0,0,'L');
			$ship_to_nm=$aRow['ship_to_nm'];
			$adrs1=$aRow['adrs1'];
			$adrs2=$aRow['adrs2'];
			$adrs3=$aRow['adrs3'];
			$pstl_cd=$aRow['pstl_cd'];
			$comp_tel_no=$aRow['comp_tel_no'];
			$ship_to_nm = (($ship_to_nm==''||$ship_to_nm==null||$ship_to_nm=='null')?$ship_to_nm='':$ship_to_nm);
			$ship_to_nm = iconv('UTF-8', 'TIS-620',$ship_to_nm);
			$pdf->SetFont('tahoma','',8);
			$bship_to_nm = false;
			$badrs1 = false;
			$badrs2 = false;
			$newY = 3;
			if(strlen($ship_to_nm)>=60){
				$tempLengthEnd = strrpos(substr($ship_to_nm,0,60), ' ');
				$ship_to_nm1 = substr(substr($ship_to_nm,0,60),0,$tempLengthEnd);
				$ship_to_nm2 = substr(substr($ship_to_nm,$tempLengthEnd,60),1,strrpos(substr($ship_to_nm,$tempLengthEnd,60),' '));
				$bship_to_nm = true;
			}else{
				$ship_to_nm1 = $ship_to_nm;
			}
			
		
			$pdf->Cell(88,10,$ship_to_nm1,0,0,'L');
			
			$pdf->SetFont('Arial','B',9);
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$pdf->Cell(30,6.7,'Order Type  :',0,0,'L');
			$pdf->Cell(20,6.7,$ordsts,0,1,'L');
			$pdf->SetFont('tahoma','',8);
			if($bship_to_nm){
				$pdf->Cell(32,$newY,'',0,0,'L');
				$pdf->Cell(88,$newY,$ship_to_nm2,0,0,'L');
			}
			$haveadrs1 =true;
			$adrs1 = (($adrs1==''||$adrs1==null||$adrs1=='null')?$haveadrs1=false:$adrs1);
			$adrs1 = iconv('UTF-8', 'TIS-620',$adrs1);
			if($haveadrs1){
				if(strlen($adrs1)>=60){
					$tempLengthEnd = strrpos(substr($adrs1,0,60), ' ');
					$adrs11 = substr(substr($adrs1,0,60),0,$tempLengthEnd);
					$adrs12 = substr(substr($adrs1,$tempLengthEnd,60),1,strrpos(substr($adrs1,$tempLengthEnd,60),' '));
					$badrs1 = true;
				}else{
					$adrs11 = $adrs1;
				}
			}
			
			if(!$badrs1&&$haveadrs1){
				$pdf->Cell(32,$newY,'',0,0,'L');
				$pdf->Cell(88,$newY,$adrs11,0,0,'L');
			}
			

			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(30,10,'Order Date  :',0,0,'L');
			$pdf->Cell(20,10,$orddate,0,0,'L');
			$pdf->SetFont('tahoma','',8);
			if($badrs1&&$haveadrs1){
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(20,$newY,'',0,1,'L');
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(88,$newY,$adrs11,0,0,'L');
				
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(20,$newY,'',0,1,'L');
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(88,$newY,$adrs12,0,0,'L');
			}
			
			
			$haveadrs2 = true;
			$adrs2 = (($adrs2==''||$adrs2==null||$adrs2=='null')?$haveadrs2=false:$adrs2);
			$adrs2 = iconv('UTF-8', 'TIS-620',$adrs2);
			if($haveadrs2){
				if(strlen($adrs2)>=60){
					$tempLengthEnd = strrpos(substr($adrs2,0,60), ' ');
					$adrs21 = substr(substr($adrs2,0,60),0,$tempLengthEnd);
					$adrs22 = substr(substr($adrs2,$tempLengthEnd,60),1,strrpos(substr($adrs2,$tempLengthEnd,60),' '));
					$badrs2 = true;
				}else{
					$adrs21 = $adrs2;
				}
			}
			if(!$badrs2&&$haveadrs2){
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(20,$newY,'',0,1,'L');
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(88,$newY,$adrs21,0,0,'L');
			}
			if($badrs2&&$haveadrs2){
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(20,$newY,'',0,1,'L');
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(88,$newY,$adrs21,0,0,'L');
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(20,$newY,'',0,1,'L');
				$pdf->Cell(32,$newY,'              ',0,0,'L');
				$pdf->Cell(88,$newY,$adrs22,0,0,'L');
			}

			$adrs3 = (($adrs3==''||$adrs3==null||$adrs3=='null')?$adrs3='':$adrs3);
			$pstl_cd = (($pstl_cd==''||$pstl_cd==null||$pstl_cd=='null')?$pstl_cd='':' , '.$pstl_cd);
			$comp_tel_no = (($comp_tel_no==''||$comp_tel_no==null||$comp_tel_no=='null')?$comp_tel_no='':' , '.$comp_tel_no);
			$tmpAdrs3 = $adrs3.$pstl_cd.$comp_tel_no;
			$pdf->Cell(32,$newY,'              ',0,0,'L');
			$pdf->Cell(20,$newY,'',0,1,'L');
			$pdf->Cell(32,$newY,'              ',0,0,'L');
			$pdf->Cell(88,$newY,iconv('UTF-8', 'TIS-620',$tmpAdrs3),0,0,'L');
			$pdf->ln();
			
			$pdf->SetFont('Arial','B',8);
			
			/**$pdf->ln(); //jarak baris 10
			$pdf->Cell(40,8, 'Part Number',1,0,'C',1);
			$pdf->Cell(70,8, 'Part Name',1,0,'C',1);
			$pdf->Cell(40,8, 'Due Date',1,0,'C',1);
			//$pdf->Cell(30,8, 'Order NO',1,0,'C',1);
			//$pdf->Cell(30,8, 'Order Date',1,0,'C',1);
			//$pdf->Cell(20,8, 'Type',1,0,'C',1);
			$pdf->Cell(40,8, 'QTY',1,1,'C',1);
			$pdf->SetFont('Arial','',10);
			$i="1"; **/
			
			$pdf->ln();
			$pdf->ln();
			$pdf->SetFont('Arial','B',9);
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->SetXY(65, $y);
			
			$pdf->Cell(30,8, 'Grand Total QTY',0,0,'C',0);
			$pdf->Cell(20,8, number_format($grand_qty),1,0,'C',0);
			$pdf->cell(30,5, 'Grand Total',0,0,'C',0);
			$pdf->Cell(50,8, number_format($grand_total,2,'.',','),1,0,'C',0); // grand total
			
			$pdf->ln();
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->SetXY(115.5, $y-5);
			$pdf->Cell(30,5, '(Without Vat)',0,0,'C',0);
			$pdf->ln();

			
			$pdf->SetFont('Arial','B',8);
			$pdf->ln(); //jarak baris 10
			$pdf->Cell(30,8, 'Part Number',1,0,'C',1);
			$pdf->Cell(50,8, 'Part Name',1,0,'C',1);
			$pdf->Cell(25,8, 'Q T Y',1,0,'C',1);
			$pdf->Cell(30,8, 'Price ('. $curcd. ')',1,0,'C',1);
			$pdf->Cell(30,8, 'Total('. $curcd. ')',1,0,'C',1);
			$pdf->Cell(30,8, 'Due Date',1,1,'C',1);
			$pdf->SetFont('Arial','',7);
			$i="1";
		}		
			$orderno=$aRow['orderno'];
			$partno=$aRow['partno'];
			$partdes=$aRow['itdsc'];
			
			$vqty=$aRow['qty'];
			$qty=number_format($aRow['qty'],0,'.',',');
			$vprice=$aRow['bprice'];
			$slsprice=$aRow['slsprice'];
			$SGprice=$aRow['SGPrice'];
			$bprice=number_format($aRow['bprice'],2,'.',',');
			
			$disco=$aRow['disc'];
			$vdiscount=($vprice*$disco)/100;
			$discount=number_format($vdiscount,0,'.',',');
			$ttl=$vqty*$slsprice;
			$ttlSG=$ttl*$SGprice;
			$amount=number_format($ttl,2,'.',',');
			$DueDate=$aRow['DueDate'];
			$dudate=substr($DueDate,-2)."/".substr($DueDate,4,2)."/".substr($DueDate,0,4);
			/*if($curcd=='SD'){
				$amountSG=number_format($ttlSG,2,'.',',');
			}else{
				$amountSG=number_format($ttlSG,4,'.',',');
			}*/
			$orderdate=$aRow['orderdate'];
			$orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
			$ordflg=$aRow['flgorder'];
			$msg="";
			if(trim($ordflg)=="R"){
				$qryreject="select * from rejectorder where trim(orderno)='".$ordno."' ";
				$qryreject=$qryreject. " and partno='".$partno."'"; 
				$sqlreject=mysqli_query($msqlcon,$qryreject);		
					if($hsl = mysqli_fetch_array ($sqlreject)){
						$msg="Status : Rejected,   Reason   :  ". trim($hsl['message']);
					
					}
				}
			
			
			if(trim($ordflg)=="R"){
				$pdf->Cell(30,16, $partno,1,0);
				$pos_x=$pdf->GetX();
				$pdf->setX($pos_x);
			}else{
				$pdf->Cell(30,8, $partno,1,0);
			}
			$pdf->Cell(50,8, $partdes,1,0);
			$pdf->Cell(25,8, $qty,1,0,'R');
			$pdf->Cell(30,8, $bprice,1,0,R);
			$pdf->Cell(30,8, $amount,1,0,R);
			//$pdf->Cell(20,8, $ordsts,1,0,'C');
			$pdf->Cell(30,8, $dudate,1,1,'R');
			
			if(trim($ordflg)=="R"){
				$pdf->setX($pos_x);
				$pdf->Cell(165,8, $msg,1,1);
			}

				
	}
	
/*Zia Added to add note..start*/	
	
	$qrycusmas = "SELECT *, orderdtl.ordflg as flgorder from orderhdr ".
	  " inner join orderdtl on orderhdr.orderno=orderdtl.orderno and orderhdr.Owner_Comp=orderdtl.Owner_Comp ".
      " inner join cusmas on orderhdr.cusno=cusmas.cusno and orderhdr.Owner_Comp=cusmas.Owner_Comp ".
      " inner join shiptoma on orderhdr.shipto=shiptoma.ship_to_cd and orderhdr.cusno=shiptoma.cusno and orderhdr.Owner_Comp=shiptoma.Owner_Comp ".
	  " inner join ordernts on orderhdr.orderno=ordernts.orderno and orderhdr.cusno=ordernts.cusno and orderhdr.Owner_Comp=ordernts.Owner_Comp ".
	  " where orderhdr.orderno='".$ordno. "' and Cusmas.Cust3='". $cusno. "' and Cusmas.Owner_Comp='". $comp. "' order by partno " ;  // edit by CTC
	$sqlcusmas=mysqli_query($msqlcon,$qrycusmas);		
		if($hslcusmas = mysqli_fetch_array ($sqlcusmas)){
			$vnote=$hslcusmas['notes'];
			$txtnote = iconv('UTF-8', 'TIS-620',$vnote);
			$pdf->Cell(30,10,'Note  :',0,0,'L');
			$pdf->SetFont('tahoma','',7);
			$pdf->Cell(20,10,$txtnote,0,1,'L');
			}
/*Zia Added to add note..end*/	
	
	
	//if($i=="1") $pdf->Output();  //output to browser
	if($i=="1") $pdf->Output('test.pdf','I');
		
?>
