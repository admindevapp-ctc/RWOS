<?php
	//$conn = new mysqli('order.denso.com', 'root', 'P@ssw0rD', 'ordering-sg');
	$conn = new mysqli('order.denso.com', 'root', '', 'ordering-sg');
	
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>