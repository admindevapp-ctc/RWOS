<?php
require '../db/class.phpmailer.php';
require '../db/class.smtp.php';
$mail_to = isset($mail_to) ? $mail_to : '';
function test_send_s($comp = '',$numrow_inst = 0,$numrow_del=0){
    global $mail_to;
	$mail = new PHPMailer();
    $mail->IsHTML(true);
    $mail->IsSMTP();
    $mail->Host = "172.24.46.52"; // sets GMAIL as the SMTP server
    $mail->Port = 25; // set the SMTP port for the GMAIL server
    $mail->From = "no-reply@denso.com"; // "name@yourdomain.com";
    $mail->FromName = "Your Name";  // set from Name
	
	// $mail->SMTPAuth = true; // enable SMTP authentication
	// $mail->SMTPSecure = "ssl"; // sets the prefix to the servier
	// $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
	// $mail->Port = 465; // set the SMTP port for the GMAIL server
	// $mail->Username = "blackbetty4475@gmail.com"; // GMAIL username
	// $mail->Password = "fdhyxwuvcqoirvsp"; // GMAIL password
	

    // $mail->Subject = "Part copying Success.";
    $mail->Subject = "[RWOS AWS] [".$comp."] Copy MA has been completed successfully ".date("Ymd h:m");
    $mail->Body = "All parts has been copied.<br> Row Inserted : $numrow_inst Rows.<br> Row Deleted : $numrow_del Rows.";

    foreach($mail_to as $k => $v){
        $mail->AddAddress($v);
    }
    // $mail->AddAddress("Blackbetty4475@gmail.com", "Mr.Pasakorn Pokkao"); // to Address
    $mail->set('X-Priority', '1'); //Priority 1 = High, 3 = Normal, 5 = low
 
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
}

function test_send_f($comp ='',$numrow_inst = 0,$numrow_del=0){
    global $mail_to;
	$mail = new PHPMailer();
    $mail->IsHTML(true);
    $mail->IsSMTP();
    $mail->Host = "172.24.46.52"; // sets GMAIL as the SMTP server
    $mail->Port = 25; // set the SMTP port for the GMAIL server
    $mail->From = "no-reply@denso.com"; // "name@yourdomain.com";
    $mail->FromName = "Your Name";  // set from Name
    $mail->Subject = "[RWOS AWS] [".$comp."] Copy MA has failed ".date("Ymd h:m");
    $mail->Body = "All parts will not copy.";

    foreach($mail_to as $k => $v){
        $mail->AddAddress($v);
    }
    // $mail->AddAddress("Blackbetty4475@gmail.com", "Mr.Pasakorn Pokkao"); // to Address

    $mail->set('X-Priority', '1'); //Priority 1 = High, 3 = Normal, 5 = low
 
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
		echo "Message sent!";
	}
}
?>
