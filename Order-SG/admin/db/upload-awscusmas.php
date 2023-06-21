<?php

session_start();
require_once('../../../core/ctc_init.php'); 

$comp = ctc_get_session_comp();

include "../../db/conn.inc";
include "excel_reader2.php";
if(isset($_POST['submit'])){
    $userfile=$_FILES['userfile']['name'];
       
    // Check file type csv or xls
    $ext = strtolower(end(explode('.', $userfile)));
   
    $msgval=''; $flagfwd=''; $msg='';
    
    if (($ext == "xls") && ($_FILES["userfile"]["size"] < 2000000)) {
        $count=0;
		$data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name'], true);
		
        // Read row and column
        $baris = $data->rowcount($sheet_index=0);
       // echo  $baris;


        //check temp table
        createTempTableawscusmas('awscusmas_temp');

        $error=0;
        // check value
        if($baris <=1 ){
            $msg='Column can not be empty';
            $error++;
        }
        // Start from 1st row
        for ($i=2; $i<=$baris; $i++){
            $msg='';
            $status='';
            $search=array("'","Ã");
            $replace=array("\\'","A");
            // Read data
            $cuno1= $data->raw($i, 1)=="General"?$data->val($i, 1):$data->raw($i, 1);
            $shipto1= $data->val($i, 2)=="General"?$data->raw($i, 2):$data->val($i, 2);
            $cusno2= $data->raw($i, 3)=="General"?$data->val($i, 3):$data->raw($i, 3);
            $shipno2= $data->val($i, 4)=="General"?$data->raw($i, 4):$data->val($i, 4);
            $shipaddr1= $data->val($i, 5)=="General"?$data->raw($i, 5):$data->val($i, 5);
            $shipaddr2= $data->val($i, 6)=="General"?$data->raw($i, 6):$data->val($i, 6);
            $shipaddr3= $data->val($i, 7)=="General"?$data->raw($i, 7):$data->val($i, 7);
            $email1= $data->val($i, 8);
            $email2= $data->val($i, 9);
            $email3= $data->val($i, 10);

              
           //  echo  $sqlqi."<br/>";
            // echo $cuno1 . ">" . $shipto1. ">" . $cusno2. ">" . $shipno2. ">" . $shipaddr1. ">" . $shipaddr2. ">" . $shipaddr3. ">" . $email1. ">" . $email2. ">" . $email3."<br/>";
             // Check blank column
            // if(strlen($cuno1)!=0 && strlen($shipto1)!=0 && strlen($cusno2)!=0 && 
                // strlen($shipno2)!=0 && strlen($shipaddr1)!=0 && strlen($email1)!=0 ){
					
					
					
                    // Insert into awscusmas
                    // $qi="INSERT INTO awscusmas_temp(  Owner_Comp ,cusno1 ,ship_to_cd1 ,cusno2 ,ship_to_cd2 ,
                    // ship_to_adrs1 ,ship_to_adrs2,ship_to_adrs3 , mail_add1 ,mail_add2 ,mail_add3 ,error ) 
                        // VALUES ('$comp','$cuno1','$shipto1','$cusno2',
                            // '$shipno2','$shipaddr1','$shipaddr2','$shipaddr3',
                            // '$email1','$email2','$email3', '$msg')";
                   // // echo  $qi;
                    // $sqlqi=mysqli_query($msqlcon,$qi) OR die(mysqli_error());


					
                    // Check duplicate 
                   // $qs1="SELECT cusno1 ,ship_to_cd1,cusno2,ship_to_cd2
                   // FROM awscusmas 
                   // WHERE cusno1='$cuno1' and ship_to_cd1='$shipto1' and cusno2='$cusno2'
                   // and ship_to_cd2='$shipno2' ";
                    //echo  $qs1."<br/>";
            // }
            // else{
				
                    if(strlen($cuno1)==0){
                        $msg.=" Column 1 st customer can not be empty <br/>";
						$error++;
                    }
                    if(strlen($shipto1)==0){
                        $msg.="Column Shipto 1st can not be empty <br/>";
						$error++;
                    }
                    if(strlen($cusno2)==0){
                        $msg.="Column 2 nd customer can not be empty <br/>";
						$error++;
                    }
                    if(strlen($shipno2)==0){
                        $msg.="Column Shipto 2nd  can not be empty <br/>";
						$error++;
                    }
                    if(strlen($shipaddr1)==0){
                        $msg.="Column Ship to address1 can not be empty <br/>";
						$error++;
                    }
                    if(strlen($email1)==0){
                        $msg.="Column e-mail1 can not be empty <br/>";
						$error++;
                    }
					//check email format
					// if(strlen($email1)> 0){
						// if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email1)) {
						  // $msg.='e-mail1 format is not correct <br/>';
                          // $error++;
						// } 
					// }
					// if(strlen($email2)> 0){
						// if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email2)) {
						  // $msg.='e-mail2 format is not correct <br/>';
                          // $error++;
						// } 
					// }
					// if(strlen($email3)> 0){
						// if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email3)) {
						  // $msg.='e-mail3 format is not correct <br/>';
                          // $error++;
						// } 
					// }
					//check email format
					// $pattern = "/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

					if(strlen($email1)> 0){
						if (!preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email1)) {
						  $msg.='Email1 format is not correct <br/>';
                          $error++;
						} 
					}
					if( strlen($email2)> 0){
						if (!preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email2)) {
						  $msg.='Email2 format is not correct <br/>';
                          $error++;
						} 
					}
					if( strlen($email3)> 0){
						if (!preg_match("/^[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email3)) {
						  $msg.='Email3 format is not correct <br/>';
                          $error++;
						} 
					}
					
					//check cusmas1
					 $qs1="SELECT * FROM `cusmas`  
					WHERE `xDealer`='". $cuno1."' and Owner_Comp='".$comp."' and Custype='A'";
				
					$sqlqs1=mysqli_query($msqlcon,$qs1);
					if(mysqli_num_rows($sqlqs1) == 0){
						$msg.='This 1st Customer Code do not found <br/>';
						$error++;
					}else{
						//check shiptoma
						$qs2="SELECT * FROM `shiptoma`  
						WHERE cusno='". $cuno1."' and Owner_Comp='".$comp."' AND ship_to_cd = '".$shipto1."'";
						$sqlqs2=mysqli_query($msqlcon,$qs2);
						if(mysqli_num_rows($sqlqs2) == 0){
							  $msg.='This 1st Customer Code do not have ship to code <br/>';
							  $error++;
						}
					}

					
					
					//check cusmas2
					$qs3="SELECT * FROM `cusmas`  
					WHERE cusno='". $cusno2."' and Owner_Comp='".$comp."' and Custype='A'";
					$sqlqs3=mysqli_query($msqlcon,$qs3);
					if(mysqli_num_rows($sqlqs3) == 0){
						$msg.='This 2nd Customer Code do not found <br/>';
						$error++;
					}else{
						$qs3="SELECT * FROM `cusmas`  
						WHERE cusno='". $cusno2."' and Owner_Comp='".$comp."' and Custype='A' and `xDealer`='". $cuno1."'";
						$sqlqs3=mysqli_query($msqlcon,$qs3);
						
						if(mysqli_num_rows($sqlqs3) == 0){
						$msg.='This 2nd Customer Code do not belong to this 1st customer <br/>';
						$error++;
						}
					}
					
                    // Insert into awscusmas
                    $qi="INSERT INTO awscusmas_temp(  Owner_Comp ,cusno1 ,ship_to_cd1 ,cusno2 ,ship_to_cd2 ,
                    ship_to_adrs1 ,ship_to_adrs2,ship_to_adrs3 , mail_add1 ,mail_add2 ,mail_add3,error ) 
                        VALUES ('$comp','$cuno1','$shipto1','$cusno2',
                            '$shipno2','$shipaddr1','$shipaddr2','$shipaddr3',
                            '$email1','$email2','$email3', '$msg')";
                    $sqlqi=mysqli_query($msqlcon,$qi) OR die(mysqli_error());

            // }

        }
        // check duplicate data
        $qs1="SELECT count(*) as 'check' FROM `awscusmas_temp`  
        group by `Owner_Comp`,`cusno1`,`ship_to_cd1`,`cusno2`,`ship_to_cd2`  
        order by 1 desc limit 1";

        $sqlqs1=mysqli_query($msqlcon,$qs1);
        while($hasil = mysqli_fetch_array ($sqlqs1)){
            $check=$hasil['check'];
            if($check >= 2){
                $msg='Duplicate records , please check your xls file';
                $error++;

            }
        }

        if($error>0){
            $errortable= $msg;
            $status='E';
            $flagfwd='3';
        }else{

            $msgsuccess="Confirm";
            $status='C';
            $flagfwd='1';    
        }
        if($flagfwd=='1'){
            $msg=$msgsuccess;
        }
        if($flagfwd=='3'){
            $msg=$errortable;
        }
      //  echo "../aws_import.php?msg=$msg&status=$status";
       echo "<SCRIPT type=text/javascript>document.location.href='../aws_import.php?msg=$msg&status=$status'</SCRIPT>";
    }
    else{
        echo "File to long";
    }
}



function createTempTableawscusmas($tblname){
   
    session_start();
    require_once('../../../core/ctc_init.php'); 
    include('../../../language/conn.inc');
    $comp = ctc_get_session_comp();
    $sql = "DESC " . $tblname;
    //echo $tblname;
    mysqli_query($msqlcon,$sql);
    //echo $sql ."<br/>";
    if ($msqlcon->errno == 1146) {
        $query2 = "CREATE TABLE " . $tblname . " (
            Owner_Comp VARCHAR(3),
            cusno1 VARCHAR(8),
            ship_to_cd1 VARCHAR(8) ,
            cusno2 VARCHAR(8) ,
            ship_to_cd2 VARCHAR(50),
            cusgrp VARCHAR(10),
            ship_to_adrs1 VARCHAR(100),
            ship_to_adrs2 VARCHAR(100),
            ship_to_adrs3 VARCHAR(50),
            mail_add1 VARCHAR(256),
            mail_add2 VARCHAR(256),
            mail_add3 VARCHAR(256),
            error VARCHAR(256))";
        mysqli_query($msqlcon,$query2);
        //echo "create >>" .$query2;
    }
    else{
        $query2 = "delete from " . $tblname . " where Owner_Comp='$comp' ";
        mysqli_query($msqlcon,$query2);
        //echo $query2;
    }
}
?>