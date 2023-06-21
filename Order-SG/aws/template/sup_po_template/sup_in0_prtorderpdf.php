<?php

session_start();
require_once('../../core/ctc_init.php');

function generatesupAWSPDF($ordno,$cusno,$path,$supno,$corno){
    
	  require('../../language/conn.inc');
    require_once('../fpdf/fpdf.php');
    
    $comp = ctc_get_session_comp();
  
    
        $sql = "SELECT *, supawsorderhdr.supno, supawsorderdtl.ordflg as flgorder ".
        " from supawsorderhdr ". 
        " inner join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.corno=supawsorderdtl.corno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp  and supawsorderhdr.supno = supawsorderdtl.supno".
        " inner join cusmas on supawsorderhdr.cusno=cusmas.cusno and supawsorderhdr.Owner_Comp=cusmas.Owner_Comp  ".
        " INNER JOIN awscusmas ON awscusmas.cusno2 = supawsorderhdr.CUST3 AND awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto ".
		" inner join supmas on supawsorderhdr.supno =supmas.supno and supawsorderhdr.Owner_Comp=supmas.Owner_Comp  ".
		" INNER JOIN shiptoma ON awscusmas.ship_to_cd1 = shiptoma.ship_to_cd AND awscusmas.cusno1 = shiptoma.cusno AND supawsorderhdr.Owner_Comp = shiptoma.Owner_Comp ".
        " where supawsorderhdr.orderno='".$ordno. "' and supawsorderhdr.cusno='". $cusno. "' and supawsorderhdr.corno='".$corno. "' and supawsorderhdr.Owner_Comp='".$comp. "' and supawsorderhdr.supno='".$supno."' order by partno " ;  
      // echo $sql;	  
      $rResult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
      //total Row Count
      $sql_sum = "SELECT SUM(qty) as 'sumqty', SUM(bprice * qty) as 'sumprice' , notes, ".
		"
		SUM(
				CASE WHEN (supawsorderdtl.ordflg = '1' OR supawsorderdtl.ordflg = '2') AND (supawsorderhdr.ordflg = '1' OR supawsorderhdr.ordflg = '2') THEN
				supawsorderdtl.qty
				ELSE 0 END
			) as 'approved_qty',
			SUM(
				CASE WHEN (supawsorderdtl.ordflg = '1' OR supawsorderdtl.ordflg = '2') AND (supawsorderhdr.ordflg = '1' OR supawsorderhdr.ordflg = '2') THEN
				supawsorderdtl.slsprice * supawsorderdtl.qty
				ELSE 0 END
			) as 'approved_amnt' 
		
		".
        " from supawsorderhdr ". 
        " inner join supawsorderdtl on supawsorderhdr.orderno=supawsorderdtl.orderno and supawsorderhdr.corno=supawsorderdtl.corno and supawsorderhdr.Owner_Comp=supawsorderdtl.Owner_Comp  and supawsorderhdr.supno = supawsorderdtl.supno".
        " inner join cusmas on supawsorderhdr.cusno=cusmas.cusno and supawsorderhdr.Owner_Comp=cusmas.Owner_Comp  ".
		" INNER JOIN awscusmas ON awscusmas.cusno2 = supawsorderhdr.CUST3 AND awscusmas.Owner_Comp = supawsorderhdr.Owner_Comp AND awscusmas.ship_to_cd2 = supawsorderhdr.shipto ".
        " inner join supmas on supawsorderhdr.supno =supmas.supno and supawsorderhdr.Owner_Comp=supmas.Owner_Comp  ".
        " inner join supawsordernts on supawsorderhdr.orderno=supawsordernts.orderno and supawsorderhdr.corno=supawsordernts.corno and  supawsorderhdr.Owner_Comp=supawsordernts.Owner_Comp ".
        " INNER JOIN shiptoma ON awscusmas.ship_to_cd1 = shiptoma.ship_to_cd AND awscusmas.cusno1 = shiptoma.cusno AND supawsorderhdr.Owner_Comp = shiptoma.Owner_Comp ".	
        " where supawsorderhdr.orderno='".$ordno. "' and supawsorderhdr.cusno='". $cusno. "' and supawsorderhdr.corno='".$corno. "' and supawsorderhdr.Owner_Comp='".$comp. "' and supawsorderhdr.supno='".$supno."' order by partno " ;  
      $sumResult = mysqli_query($msqlcon, $sql_sum ) or die(mysqli_error());
    $rResult = mysqli_query($msqlcon, $sql ) or die(mysqli_error());
    $count = mysqli_num_rows($rResult);
    $i="0";
    $i="0";
	$grand_qty = 0;
	$grand_total = 0;
	$grand_total2 = 0;
  $pdf=new FPDF();
	while ( $sumquery = mysqli_fetch_array( $sumResult ) )
	{
		$grand_qty = $sumquery['sumqty'];
		$grand_total = $sumquery['sumprice'];
		$txtnote = $sumquery['notes'];
		$grand_appamnt = $sumquery['approved_amnt'];
		$grand_app_qty = $sumquery['approved_qty'];
	}
    while ( $aRow = mysqli_fetch_array( $rResult ) )
    {
    //	echo "test";
    if($i=="0"){
        $vcusno=$aRow['CUST3'];
        $vshpno=$aRow['shipto'];
        $vcusnm=$aRow['Cusnm'];
        $exif = file_exists('../sup_logo/' . $aRow['logo']);
        $exif===false ?  $vlogo = "../images/noimage.png" : $vlogo = '../sup_logo/' . $aRow['logo'];
        
        $vsupno=$aRow['supno'];
        $vsupnm=$aRow['supnm'];
        $ship_to_nm= '';
        $adrs1=$aRow['ship_to_adrs1'];
        $adrs2=$aRow['ship_to_adrs2'];
        $adrs3=$aRow['ship_to_adrs3'];
        $pstl_cd= '';
        $comp_tel_no= '';
        $corno=$aRow['Corno'];
        $curcd=$aRow['CurCD'];
        $row_comp=$aRow['Owner_Comp'];  // add by CTC
        $county = ctc_get_counrty_comp($row_comp); // add by CTC
        if($corno=="")$corno="-";
        $pdf->AddFont('tahoma','','tahomab.php');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
  
        $pdf->Image($vlogo,10,10,40,15);
        // start add County by CTC
        $pdf->SetY(10);
        $pdf->SetX(53);
        $pdf->Cell(0,20,$county['Country'],0,1,'L');
        // end add by County CTC
        $pdf->SetY(10);
        $pdf->Cell(0,20,'Purchase Order',0,1,'C');
        
        //$pdf->ln(); //jarak baris 10
        $pdf->SetFillColor(236,232,233);
        $pdf->SetDrawColor(128,0,0);
  
  
            //start add supplier code April 2021
            $pdf->SetFont('Arial','B',10);
			$pdf->Cell(30,10,'Supplier Name',0,0,'L');

			$bsup_nm = false;
			$newY = 3;
			$havesup_nm =true;
			$havesup_nm = (($vsupnm==''||$vsupnm==null||strtolower($vsupnm)=='null')?false:true);
			if($havesup_nm){
				$vsupnm = iconv('UTF-8', 'TIS-620',$vsupnm);
				if(strlen($vsupnm)>=60){
					$tempLengthEnd = strrpos(substr($vsupnm,0,60), ' ');
					$vsupnm1 = substr(substr($vsupnm,0,60),0,$tempLengthEnd);
					$vsupnm2 = substr(substr($vsupnm,$tempLengthEnd,60),1,strlen($vsupnm));
					$bsup_nm = true;
				}else{
					$vsupnm1 =  $vsupnm;
				}
			}


			$pdf->SetFont('tahoma','',9);
			$pdf->Cell(88,10,$vsupnm. ' (' .$vsupno .')',0,0,'L');
			$pdf->SetFont('Arial','B',9);
			$pdf->Ln();
            //end add supplier code April 2021
  
  
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30,10,'Customer  :',0,0,'L');
        //$pdf->Cell(30,10,$vcusno,0,0,'L');
        $pdf->Cell(90,10,$vcusnm.' ('.$vcusno.')',0,0,'L');
        $pdf->Cell(30,10,'P.O Number    :',0,0,'L');
        $pdf->Cell(20,10,$corno,0,1,'L');
  
        $periode=$aRow['orderprd'];
        $pdf->Cell(30,10,'Ship To  :',0,0,'L');
        $pdf->Cell(90,10,$vshpno,0,0,L);
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
        $orderdate=$aRow['orderdate'];
        $orddate=substr($orderdate,-2)."/".substr($orderdate,4,2)."/".substr($orderdate,0,4);
        $pdf->Cell(32,10,'Ship To Address  :',0,0,'L');
        $pdf->SetFont('tahoma','',8);
        $bship_to_nm = false;
        $badrs1 = false;
        $badrs2 = false;
        $newY = 3;
        $haveship_to_nm =true;
        $haveship_to_nm = (($ship_to_nm==''||$ship_to_nm==null||strtolower($ship_to_nm)=='null')?false:true);
        if($haveship_to_nm){
          $ship_to_nm = iconv('UTF-8', 'TIS-620',$ship_to_nm);
          if(strlen($ship_to_nm)>=60){
            $tempLengthEnd = strrpos(substr($ship_to_nm,0,60), ' ');
            $ship_to_nm1 = substr(substr($ship_to_nm,0,60),0,$tempLengthEnd);
            $ship_to_nm2 = substr(substr($ship_to_nm,$tempLengthEnd+1,60),1,strlen($ship_to_nm));
            $bship_to_nm = true;
          }else{
            $ship_to_nm1 = $ship_to_nm;
          }
        }
        $haveadrs1 =true;
        $haveadrs1 = (($adrs1==''||$adrs1==null||strtolower($adrs1)=='null')?false:true);
        if($haveadrs1){
          $adrs1 = iconv('UTF-8', 'TIS-620',$adrs1);
          if(strlen($adrs1)>=60){
            $tempLengthEnd = strrpos(substr($adrs1,0,60), ' ');
            $adrs11 = substr(substr($adrs1,0,60),0,$tempLengthEnd);
            $adrs12 = substr(substr($adrs1,$tempLengthEnd+1,60),1,strlen($adrs1));
            $badrs1 = true;
          }else{
            $adrs11 = $adrs1;
          }
        }
        $haveadrs2 = true;
        $haveadrs2 = (($adrs2==''||$adrs2==null||strtolower($adrs2)=='null')?false:true);
        if($haveadrs2){
          $adrs2 = iconv('UTF-8', 'TIS-620',$adrs2);
          if(strlen($adrs2)>=60){
            $tempLengthEnd = strrpos(substr($adrs2,0,60), ' ');
            $adrs21 = substr(substr($adrs2,0,60),0,$tempLengthEnd);
            $adrs22 = substr(substr($adrs2,$tempLengthEnd+1,60),1,strlen($adrs2));
            $badrs2 = true;
          }else{
            $adrs21 = $adrs2;
          }
        }
        $haveadrs3 = true;
        $haveadrs3 = (($adrs3==''||$adrs3==null||strtolower($adrs3)=='null')?false:true);
        $tmpAdrs3 ="";
        if(!$haveadrs3){
          $adrs3 = '';
        }else{
          $tmpAdrs3 = $adrs3;
        }
        $havepstl_cd = true;
        $havepstl_cd = (($pstl_cd==''||$pstl_cd==null||strtolower($pstl_cd)=='null')?false:true);
        if(!$havepstl_cd){
          $pstl_cd = '';
        }else{
          if($haveadrs3){
            $tmpAdrs3 .= ',';
          }
          $tmpAdrs3 .= $pstl_cd;
        }
        $havecomp_tel_no= true;
        $havecomp_tel_no = (($comp_tel_no==''||$comp_tel_no==null||strtolower($comp_tel_no)=='null')?false:true);
        if(!$havecomp_tel_no){
          $comp_tel_no = '';
        }else{
          if($haveadrs3||$havepstl_cd){
            $tmpAdrs3 .= ',';
          }
          $tmpAdrs3 .= $comp_tel_no;
        }
  
        if($haveship_to_nm){
          $pdf->Cell(88,10,$ship_to_nm1,0,0,'L');
          $pdf->SetFont('Arial','B',9);
          $pdf->Cell(30,6.7,'Order Type  :',0,0,'L');
          $pdf->Cell(20,6.7,$ordsts,0,1,'L');
          $pdf->SetFont('tahoma','',8);
          if($bship_to_nm){
            $pdf->Cell(32,$newY,'',0,0,'L');
            $pdf->Cell(88,$newY,$ship_to_nm2,0,0,'L');
          }
          if($haveadrs1){
            if(!$badrs1&&$haveadrs1){
              $pdf->Cell(32,$newY,'',0,0,'L');
              $pdf->Cell(88,$newY,$adrs11,0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,10,'Order Date  :',0,0,'L');
              $pdf->Cell(20,10,$orddate,0,0,'L');
              $pdf->SetFont('tahoma','',8);
              if($badrs1&&$haveadrs1){
                $pdf->SetFont('tahoma','',8);
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs11,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(30,10,'Order Date  :',0,0,'L');
                $pdf->Cell(20,10,$orddate,0,0,'L');
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs12,0,0,'L');
              }
  
              if(!$badrs2&&$haveadrs2){
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs21,0,0,'L');
              }
  
              if($badrs2&&$haveadrs2){
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs21,0,0,'L');
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs22,0,0,'L');
              }
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,iconv('UTF-8', 'TIS-620',$tmpAdrs3),0,0,'L');
                // $pdf->SetFont('Arial','B',9);
                // $pdf->Cell(30,$newY,'Order Date  :',0,0,'L');
                // $pdf->Cell(20,$newY,$orddate.'qqqq',0,0,'L');
            }
          }else{
            if($haveadrs2){
              if(!$badrs2&&$haveadrs2){
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs21,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(30,10,'Order Date  :',0,0,'L');
                $pdf->Cell(20,10,$orddate,0,0,'L');
                $pdf->SetFont('tahoma','',8);
              }
  
              if($badrs2&&$haveadrs2){
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs21,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(30,10,'Order Date  :',0,0,'L');
                $pdf->Cell(20,10,$orddate,0,0,'L');
                $pdf->SetFont('tahoma','',8);
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs22,0,0,'L');
              }
                
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,iconv('UTF-8', 'TIS-620',$tmpAdrs3),0,0,'L');
            }else{
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,iconv('UTF-8', 'TIS-620',$tmpAdrs3),0,0,'L');
              $pdf->Cell(30,$newY,'',0,0,'L');
              $pdf->Cell(20,$newY,'',0,1,'L');
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,'',0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,$newY,'Order Date  :',0,0,'L');
              $pdf->Cell(20,$newY,$orddate,0,0,'L');
              // $pdf->SetFont('Arial','B',9);
              // $pdf->Cell(30,10,'Order Date  :',0,0,'L');
              // $pdf->Cell(20,10,$orddate,0,0,'L');
            }
  
          }
        }else{
          if($haveadrs1){
            if(!$badrs1){
              $pdf->Cell(88,10,$adrs11,0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,6.7,'Order Type  :',0,0,'L');
              $pdf->Cell(20,6.7,$ordsts,0,1,'L');
              $pdf->SetFont('tahoma','',8);
              $haveadrs1 = false;
            }else if($badrs1){
              $pdf->Cell(88,10,$adrs11,0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,6.7,'Order Type  :',0,0,'L');
              $pdf->Cell(20,6.7,$ordsts,0,1,'L');
              $pdf->SetFont('tahoma','',8);
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,$adrs12,0,0,'L');
              $haveadrs1 = false;
            }
            if(!$badrs2&&$haveadrs2){
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,$adrs21,0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,10,'Order Date  :',0,0,'L');
              $pdf->Cell(20,10,$orddate,0,0,'L');
              $pdf->SetFont('tahoma','',8);
              $haveadrs2 = false;
            }else if($badrs2&&$haveadrs2){
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,$adrs21,0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,10,'Order Date  :',0,0,'L');
              $pdf->Cell(20,10,$orddate,0,0,'L');
              $pdf->SetFont('tahoma','',8);
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(20,$newY,'',0,1,'L');
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,$adrs22,0,0,'L');
              $haveadrs2 = false;
            }
            $pdf->Cell(32,$newY,'              ',0,0,'L');
            $pdf->Cell(20,$newY,'',0,1,'L');
            $pdf->Cell(32,$newY,'              ',0,0,'L');
            $pdf->Cell(88,$newY,iconv('UTF-8', 'TIS-620',$tmpAdrs3),0,0,'L');
          }else{
            if($haveadrs2){
              if(!$badrs2){
                $pdf->Cell(88,10,$adrs21,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(30,6.7,'Order Type  :',0,0,'L');
                $pdf->Cell(20,6.7,$ordsts,0,1,'L');
                $pdf->SetFont('tahoma','',8);
                $haveadrs2 = false;
              }else if($badrs2){
                $pdf->Cell(88,10,$adrs21,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(30,6.7,'Order Type  :',0,0,'L');
                $pdf->Cell(20,6.7,$ordsts,0,1,'L');
                $pdf->SetFont('tahoma','',8);
                $pdf->Cell(32,$newY,'              ',0,0,'L');
                $pdf->Cell(88,$newY,$adrs22,0,0,'L');
                $haveadrs2 = false;
              }
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(20,$newY,'',0,1,'L');
              $pdf->Cell(32,$newY,'              ',0,0,'L');
              $pdf->Cell(88,$newY,iconv('UTF-8', 'TIS-620',$tmpAdrs3),0,0,'L');
              $pdf->SetFont('Arial','B',9);
              $pdf->Cell(30,$newY,'Order Date  :',0,0,'L');
              $pdf->Cell(20,$newY,$orddate,0,0,'L');
            }
          }
      
  
        }
		// add by Pasakorn N.
		$pdf->ln();
		$pdf->ln();
		$pdf->SetFont('Arial','B',9);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetXY(55, $y);
		$pdf->Cell(30,8, 'Grand Total QTY',0,0,'C',0);
		$pdf->Cell(25,8, number_format($grand_qty),1,0,'C',0);
		$pdf->cell(35,5, 'Grand Total',0,0,'C',0);
		$pdf->Cell(50,8, number_format($grand_total,2,'.',','),1,0,'C',0); // grand total
		$pdf->ln();
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->SetXY(115.5, $y-5);
		$pdf->Cell(25,5, '(Without Tax)',0,1,'C',0);
		
		// add by Pasakorn N.
		if($grand_app_qty != 0){
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(194,8,8);//Red font
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			$pdf->SetXY(55, $y);
			$pdf->Cell(30,10, 'Approved QTY: ',0,0,'L',0);
			$pdf->Cell(25,8, $grand_app_qty,1,0,C);
			$pdf->cell(35,10, 'Approved Amount: ',0,0,'L',0);
			$pdf->Cell(50,8, number_format($grand_appamnt,2,'.',','),1,0,C); 
			$pdf->SetXY(-55, $y+5);
			$pdf->Cell(20,17, '(Approved and price available items only)',0,0,'C',0);
			//add by Pasakorn N.
		}
		$pdf->ln();
		$pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(30,8, 'Part Number',1,0,'C',1);
        $pdf->Cell(50,8, 'Part Name',1,0,'C',1);
        $pdf->Cell(25,8, 'Q T Y',1,0,'C',1);
        $pdf->Cell(30,8, 'Price ('. $curcd. ')',1,0,'C',1);
        $pdf->Cell(30,8, 'Total('. $curcd. ')',1,0,'C',1);
        $pdf->Cell(30,8, 'Due Date',1,1,'C',1);
        //$pdf->Cell(30,8, 'Total(SD)',1,1,'C',1);
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
        $curcd=strtoupper($aRow['CurCD']);
        $disco=$aRow['disc'];
        $vdiscount=($vprice*$disco)/100;
        $discount=number_format($vdiscount,0,'.',',');
        $ttl=$vqty*$slsprice;
        $ttlSG=$ttl*$SGprice;
        if($aRow['bprice'] == "0" || $aRow['bprice'] == Null )
        {
          $bprice="";
        }
        else{
          $bprice=number_format($aRow['bprice'],2,'.',',');
        }
        if($ttl == "0" || $ttl == Null )
        {
          $amount="";
        }
        else{
          $amount=number_format($ttl,2,'.',',');
        }
        $DueDate=$aRow['DueDate'];
        $dudate=substr($DueDate,-2)."/".substr($DueDate,4,2)."/".substr($DueDate,0,4);
        /*if($curcd=='SD'){
          $amountSG=number_format($ttlSG,2,'.',',');
          $amount=number_format($ttl,2,'.',',');
        }else{
          $amountSG=number_format($ttlSG,4,'.',',');
          $amount=number_format($ttl,2,'.',',');
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
        $pdf->Cell(25,8, $qty,1,0,'C');
        $pdf->Cell(30,8, $bprice,1,0,R);
        $pdf->Cell(30,8, $amount,1,0,R);
        //$pdf->Cell(30,8, $ttl,1,0,R);
        //$pdf->Cell(20,8, $ordsts,1,0,'C');
        $pdf->Cell(30,8, $dudate,1,1,'C');
  
        if(trim($ordflg)=="R"){
          $pdf->setX($pos_x);
          $pdf->Cell(165,8, $msg,1,1);
        }		
    
    }

	$pdf->Cell(30,10,'Note  :',0,0,'L');
	$pdf->SetFont('tahoma','',7);
	$pdf->Cell(20,10,$txtnote,0,1,'L');

    //if($i=="1") $pdf->Output();  //output to browser
    $fileName = $comp.$cusno.$vsupno.$corno.date("YmdHis").'.pdf';
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
    if($i=="1") $pdf->Output($path.$fileName,'F');
    return $fileName;	
  
  }
?>