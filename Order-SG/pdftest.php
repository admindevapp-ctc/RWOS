<?php
/*
require('fpdf/fpdf_alpha.php');
$pdf=new PDF_ImageAlpha();
$pdf->AddPage();
$pdf->SetY(10);

$pdf->Cell(0,20,'Purchase Order',0,1,'C');
$pdf->SetFillColor(236,232,233);
$pdf->SetDrawColor(128,0,0);
$maskImg = $pdf->Image('sup_logo/622897.png', 0,0,0,0, '', '', true); 
//$pdf->SetFont('Arial','',16);
//$pdf->MultiCell(0,8, str_repeat('Hello World! ', 180));

// A) provide image + separate 8-bit mask (best quality!)
/*
// first embed mask image (w, h, x and y will be ignored, the image will be scaled to the target image's size)
$maskImg = $pdf->Image('mask.png', 0,0,0,0, '', '', true); 
// embed image, masked with previously embedded mask
$pdf->Image('image.png',55,10,100,0,'','', false, $maskImg);

// B) use alpha channel from PNG (alpha channel converted to 7-bit by GD, lower quality)
$pdf->ImagePngWithAlpha('image_with_alpha.png',55,100,100);

// C) same as B), but using Image() method that recognizes the alpha channel
$pdf->Image('image_with_alpha.png',55,190,100);
*/


require('fpdf/fpdf_alpha.php');
$pdf=new FPDF();
			$pdf->AddFont('tahoma','','tahomab.php');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',12);

			$pdf->SetY(10);
			$pdf->SetX(48);
			$pdf->Cell(0,20,$county['Country'],0,1,'L');
			$pdf->SetY(10);
			$pdf->Cell(0,20,'Purchase Order',0,1,'C');
            
			$pdf->Image('sup_logo/622897.png' ,10,10,20,20);
			$pdf->SetY(10);
			$pdf->SetX(48);
$pdf->Output();
?>