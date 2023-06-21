<?
/* plugins fpdf */
	$rootpath = $_SERVER['DOCUMENT_ROOT']."/dev/pdfontheair/"; // rubah, disesuaikan dengan path document berada

	define('FPDF_FONTPATH','./pdf/font/');
	require_once('./pdf/lib/fpdf.inc.php');
	require_once('./pdf/lib/pdftable.inc.php');
	require_once('./pdf/lib/color.inc.php');
	require_once('./pdf/lib/htmlparser.inc.php');
	
	$htmlTemplate ='
	<table width="100%" border="1" cellspacing="1" cellpadding="1">
	<tr><td colspan="5" bgcolor="#666666">DATA ROW</td></tr>
      <tr>
        <td>NO</td>
        <td>FIELD1</td>
        <td>FIELD2</td>
        <td>FIELD3</td>
        <td>FIELD4</td>
      </tr>';
      
	  for($i=1;$i<100;$i++){
	  $htmlTemplate .='
	  <tr>
        <td>'.$i.'</td>
        <td>data 1</td>
        <td>data 2</td>
        <td>data 3</td>
        <td>data 4</td>
      </tr>';
	  }
	$htmlTemplate .='</table><BR><BR>';


	$pageTambahan = '
	<table width="100%" border="1" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="2" bgcolor="#666666">TABLE HEADER - PAGE TAMBAHAN</td>
  </tr> 
  <tr>
    <td>1</td>
    <td>2</td>
  </tr>
  <tr>
    <td>3</td>
    <td>4</td>
  </tr>
  <tr>
    <td colspan="2">5</td>
  </tr>
  <tr>
    <td>6</td>
    <td>7</td>
  </tr>
  <tr>
    <td>8</td>
    <td>9</td>
  </tr>
  <tr>
    <td colspan="2">10</td>
  </tr>
  <tr>
    <td>11</td>
    <td>12</td>
  </tr>
  <tr>
    <td colspan="2">13</td>
  </tr>
  <tr>
    <td>14</td>
    <td>15</td>
  </tr>
  <tr>
    <td colspan="2">16</td>
  </tr>
  <tr>
    <td>17</td>
    <td>18</td>
  </tr>
  <tr>
    <td colspan="2">19</td>
  </tr>
  <tr>
    <td>20</td>
    <td>21</td>
  </tr>
  <tr>
    <td colspan="2">22</td>
  </tr>
  <tr>
    <td>23</td>
    <td>24</td>
  </tr>
  <tr>
    <td colspan="2">25</td>
  </tr>
</table>

		
		';



	// fpdf class
			############################
			

			$p = new PDF();
			
			// PAGE // Data
			$p->SetMargins(15,15,15);
			$p->AddPage();
			$p->defaultFontFamily = 'times';
			$p->setStyle('normal');
			#$p->defaultFontStyle  = '';
			$p->defaultFontSize   = 11;
			$p->SetFont($p->defaultFontFamily, $p->defaultFontStyle, $p->defaultFontSize);
			$p->htmltable($htmlTemplate);
			
			
			// PAGE 2 // Untuk page tambahan
			
			$p->AddPage();
			$p->defaultFontFamily = 'times';
			$p->defaultFontSize   = 11;
			$p->SetFont($p->defaultFontFamily, $p->defaultFontStyle, $p->defaultFontSize);
			$p->htmltable($pageTambahan);
			


			$filename = "MyFiles - ".rand(111,999)." .pdf"; // ubah namafile sesuai dengan keinginan
			// archive in server
			$p->output($rootpath.$filename,'F'); // tempat file pdf yg akan disimpan di server
			// end archives
			$pdfdoc = $p->Output("", "S"); // baca fpdf doc buat detailnya
	

	//Sendmail
	$to			= 'chandrawiraatmaja@gmail.com';
	$from		= "chandrawira@gmail.com";
	$subject	= "PDF ON THE AIR test attachment";
	$message	="Please see the attachment.<hr>";
	$separator	= md5(time());
	$eol		= PHP_EOL;
	$attachment = chunk_split(base64_encode($pdfdoc));
	$headers	= "From: ".$from.$eol;
	$headers	.= "MIME-Version: 1.0".$eol; 
	$headers	.= "Content-Type: multipart/mixed;boundary=\"".$separator."\"".$eol.$eol; 
	$headers	.= "Content-Transfer-Encoding: 7bit".$eol;
	$headers	.= "This is a MIME encoded message.".$eol.$eol;

	// message
	$headers	.= "--".$separator.$eol;
	$headers	.= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
	$headers	.= "Content-Transfer-Encoding: 8bit".$eol.$eol;
	$headers	.= $message.$eol.$eol;

	$headers	.= "--".$separator.$eol;
	$headers	.= "Content-Type: application/octet-stream;name=\"".$filename."\"".$eol; 
	$headers	.= "Content-Transfer-Encoding: base64".$eol;
	$headers	.= "Content-Disposition: attachment".$eol.$eol;
	$headers	.= $attachment.$eol.$eol;
	$headers	.= "--".$separator."--";

		//mail($to, $subject, "", $headers);
		//echo "<script>alert('Sukses mengirim email dan attachment');document.location.href=\"pdf.php\"</script>";
?>