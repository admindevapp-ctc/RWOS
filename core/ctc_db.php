<?php


class ctcdb {

	public $db = null;
	
	function __construct() {
		$this->db_connect();
    }

	function db_connect(){

// Start comment
// change connect to local 23 Mar 2021

		$DB_HOST = 'order.denso.com';
		$DB_USER = 'root';
		$DB_PASS = 'P@ssw0rD';
		// $DB_NAME = 'ordering-sg';
		$DB_NAME = 'ordering-sg';


// $DB_HOST = 'order.denso.com';
// $DB_USER = 'root';
// $DB_PASS = 'P@ssw0rD';
// // $DB_NAME = 'ordering-sg';
// $DB_NAME = 'ordering-sg';
// End comment

		try{
			$dbh = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS, array(
				PDO::MYSQL_ATTR_LOCAL_INFILE => true,
			));
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbh->exec("set names utf8");
			$this->db = $dbh;
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}


	}

}

    
