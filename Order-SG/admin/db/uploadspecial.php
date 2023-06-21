<?php

session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

$comp = ctc_get_session_comp(); // add by CTC

include "../../db/conn.inc";
include "excel_reader2.php";
if(isset($_POST['submit'])){
    $radvalact=$_POST['group1'];
    $radvaltype=$_POST['group2'];
    $radvalrow=$_POST['group3'];
    $userfile=$_FILES['userfile']['name'];
    
    // Check file type csv or xls
    $ext = strtolower(end(explode('.', $userfile)));
   
    $msgval=''; $flagfwd=''; $msg='';
    
    if($userfile=='' || $radvaltype=='' || $radvalact=='' || $radvalrow==''){
        $msgval='Please fill all field';
        $flagfwd='2';
    }else{
        if($radvaltype=='csv' && $ext=='csv'){
            $flagpro='upcsv';
        }else if($radvaltype=='excel' && $ext=='xls'){
            $flagpro='upxls';
            $flagfwd='1';
            $msgsuccess='Success';
        }else{
            $flagpro='';
            $flagfwd='2';
            $msgval='Should match between file type upload with item selected';
        }
    }
	
	echo "<br>";
	echo "flag fwd =". $flagfwd; 
	echo "<br>";
	
	
    if($flagpro!=""){
		
	 	$qd="DELETE FROM specialpricetmp WHERE Owner_Comp='$comp'";
     	mysqli_query($msqlcon,$qd);	
	}
	
	
    /* **
     *  UPLOAD CSV File
     * **/
    if($flagpro=='upcsv'){
        $file = $_FILES['userfile']['tmp_name'];  
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            $flag=''; 
            $row = 1; $error=0; 
            while (($data = fgetcsv($handle, 1000, ',', '"')) !== FALSE) {
                $msg='';
                $status='';
                
                $search=array("'","Ã");
                $replace=array("\\'","A");
                // Read data
                $fcusno=trim($data[0]);
                $fitnbr=trim($data[1]);
                $fprice=trim($data[2]);
                $fcurcd=trim($data[3]);
                
                $num = count($data);   
                // Check 1st row as header and value radio button is yes
                if($radvalrow=='yesrow' && $row==1){
                    // Check count field
                    if($num==4){
                        // Check blank column
                        if(strlen($fcusno)!=0 && strlen($fitnbr)!=0 && strlen($fprice)!=0 && 
                            strlen($fcurcd)!=0 ){
                                // Check value 1st row
                                if(trim($fcusno)=='CUSNO' && trim($fitnbr)=='ITNBR' && 
                                    trim($fprice)=='PRICE' && trim($fcurcd)=='CURCD' 
                                   ){
                                        $fprice=0;
                                        $status='H';
                                        $msg='Header Row';
                                }else{
                                    $status='H';
                                    $msg='Header Row';
                                }
                        }else{
                            $status='E';
                            $msg='Header can not be empty';
                            $error++;
                        }
                    }else{
                        $status='E';
                        $msg='Header should have 4 fields';
                        $error++;
                    }
                }else{
                    // Check count field
                    if($num==4){
                        // Check blank column
                        if(strlen($fcusno)!=0 && strlen($fitnbr)!=0 && strlen($fprice)!=0 && 
                            strlen($fcurcd)!=0 ){
                            
                            // Check length
                            if(strlen($fcusno)<=8 && strlen($fitnbr)<=15 && strlen($fprice)<=15 && 
                                strlen($fcurcd)==2 ){
                                    $fcust2='';
									$fcust3='';
                                    // Check cusno in Cusmas table
                                    $sqs2="SELECT Cusno, CUST2, CUST3 FROM cusmas WHERE Cusno='$fcusno' AND Owner_Comp='$comp'";
                                    $sqlqs2=  mysqli_query($msqlcon,$sqs2);
                                    $arrqs2=  mysqli_fetch_array($sqlqs2);
                                    if($arrqs2){
                                        $fcust2=$arrqs2['CUST2'];
										$fcust3=$arrqs2['CUST3'];
                                        //Check itnbr in bm008pr table
                                        $sqs3="SELECT ITNBR FROM bm008pr WHERE ITNBR='$fitnbr' AND Owner_Comp='$comp'";
                                        $sqlqs3=  mysqli_query($msqlcon,$sqs3);
                                        $arrqs3=  mysqli_fetch_array($sqlqs3);
                                        if($arrqs3){
                                            
                                            // Check numeric type for price
                                            if(!is_numeric($fprice)){
                                                $status='E';
                                                $msg=$fprice.' - Price format should be numeric';
                                                $error++;
                                            }
                                        }else{
                                            $status='E';
                                            $msg='Could not find item number '.$fitnbr;
                                            $error++;
                                        }
                                    }else{
                                        $status='E';
                                        $msg='Could not find customer number '.$fcusno;
                                        $error++;
                                    }
                            }else{
                                $status='E';
                                $msg='Check the length';
                                $error++;
                            }
                        }else{
                            $status='E';
                            $msg='Column can not be empty';
                            $error++;
                        }
                    }else{
                        $status='E';
                        $msg='Should 4 fields';
                        $error++;
                    }
                }    
                
             
			 if($status!='H'){
                $qi="INSERT INTO specialpricetmp(Cusno, Itnbr, Price, CurCD, CUST2, CUST3,
                        StatusItem,Keterangan,Owner_Comp) 
                    VALUES ('$fcusno','$fitnbr','$fprice','$fcurcd','$fcust2','$fcust3',
                        '$status','$msg','$comp')
                    ON DUPLICATE KEY UPDATE Cusno='$fcusno',Itnbr='$fitnbr',
                        Price='$fprice',CurCD='$fcurcd',CUST2='$fcust2',CUST3='$fcust3',
                        StatusItem='$status',Keterangan='$msg',Owner_Comp='$comp'";
                mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
			 }
             $row++;
            }
        }
        
//		echo "<br>";
//	echo "error =". $error; 
//	echo "<br>";
//	echo "action =". $radvalact;
	
        
        // If there are error upload
        if($error>0){
            $errortable='Error';
            $flagfwd='3';
        }else{
            // If action Add 
            if($radvalact=='add'){
                $msgsuccess='Add';
				$flagfwd='1';
            }
            // If action Replace Partial
            if($radvalact=='edit'){
                $msgsuccess='Replace';
                $flagfwd='1';      
            
            }
            // If action Replace All    
            if($radvalact=='editall'){
                $msgsuccess="Confirm";
                $flagfwd='1';    
            }
        }
        
    }
	echo "<br>";
	echo "msg success =". $msgsuccess;
    /* **
     *  END UPLOAD CSV File 
     * **/
    
    /* ========================================
       UPLOAD XLS File
     ======================================== */
    if($flagpro=='upxls'){
        // Read excel file
        $data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
            
        // Read row and column
        $baris = $data->rowcount($sheet_index=0);
        
        $error=0;
        // Start from 1st row
        for ($i=1; $i<=$baris; $i++){
            $msg='';
            $status='';
            $search=array("'","Ã");
            $replace=array("\\'","A");
            // Read data
            $fcusno = trim($data->val($i, 1));
            $fitnbr = trim($data->val($i, 2));
            $fprice = trim($data->val($i, 3));  
            $fcurcd = trim($data->val($i, 4));
          
              
            // Check 1st row as header and value radio button is yes
            if($radvalrow=='yesrow' && $i==1){
                // Check blank column
                if(strlen($fcusno)!=0 && strlen($fitnbr)!=0 && strlen($fprice)!=0 && 
                    strlen($fcurcd)!=0 ){
                    
                        // Check value 1st row
                        if(trim($fcusno)=='CUSNO' && trim($fitnbr)=='ITNBR' && 
                            trim($fprice)=='PRICE' && trim($fcurcd)=='CURCD'  
                            ){
                                $fprice=0;
                                $status='H';
                                $msg='Header Row';
                        }else{
                            $status='H';
                            $msg='Header Row';
                        }
                }else{
                    $status='E';
                    $msg='Header can not be empty';
                    $error++;
                }
            }else{
                // Check blank column
                if(strlen($fcusno)!=0 && strlen($fitnbr)!=0 && strlen($fprice)!=0 && 
                    strlen($fcurcd)!=0 ){
                        
                        // Check length
                        if(strlen($fcusno)<=8 && strlen($fitnbr)<=15 && strlen($fprice)<=15 && 
                            strlen($fcurcd)==2 ){
                            
                                // Check cusno in Cusmas table
								$fcust2="";
								$fcust3="";
								
                                $sqs2="SELECT Cusno, CUST2, CUST3 FROM cusmas WHERE Cusno='$fcusno' AND Owner_Comp='$comp'";
                                $sqlqs2=  mysqli_query($msqlcon,$sqs2);
                                $arrqs2=  mysqli_fetch_array($sqlqs2);
                                if($arrqs2){
                                      $fcust2=$arrqs2['CUST2'];
									  $fcust3=$arrqs2['CUST3'];
                                    //Check itnbr in bm008pr table
                                    $sqs3="SELECT ITNBR FROM bm008pr WHERE ITNBR='$fitnbr' AND Owner_Comp='$comp'";
                                    $sqlqs3=  mysqli_query($msqlcon,$sqs3);
                                    $arrqs3=  mysqli_fetch_array($sqlqs3);
                                    if($arrqs3){
                                        
                                        // Check numeric type for price
                                        if(!is_numeric($fprice)){
                                            $status='E';
                                            $msg=$fitnbr.' - Price format should be numeric';
                                            $error++;
                                        }
                                    }else{
                                        $status='E';
                                        $msg='Could not find item number '.$fitnbr;
                                        $error++;
                                    }
                                }else{
                                    $status='E';
                                    $msg='Could not find customer number '.$fcusno;
                                    $error++;
                                }
                            }else{
                                $status='E';
                                $msg='Check the length';
                                $error++;
                            }
                }else{
                    $status='E';
                    $msg='Column can not be empty';
                    $error++;
                }
            }
              
            // Insert into specialpricetmp
            if($status!='H'){
			$qi="INSERT INTO specialpricetmp(Cusno, Itnbr, Price, CurCD, CUST2, CUST3,
                    StatusItem,Keterangan,Owner_Comp) 
                VALUES ('$fcusno','$fitnbr','$fprice','$fcurcd','$fcust2','$fcust3',
                    '$status','$msg','$comp')
                ON DUPLICATE KEY UPDATE Cusno='$fcusno',Itnbr='$fitnbr',
                    Price='$fprice',CurCD='$fcurcd',CUST2='$fcust2',CUST3='$fcust3',
                    StatusItem='$status',Keterangan='$msg',Owner_Comp='$comp'";
            mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
        	}  
		}
        // If there are error upload
	
	//echo "<br>";
	//echo "error =". $error; 
	//echo "<br>";
	//echo "action =". $radvalact;
	
        if($error>0){
            $errortable='Error';
            $flagfwd='3';
        }else{
            // If action Add 
            if($radvalact=='add'){
                $msgsuccess='Add';
                $flagfwd='1';
            }
            // If action Replace Partial
            if($radvalact=='edit'){
                $msgsuccess='Replace';
                $flagfwd='1';      
            
            }
            // If action Replace All    
            if($radvalact=='editall'){
                $msgsuccess="Confirm";
                $flagfwd='1';    
            }
        }
    }
    /* ========================================
       END UPLOAD XLS File
     ======================================== */
   echo  'flagwd :' .$flagfwd; 
    if($flagfwd=='1'){
        $msg=$msgsuccess;
    }
    if($flagfwd=='2'){
        $msg=$msgval;
    }
    if($flagfwd=='3'){
        $msg=$errortable;
    }
   // echo $msg;
   echo "<SCRIPT type=text/javascript>document.location.href='../imspecial.php?msg=$msg'</SCRIPT>";
}
?>
