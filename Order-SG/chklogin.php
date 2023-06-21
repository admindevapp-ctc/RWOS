<?php
require('db/conn.inc');
require_once('../language/Lang_Lib.php');
require_once('../core/ctc_init.php');

$user = $_SESSION['user'];
$comp = ctc_get_session_comp();
$query="select sessid from userid where trim(UserName) = '$user' and Owner_Comp='$comp' ";
$result = mysqli_query($msqlcon,$query);
$hasil = mysqli_fetch_array ($result);
if(trim($hasil['sessid'])!=trim(session_id())){
	
	/**echo 'another user connect!';
	echo '<br>';
	echo $hasil['sessid'];
	echo '<br>';
	echo session_id(); 
	echo '<br>';
	echo $_SESSION['sessionid'];**/
     ?><script language="javascript">
	alert('<?php echo get_lng($_SESSION["lng"], "G0013"); ?>'/*"Another user login using your account!!"*/);
	</script>
		
    <?
    	echo "<script> document.location.href='logout.php'; </script>";
	
}


?>