<? session_start() ;
?>
<?
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
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}

include('chkloginajax.php');
	require('db/conn.inc');
	$action=trim($_GET['action']);
	$rfqno=trim($_GET['rfqno']);
	$shpno=trim($_GET['shpno']);
if (trim($_GET['rfqno']) == '') {
//	$error = 'Error : Order No  should be filled';
}
// tambahan tanggal 29 Mei

if ($action=='new') {
	$cyear=date('Y');
	$cmonth=date('m');
	$ym=$cyear.$cmonth;
	$query="select * from RFQNo where period='".$ym."' ";
	$sql=mysqli_query($msqlcon,$query);		
	if($hasil = mysqli_fetch_array ($sql)){
		$order =$hasil["RFQNo"]+1;
		$xorder=str_pad((int) $order,3,"0",STR_PAD_LEFT);
		$query="update RFQNo set RFQNo=". $order  . " where period='".$ym."' ";
	}else{
		$xorder='001';
		$query="insert into RFQNo(`period`,`RFQNo`) values('$ym',1)";
	
	}
	mysqli_query($msqlcon,$query);
	//echo $query;
	$rfqno='RFQ'.$xorder."/".$cmonth."/".$cyear;
	//echo $rfqno;
	
}




if ($error) {
	echo $error;
} else {
	
	

	require 'db/class.phpmailer.php';
	require 'db/class.smtp.php';
	include "crypt.php";

	if(isset($_SESSION['sip'])){      
		$ym=date('Ym');
		$cymd=date('Ymd');
		
		if($action!='close'){
			if($action!='edit'){
				$query="INSERT INTO rfqhdr (STS,CUST3, RFQDT, RFQYM, RFQNO, CUSNO) values('P', '$cusno','$cymd', '$ym', '$rfqno', '$shpno' )";
				mysqli_query($msqlcon,$query) or die("can't run script");
			}
		    $query="delete from rfqdtl where RFQNO='".$rfqno ."' ";
			mysqli_query($msqlcon,$query) or die("can't run script");
			$jml=count($_SESSION['sip']);	
			for($i = 0; $i < $jml; $i++) {
				$sts= $_SESSION['sip'][$i]['sts']  ;
				$partno= $_SESSION['sip'][$i]['prtno']  ;
				$partdes= $_SESSION['sip'][$i]['desc'];
				$rpldt= $_SESSION['sip'][$i]['rpldt'];
				$diasrmk= $_SESSION['sip'][$i]['diasrmk'];
				$diasans= $_SESSION['sip'][$i]['diasans'];
				if(trim($shpno)==trim($cusno)){
					$vshpno='';
				}else{
					$vshpno=$shpno;
				}
				$query="INSERT INTO rfqdtl (STS,CUST3, RFQDT, RFQYM, RFQNO, PRTNO, ITDSC, RPLDT, DIASRMK, DIASANS, CUSNO) values('$sts', '$cusno', '$cymd', '$ym', '$rfqno', '$partno', '$partdes', '$rpldt', '$diasrmk', '$diasans', '$vshpno' )";
				mysqli_query($msqlcon,$query);
				//echo $query;
				}
			
		
		}
			unset($_SESSION['sip']);
			if($action=='new'){
				$inetmail ='Administrator@denso.com.sg';
    			$notesmail='Administrator Web';
				$desmailApp='srinindito@denso.co.id';
				//$desmailApp='weborder.denso.com.sg';
				$getparm=paramEncrypt("action=open&rfqno=$rfqno&cust3=$cusno");
				$custi3=$cusno;
				sendmail($rfqno, $desmailApp, $inetmail, $notesmail, $getparm , $custi3);
				//echo $rfqno. $desmailApp. $inetmail. $notesmail. $getparmm . $custi3;
			}
	}

	//echo $query;
echo "<script> document.location.href='mainRFQ.php'; </script>"; 

	
}

/** FUNCTION SEND MAIL **/
function sendmail($rfqno, $des, $inetmail, $notesmail, $getparm, $custi3){
    $mail = new PHPMailer(true); //New instance, with exceptions enabled

 
    $mail->IsSMTP();                           // tell the class to use SMTP
    $mail->SMTPAuth   = false;                  // enable SMTP authentication
    $mail->Port       = 25;                    // set the SMTP server port
    //$mail->Host       = "172.31.1.240"; // SMTP server
	$mail->Host       = "10.72.220.5";  	 // SMTP server
    $mail->Username   = "srinindito@denco.co.id";     // SMTP server username
    //$mail->Password   = "password";            // SMTP server password

    //$mail->IsSendmail();  // tell the class to use Sendmail

    $mail->AddReplyTo($inetmail,"First Last");

    $mail->From       = $inetmail;
    $mail->FromName   = $notesmail;

    $to = $des;

    $mail->AddAddress($to);
	$mail->Subject  = "New Request of Quatation : " . $rfqno  . " from :". $custi3;
	

    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    $mail->WordWrap   = 80; // set word wrap

    $body = "";
    $body= $body . "<font face=Century Gothic size=-1>Please follow the link below :<br>";  
    $body= $body . "<a href='http://".$_SERVER['HTTP_HOST']."/dologin.php?".$getparm. "'>"."Click to open the Document"."</a></font>";

    $mail->MsgHTML($body);

    $mail->IsHTML(true); // send as HTML

    $mail->Send();
    if(!$mail){
        echo 'Mailer error: ' . $mail->ErrorInfo;
    }else{
        echo 'Message has been sent.';
    }	
}
?>
