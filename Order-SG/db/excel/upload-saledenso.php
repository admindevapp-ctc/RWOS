<?php

session_start();
require_once('../../../core/ctc_init.php'); 

$comp = ctc_get_session_comp();
$cusno=	$_SESSION['cusno'];

include "../conn.inc";
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
        createTempTable('awsexc_temp');

        $error=0;
        // check value
        if($baris <=1 ){
            $msg='Column can not be empty';
            $error++;
        }
        // Start from 1st row
        for ($i=2; $i<=$baris; $i++){

            $message='';
            $msg='';
            $status='';
            $search=array("'","Ã");
            $replace=array("\\'","A");
            // Read data
            $partno= $data->val($i, 1)=="General"?$data->raw($i, 1):$data->val($i, 1);
            $cusgrp2= $data->val($i, 2);
            $condition= $data->val($i, 3);//=="General"?$data->val($i, 3):$data->raw($i, 3);
            $cuur= $data->val($i, 4);
            $price= $data->val($i, 5);
            $strcondition = strtolower(str_replace(' ', '', $condition));
            switch ($strcondition) {
                case "sell":
                  $sell =  "1";
                  break;
                case "1":
                  $sell =  "1";
                  break;
                case "notsell":
                  $sell =  "0";
                  break;
                default:
                  $sell =  "0";
              }
              
           //  echo  $sqlqi."<br/>";
           // echo $partno . ">" . $cusgrp2. ">" . $sell. ">" . $cuur. ">" . $price."<br/>";
            
                //Validate
                //3. Check blank column
                if(strlen($partno)==0 || strlen($cusgrp2)==0 || strlen($condition)==0 ){

                    //0. price allow only numaric
                    if(!is_numeric($price)){
                        // $price=0;
                        $message .= "Error :  Allow only Numaric<br/>";
                    }else{
						$length = strlen($price);
						$decimal_places = strpos($price, '.') !== false ? $length - strpos($price, '.') - 1 : 0;
						if(!($length <= 14 && $decimal_places <= 2)){
							$message .= "Error : Should not exceed 14 digit and 2 decimal place. <br/>";

						}
						
					}
                    if(strlen($partno)==0 ){

                        $message .= "Error :  Part Number should not be blank<br/>";
                    }
                    if(strlen($cusgrp2)==0 ){

                        $message .= "Error :  Must have group from this customer<br/>";
                    }
                    if(strlen($condition)==0 ){

                        $message .= "Error :  Allow only \"Sell\" or \"Notsell\". Other then this block<br/>";
                    }
                }
                else{
					
                    //0. price allow only numaric
                    if(!is_numeric($price) && $price != ''){
                        $message .= "Error :  Allow only Numaric<br/>";
                    }else{
						
						$length = strlen($price);
						$decimal_places = strpos($price, '.') !== false ? $length - strpos($price, '.') - 1 : 0;
						if(!($length <= 14 && $decimal_places <= 2)){
							$message .= "Error : Should not exceed 14 digit and 2 decimal place. <br/>";

						}
						
					}
                    //1.check sellprice 
                    if($comp != "IN0"){
                        $qs1="SELECT count(*) exsit FROM sellprice where trim(Itnbr) = '$partno' and Owner_Comp='$comp'";
                    }
                    else{
                        $qs1="SELECT count(*) exsit FROM bm008pr where trim(ITNBR) = '$partno' and Owner_Comp='$comp'";
                    }
                    $sqlqs1=mysqli_query($msqlcon,$qs1);
                    $row = mysqli_fetch_array($sqlqs1);
                    if($row["exsit"] == 0){
                        $message .= "Error : Part Number is not in Price master. Please contact Denso PIC";
                    }
                    else {
                        //2.check group on awscusmas
                        $qs1="SELECT  count(*) exsit  FROM awscusmas where  Owner_Comp='$comp' and cusno1 = '$cusno' and cusgrp='$cusgrp2'";
                        $sqlqs1=mysqli_query($msqlcon,$qs1);
                        $row = mysqli_fetch_array($sqlqs1);
                        if($row["exsit"] == 0){
                            $message .= "Error : This group name does not exist";
                        }
                    }
					
					if($comp == 'IN0'){
						if($price == ''){
							$message .= "Error : Price can not be blank.<br/>";
						}
						if($cuur == ''){
							$message .= "Error : Currency code can not be blank.<br/>";
						}
					}

                }

                // Check duplicate
                $qs1="SELECT Itnbr  FROM `awsexc_temp`  
                where  Owner_Comp='$comp' and cusno1 = '$cusno' and  Itnbr = '$partno' and cusgrp='$cusgrp2'
                ";
                $sqlqs1=mysqli_query($msqlcon,$qs1);
                $row = mysqli_fetch_array($sqlqs1);
                //echo $qs1 .strlen($row["Itnbr"]) ."<br/>";
                if(strlen($row["Itnbr"]) > 0){
                    $message .= "Error : Duplicate";
                }

                // Insert into awsexc_temp
                $qi="INSERT INTO awsexc_temp(Owner_Comp ,cusno1 ,itnbr ,sell ,cusgrp ,price ,curr,error ) 
                    VALUES ('$comp','$cusno','$partno','$sell','$cusgrp2','$price','$cuur','$message')";
                $sqlqi=mysqli_query($msqlcon,$qi) OR die(mysqli_error());
                if(strlen($message)!=0 ){
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
        //echo $msg .$status;
        echo "<SCRIPT type=text/javascript>document.location.href='../../aws_saledenso_import.php?msg=$msg&status=$status'</SCRIPT>";
    }
    else{
        echo "File to long";
    }
}



function createTempTable($tblname){
   
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
            itnbr VARCHAR(27) ,
            sell VARCHAR(1) ,
            cusgrp VARCHAR(3),
            price VARCHAR(500),
            curr VARCHAR(3),
            error VARCHAR(256))";
        mysqli_query($msqlcon,$query2);
        //echo "create >>" .$query2;
    }
    else{
        $query2 = "delete from " . $tblname . " where Owner_Comp='$comp' and cusno1 = '$cusno'";
        mysqli_query($msqlcon,$query2);
        //echo $query2;
    }
}
?>
