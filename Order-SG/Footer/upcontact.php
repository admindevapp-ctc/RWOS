<?php
$host = "order.denso.com";
$host = "order.denso.com";

$user = "root";
$pass = "P@ssw0rD";
$dbnm = "ordering-sg";

$conn = mysql_connect ($host, $user, $pass);
if ($conn) {
	$buka = mysqli_select_db ($dbnm);
	if (!$buka) {
		die ("Database tidak dapat dibuka");	
	}
} else {
	die ("Server MySQL tidak terhubung");	
}

$err='';

$email= $_POST['email'];
$name= $_POST['name'];
$address= $_POST['address'];
$title =$_POST['title'];
$phone= $_POST['phone'];
$subject =$_POST['subject'];
$memo=$_POST['memo'];

if($title==''){
	$err='1';
};

if($name==''){
	$err='1';
};

if($phone==''){
	$err='1';
};

if($subject==''){
	$err='1';
};

if($memo==''){
	$err='1';
};


if($err!=''){
	echo 'test';
}else{
	
$query = "INSERT INTO contactus VALUES('','$title','$name', '$address', '$phone', '$email', ' ', ' ', '$subject', '$memo')";
$sql = mysql_query ($query) ;
$issubject=$subject;
$ismemo="name  : ".$title . " " . $name . "\n" . "phone  : " . $phone . "\n" . "email : ". $email."\n" . "address : ". $address."\n\n" . $memo;
mail("tidanan@ctc-g.co.th", $issubject, $ismemo);

};
?>	