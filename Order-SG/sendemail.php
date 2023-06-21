<?php 

require_once('./../core/ctc_init.php'); // add by CTC

require 'db/class.phpmailer.php';
require 'db/class.smtp.php';
require 'config/Web_Lib.php';
function sendmail($to, $to1,  $title, $content, $from){
	$mail = new PHPMailer(); //New instance, with exceptions enabled


	$mail->IsSMTP();                           // tell the class to use SMTP

	$mail->Host       = "172.24.46.52";  	 // SMTP server
	$mail->Username   = "srinindito@denco.co.id";     // SMTP server username
	$mail->Port       = 25;                    // set the SMTP server port
	//$mail->Host       = "172.31.1.240"; // SMTP server

	// TOD0: Remove on Production
	// Test 
	// $mail->SMTPAuth = true; // enable SMTP authentication
	// $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
	// $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
	// $mail->Port = 465; // set the SMTP port for the GMAIL server
	// $mail->Username = "blackbetty4475@gmail.com"; // GMAIL username
	// $mail->Password = "fdhyxwuvcqoirvsp"; // GMAIL password


	$mail->From       = $from;
	$mail->FromName   = 'Administrator Web';

	$mail->AddAddress($to);
	$mail->AddAddress($to1);

	$mail->Subject  =  $title;

	$mail->WordWrap   = 80; // set word wrap

	$body =  $content;

	$mail->MsgHTML($body);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();

}

function sendmailAttachFile($to ,  $orderno , $corno , $cusno , $cusnm , $bcc){//CTC P.Pawan 04/03/19 email add attach file
	$mail = new PHPMailer();
	$mail->IsHTML(true);
	$mail->IsSMTP(true);
	$mail->CharSet = "utf-8";
	// $mail->SMTPAuth = true; // enable SMTP authentication
	// $mail->SMTPSecure = "tls"; // sets the prefix to the servier
	// $mail->Host = "outlook.office365.com"; // sets GMAIL as the SMTP server
	// $mail->Port = 587; // set the SMTP port for the GMAIL server
	// $mail->Username = ""; // GMAIL username
	// $mail->Password = ""; // GMAIL password

	$mail->Host       = "172.24.46.52";  	 // SMTP server
	$mail->Username   = "srinindito@denco.co.id";     // SMTP server username
	$mail->Port       = 25;                    // set the SMTP server port

	// TOD0: Remove on Production
	// Test
	// $mail->SMTPAuth = true; // enable SMTP authentication
	// $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
	// $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
	// $mail->Port = 465; // set the SMTP port for the GMAIL server
	// $mail->Username = "blackbetty4475@gmail.com"; // GMAIL username
	// $mail->Password = "fdhyxwuvcqoirvsp"; // GMAIL password

	$from = get_config("from");
	$mail->From = $from; // get value email sent from
	$mail->FromName   = 'Administrator Web';
	// $mail->AddReplyTo = "Pawan@ctc-g.co.th"; // Reply

	$path = get_config("path_PDF");
	$email_template = get_config("emailTemplate");
	$po_tempalte = get_config("POTemplate");
	require_once($po_tempalte);

	if(!isset($bcc)){
		$subject = "**DENSO Order Mail Sent Error ".$corno;
		$content .="Dear Administrator<br>";
		$content .="System couldn't send email to ".$cusnm." ( ".$cusno." ) automatically due to no email address found in Ship to MA.<br>";
		$content .="However, Customer can resent email with attahced PDF from History menu for this PO.<br><br>";
		$content .="This Order Acknowledgment is system generated. ";
		$content .="Please do not reply directly to this email account.<br><br>";
		$content .="With Best Regards<br>";
		$content .="DENSO Sales() Co., Ltd.<br>";
		$content .="Customer Service & Operation";

		$mail->Body = $content;
	}else{
		$subject = "**DENSO Order Confirmation ".$corno;
		// edit by CTC
		// if(ctc_get_session_comp() == 'TK0'){
		// 	$content = "เรียน ".$cusnm." ( ".$cusno." )<br>";
		// 	$content .="บริษัท เด็นโซ่ เซลล์(ประเทศไทย) จำกัด ได้รับคำสั่งซื้อสินค้า หมายเลข ".$corno." เรียบร้อยแล้ว<br>";
		// 	$content .="หากท่านต้องการยกเลิกคำสั่งซื้อ กรุณาติดต่อเจ้าหน้าที่เด็นโซ่(ฝ่ายขาย)<br><br>";
		// 	$content .="ขอแสดงความนับถือ<br>";
		// 	$content .="บ.เด็นโซ่ เซลล์(ประเทศไทย) จำกัด<br><br>";
		// 	$content .="อีเมล์ฉบับนี้เป็นการแจ้งข้อมูลจากระบบอัตโนมัติ, กรุณาอย่าตอบกลับ หากท่านมีข้อสงสัย<br>";
		// 	$content .="หรือต้องการสอบถามรายละเอียดเพิ่มเติม กรุณาติดต่อ ตามเบอร์โทรศัพท์ 02-315-9500 ต่อ 6315, 6327<br><br>";
		// }

		// $content .="Dear ".$cusnm." ( ".$cusno." )<br>";
		// $content .="Thank you for placing your order ".$corno." to DENSO Sales() Co., Ltd.<br>";
		// $content .="Your order will be processed accordingly and we will inform you of the progress in due course.<br><br>";
		// $content .="This Order Acknowledgment is system generated. ";
		// $content .="Please do not reply directly to this email account.<br><br>";
		// $content .="With Best Regards<br>";
		// $content .="DENSO Sales() Co., Ltd.<br>";
		// $content .="Customer Service & Operation";


		$message = file_get_contents($email_template); 
		$message = str_replace('%cusno%', $cusno, $message); 
		$message = str_replace('%cusnm%', $cusnm, $message); 
		$message = str_replace('%corno%', $corno, $message); 
		
		$mail->MsgHTML($message);

	}
	$mail->Subject = $subject;

	// $_body = $content;
	// $mail->Body = $_body;


	// $mail->AddAddress("Pawan@ctc-g.co.th"); // to Address
	$file = '';
	$cc = get_config("cc");
	$cc = explode(";",$cc);
	if(isset($to) && !empty($to) && $to != ""){
		$file = generatePDF($orderno,$cusno,$path);
		// $file = "";
		if(empty($file) || $file===""){
			$subject = "**DENSO Order Mail Sent Error ".$corno;
			$content ="Dear Administrator<br>";
			$content .="System couldn't send email to ".$cusnm." ( ".$cusno." ) automatically because no attached PDF found.<br>";
			$content .="However, Customer can resent email with attahced PDF from History menu for this PO.<br><br>";
			$content .="This Order Acknowledgment is system generated. ";
			$content .="Please do not reply directly to this email account.<br><br>";
			$content .="With Best Regards<br>";
			$content .="DENSO Sales() Co., Ltd.<br>";
			$content .="Customer Service & Operation";
			sendEmail($to,$subject,$content,$from);
			echo '{"result":"failPDF"}';
			return false;
		}
		foreach ($to as $email) {
			$mail->AddAddress($email);
		}
		if(!empty($cc)){
			foreach ($cc as $ccEmail) {
				$mail->AddCC($ccEmail);
			}
		}
		if(isset($bcc) && !empty($bcc)){
			foreach ($bcc as $bccEmail) {
				$mail->AddBCC($bccEmail);
			}
		}
		$mail->AddAttachment($path.$file);
	}
	if (!$mail->send()) {
		if(isset($bcc)){
			sendmailAttachFile($bcc ,  $orderno , $corno , $cusno , $cusnm,null);
		}
    	echo '{"result":"fail"}';
	} else {
		if(isset($bcc)){
			$path_Backup = get_config("path_PDF_Backup");
			if (!file_exists($path_Backup)) {
				mkdir($path_Backup, 0777, true);
			}
			rename($path.$file, $path_Backup.$file);
	    	echo '{"result":"success"}';
		}
	}
}

function sendEmail($to , $subject , $content , $from){
	$mail = new PHPMailer();
	$mail->IsHTML(true);
	$mail->IsSMTP(true);
	$mail->CharSet = "utf-8";
	// $mail->SMTPAuth = true; // enable SMTP authentication
	// $mail->SMTPSecure = "tls"; // sets the prefix to the servier
	// $mail->Host = "outlook.office365.com"; // sets GMAIL as the SMTP server
	// $mail->Port = 587; // set the SMTP port for the GMAIL server
	// $mail->Username = ""; // GMAIL username
	// $mail->Password = ""; // GMAIL password

	$mail->Host       = "172.24.46.52";  	 // SMTP server
	$mail->Username   = "srinindito@denco.co.id";     // SMTP server username
	$mail->Port       = 25;                    // set the SMTP server port


	// TOD0: Remove on Production
	// Test 
	// $mail->SMTPAuth = true; // enable SMTP authentication
	// $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
	// $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
	// $mail->Port = 465; // set the SMTP port for the GMAIL server
	// $mail->Username = "blackbetty4475@gmail.com"; // GMAIL username
	// $mail->Password = "fdhyxwuvcqoirvsp"; // GMAIL password

	$from = get_config("from");
	$mail->From = $from; // get value email sent from
	$mail->FromName   = 'Administrator Web';
	// $mail->AddReplyTo = "Pawan@ctc-g.co.th"; // Reply

	$mail->Subject = $subject;

	$_body = $content;
	$mail->Body = $_body;
	foreach ($to as $email) {
		$mail->AddAddress($email);
	}
	$cc = get_config("cc");
	$cc = explode(";",$cc);
	if(!empty($cc)){
		foreach ($cc as $ccEmail) {
			$mail->AddCC($ccEmail);
		}
	}
	$mail->send();
}

if(isset($_POST['axEmail']) && isset($_POST['orderNo']) && isset($_POST['corno']) && isset($_POST['cusno']) && isset($_POST['cusnm'])){//CTC P.Pawan 04/03/19 ajax email add attach file
	// require('generatePDF.php');  // CTC move to function sendmailAttachFile 
	$to = $_POST['axEmail'];
	$orderno = $_POST['orderNo'];
	$corno = $_POST['corno'];
	$cusno = $_POST['cusno'];
	$cusnm = $_POST['cusnm'];
	$bcc =  $_POST['bcc'];
	sendmailAttachFile($to ,  $orderno , $corno , $cusno , $cusnm ,$bcc);
}
?>
