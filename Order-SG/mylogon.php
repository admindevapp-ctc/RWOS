<?php

session_start();
require_once('../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
  if($_SESSION['redir']=='Order-SG'){
	$redir=$_SESSION['redir'];
	$cusno=	$_SESSION['cusno'];
	$type=$_SESSION['type'];
	$com=$_SESSION['com'];
	$userName=$_SESSION['user'];
	$comp = ctc_get_session_comp();
   }else{
      echo "<script> document.location.href='../".redir."'; </script>";
      }
}else{	
	header("Location:../login.php");
}
	include('db/conn.inc');
		//echo 'type customer ='.$type;
		$query="select * from cusmas where cust3 = '$cusno' and Owner_Comp='$comp' ";
		//	echo $query;
		$result = mysqli_query($msqlcon,$query);
		$no= mysqli_num_rows($result);
    	//echo $query;
		if($no==0){
			if($type=='a'){
				$_SESSION['cusnm']=$_SESSION['user'];	
				echo "<script> document.location.href='admin/maincusadm.php'; </script>";
		   	}
			if($type=='m'){
				$_SESSION['cusnm']=$cusno;	
		 		echo "<script> document.location.href='mkt/mainRFQ.php'; </script>";
			}else if($type=='s'){
				$_SESSION['supno']=$cusno;	
		 		echo "<script> document.location.href='supplier/supmaincusadm.php'; </script>";
			}
			else{
				echo "wrong user name or password!";
			}
		}else{
			$rec=mysqli_fetch_array($result);
 			$cusnm=trim($rec['Cusnm']);
			$custype=$rec['Custype'];
			$alias=$rec['alias'];
			$group=$rec['CSGroup'];
			$dealer=$rec['xDealer'];
			//$oecus=$rec['OECus'];
			$tablename=$alias."ordtmp";
			//start comment PLE
			// change for AWS Jan 2023
			$awstable=$alias."awsordertmp";
			$_SESSION['cusnm']=$cusnm;	
	    	$_SESSION['alias']=$alias;
			$_SESSION['tablename']=$tablename;
	   	 	$_SESSION['custype']=$custype;
			$_SESSION['dealer']=$dealer;
			$_SESSION['group']=$group;
			$_SESSION['password']=$group;
			$_SESSION['awstable']=$awstable;
			//$_SESSION['oecus']=$oecus;
			//End comment PLE
			
		
		
			//create table baseon username
			//$sql= 'DESC table_name;';
			$sql= "DESC $tablename;";
	
    		mysqli_query($msqlcon,$sql);
    		if ($msqlcon->errno==1146){
    			$query2="CREATE TABLE ".$tablename." (
					Owner_Comp varchar(3),
					CUST3 varchar(45),
  					orderno varchar(20),
  					orderdate varchar(8),
  					cusno varchar(8),
  					partno varchar(27),
  					partdes varchar(30),
  					ordstatus varchar(1),
  					qty int(11),
					CurCD varchar(2),
  					bprice decimal(18,4),
					SGCurCD varchar(2),
					SGPrice decimal(18,8),
					disc int(3),
  					dlrdisc int(3) ,
  					slsprice decimal(18,4),
  					Corno varchar(20),
  					DueDate varchar(8),
  					DlvBy varchar(1),
					DlrCurCD varchar(2),
					DlrPrice decimal(18,4),
					OECus varchar(1),
					Shipment varchar(1),
  					PRIMARY KEY  (orderno, ordstatus, cusno, partno,Owner_Comp)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
				mysqli_query($msqlcon,$query2);
			}else{
				// Check column Owner_Comp
				$sql_column = "SHOW COLUMNS FROM $tablename LIKE 'Owner_Comp'";
				$query_column = mysqli_query($msqlcon,$sql_column);
				if($query_column->num_rows ==0){
					$sql_alter = "ALTER TABLE $tablename
					ADD COLUMN Owner_Comp  varchar(3) NOT NULL FIRST ,
					DROP PRIMARY KEY,
					ADD PRIMARY KEY (orderno, ordstatus, cusno, partno, Owner_Comp)";
					mysqli_query($msqlcon,$sql_alter);
				}

			}
		
			//start comment PLE
			// change for AWS Jan 2023
			$sql= "DESC $awstable;";
			//echo $sql;
    		mysqli_query($msqlcon,$sql);
    		if ($msqlcon->errno==1146){
    			$query2="CREATE TABLE ".$awstable." (
					Owner_Comp varchar(3),
					CUST3 varchar(45),
					orderno varchar(20),
					orderdate varchar(8),
					cusno varchar(8),
					partno varchar(27),
					partdes varchar(30),
					ordstatus varchar(10),
					qty int(11),
					CurCD varchar(2),
					bprice decimal(18,4),
					SGCurCD varchar(2),
					SGPrice decimal(18,8),
					disc int(3),
					dlrdisc int(3) ,
					slsprice decimal(18,4),
					Corno varchar(20),
					DueDate varchar(8),
					DlvBy varchar(1),
					DlrCurCD varchar(2),
					DlrPrice decimal(18,4),
					OECus varchar(1),
					Shipment varchar(1),
					PRIMARY KEY  (orderno, ordstatus, cusno, partno,Owner_Comp)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
				mysqli_query($msqlcon,$query2);
			}else{
				// Check column Owner_Comp
				$sql_column = "SHOW COLUMNS FROM $awstable LIKE 'Owner_Comp'";
				$query_column = mysqli_query($msqlcon,$sql_column);
				if($query_column->num_rows ==0){
					$sql_alter = "ALTER TABLE $awstable
					ADD COLUMN Owner_Comp  varchar(3) NOT NULL FIRST ,
					DROP PRIMARY KEY,
					ADD PRIMARY KEY (orderno, ordstatus, cusno, partno, Owner_Comp)";
					mysqli_query($msqlcon,$sql_alter);
				}

			}
		   // End Comment PLE

			$session_id= session_id();
			$_SESSION['sessionid']=$session_id;
			$query="update userid set sessid='$session_id' where trim(UserName) = '$userName' and Owner_Comp='$comp' " ;
		
			$result = mysqli_query($msqlcon,$query);
			//echo $query;
		
		}
		
		if($type!='a'&& $type!='m'){
			//echo "<script> document.location.href='main.php'; </script>";	
			// Start Comment PLE
			// Edit for AWS Dec 2022
			if($type=='w'){
				echo "<script> document.location.href='aws/main.php'; </script>";
		   }
		   else{
			   echo "<script> document.location.href='main.php'; </script>";	
		   }
		   // End Comment PLE
		}else{
			//$_SESSION['lng'] = 'en';
			if($type!='m'){
		 	echo "<script> document.location.href='admin/maincusadm.php'; </script>";
			}else if($type!='s'){
				echo "<script> document.location.href='supplier/supmaincusadm.php'; </script>";
			}else{
			echo "<script> document.location.href='mkt/mainrfq.php'; </script>";
			}
		
		}
	
	


die();
?>