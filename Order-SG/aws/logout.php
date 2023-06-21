<? session_start();
if(isset($_SESSION['cusno']))
{
		unset($_SESSION['cusno']);
		unset($_SESSION['cusnm']);
		unset($_SESSION['user']);
		unset($_SESSION['alias']);
		unset($_SESSION['tablename']);
		unset($_SESSION['type']);
		unset($_SESSION['dealer']);
		unset($_SESSION['group']);
		unset($_SESSION['type']);
		unset($_SESSION['custype']);
		unset($_SESSION['password']);
		unset($_SESSION['redir']);
		unset($_SESSION['com']);


	session_destroy();
	?><script language="javascript">
	document.location="../../index.php";
	</script><?
	
}else{
	?><script language="javascript">
	alert("Sorry, You are not authorized to access this page!!");
	document.location="../../index.php";
	</script>
	<?
}
?>