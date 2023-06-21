<?php

session_start();
require_once('./../../../core/ctc_init.php'); // add by CTC

include "../../db/conn.inc";
include "excel_reader2.php";

$comp = ctc_get_session_comp(); // add by CTC

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
    
    /* **
     *  UPLOAD CSV File
     * **/
    if($flagpro=='upcsv'){
        $file = $_FILES['userfile']['tmp_name'];  
        
        if (($handle = fopen($file, "r")) !== FALSE) {
            $flag=''; 
            $row = 1; $error=0; 
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $msg='';
                $status='';
                
                $search=array("'","Ã");
                $replace=array("\\'","A");
                // Read data
                $fcusno=trim($data[0]);
                $fitnbr=trim($data[1]);
                $fprice=trim($data[2]);
                $fcurcd=trim($data[3]);
				$fdprice=trim($data[4]);
                $fdcurcd=trim($data[5]);
				$fdlrcd=trim($data[6]);
                
                $num = count($data);   
                // Check 1st row as header and value radio button is yes
                if($radvalrow=='yesrow' && $row==1){
                    // Check count field
                    if($num==7){
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
                        $msg='Header should have 7 fields';
                        $error++;
                    }
                }else{
                    // Check count field
                    if($num==7){
                        // Check blank column
                        if(strlen($fcusno)!=0 && strlen($fitnbr)!=0 && strlen($fprice)!=0 && 
                            strlen($fcurcd)!=0 ){
                            
                            // Check length
                            if(strlen($fcusno)<=8 && strlen($fitnbr)<=15 && strlen($fprice)<=15 && 
                                strlen($fcurcd)==2 ){
                                    $fcust2='';
									$fcust3='';
                                    // Check cusno in Cusmas table
                                    $sqs2="SELECT Cusno, CUST2, CUST3 FROM cusmas WHERE Cusno='$fcusno' AND Owner_Comp='$comp' "; // edit by CTC
                                    $sqlqs2=  mysqli_query($msqlcon,$sqs2);
                                    $arrqs2=  mysqli_fetch_array($sqlqs2);
                                    if($arrqs2){
                                        $fcust2=$arrqs2['CUST2'];
										$fcust3=$arrqs2['CUST3'];
                                        //Check itnbr in bm008pr table
                                        $sqs3="SELECT ITNBR FROM bm008pr WHERE ITNBR='$fitnbr' AND Owner_Comp='$comp' "; // edit by CTC
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
                                        $msg='Could not find customer number '.$fitnbr;
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
                        $msg='Should 7 fields';
                        $error++;
                    }
                }    
                
                //echo $row.' '.$radvalrow.' '.$fitnbr.' '.$fassycd.' '.$fitdsc.' '.$fitcls.' '.$fplann.' '.$fproduct.' '.$fsubproduct.' '.$flotsize.' '.$fitcat.' '.$fittyp.' '.$msg.'<br>';
                // Insert into sellpricetmp
               if($status!='H'){
			   $qi="INSERT INTO specialpriceawstmp(Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD,
                        StatusItem,Keterangan,Owner_Comp) 
                    VALUES ('$fcusno','$fitnbr',$fprice,'$fcurcd',$fdprice,'$fdcurcd','fdlrcd',
                        '$status','$msg','$comp')
                    ON DUPLICATE KEY UPDATE Cusno='$fcusno',Itnbr='$fitnbr',
                        PriceAws=$fprice,CurCDAWS='$fcurcd',Price=$fdprice,CurCD='$fdcurcd', DlrCD='$fdlrcd', StatusItem='$status',Keterangan='$msg',Owner_Comp='$comp'"; // edit by CTC
					  mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
			   }
                $row++;
            }
        }
        
        // Check duplicate ITNBR AND CUSNO in sellpricetmp table
        $qs2="SELECT Cusno,Itnbr FROM specialpriceawstmp WHERE StatusItem != 'H' AND Owner_Comp='$comp' "; // edit by CTC
        $sqlqs2=mysqli_query($msqlcon,$qs2);
        while($arr2=mysqli_fetch_array($sqlqs2)){
            set_time_limit(0);
            $cusnotmp=$arr2['Cusno'];
            $itnbrtmp=$arr2['Itnbr'];
            
            // Get cusno and itnbr from sellprice table
            $qs3="SELECT Cusno,Itnbr FROM specialpriceaws WHERE Cusno='$cusnotmp' AND Itnbr='$itnbrtmp' AND Owner_Comp='$comp' "; // edit by CTC
            $sqlqs3=mysqli_query($msqlcon,$qs3);
            $arr3=mysqli_fetch_array($sqlqs3);

            if(!$arr3){
                if($radvalact=='add'){
                    $flag='addx';
                }else if($radvalact=='edit'){
                    $status='E';
                    $msg='Failed to edit because could not found Item Number: '.$itnbrtmp.' & Customer No.'.$cusnotmp;
                    $qu1="UPDATE specialpriceawstmp SET StatusItem='$status', Keterangan='$msg' WHERE Cusno='$cusnotmp' AND Itnbr='$itnbrtmp' AND Owner_Comp='$comp' "; // edit by CTC
                    mysqli_query($msqlcon,$qu1);
                    $error++;
                }else{
                    $flag='editallx';
                }
            }else{
                if($radvalact=='edit'){
                    $flag='editx';
                }else if($radvalact=='add'){
                    $status='E';
                    $msg='Failed to add because duplicate  Item Number: '.$itnbrtmp.' & Customer No. '.$cusnotmp;
                    $qu2="UPDATE specialpriceawstmp SET StatusItem='$status', Keterangan='$msg' WHERE Cusno='$cusnotmp' AND Itnbr='$itnbrtmp' AND Owner_Comp='$comp' "; // edit by CTC
                    mysqli_query($msqlcon,$qu2);
                    $error++;
                }else{
                     $flag='editallx';
                }
            }
        }
        
        // If there are error upload
        if($error>0){
            $errortable='Error';
            $flagfwd='3';
        }else{
            // If action Add 
            if($flag=='addx'){
                $msgsuccess='Add';
                $flagfwd='1';
            }
            // If action Replace Partial
            if($flag=='editx'){
                $msgsuccess='Replace';
                $flagfwd='1';      
            
            }
            // If action Replace All    
            if($flag=='editallx'){
                $msgsuccess="Confirm";
                $flagfwd='1';    
            }
        }
        
    }
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
          	$fdprice = trim($data->val($i, 5));  
            $fdcurcd = trim($data->val($i, 6));
    		$fdlrcd = trim($data->val($i, 7));     
	              
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
								    $sqs3="SELECT ITNBR FROM bm008pr WHERE ITNBR='$fitnbr' AND Owner_Comp='$comp' ";  // edit by CTC
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
                                $msg='Check the length';
                                $error++;
                            }
                }else{
                    $status='E';
                    $msg='Column can not be empty';
                    $error++;
                }
            }
              
            // Insert into sellpricetmp
			if($status!='H'){
            $qi="INSERT INTO specialpriceawstmp(Cusno, Itnbr,PriceAWS,CurCDAWS,Price,CurCD,DlrCD,
                        StatusItem,Keterangan,Owner_Comp) 
                    VALUES ('$fcusno','$fitnbr',$fprice,'$fcurcd',$fdprice,'$fdcurcd','$fdlrcd',
                        '$status','$msg','$comp')
                    ON DUPLICATE KEY UPDATE Cusno='$fcusno',Itnbr='$fitnbr',
                        PriceAws=$fprice,CurCDAWS='$fcurcd',Price=$fdprice,CurCD='$fdcurcd', DlrCD='$fdlrcd', StatusItem='$status',Keterangan='$msg',Owner_Comp='$comp' ";  // edit by CTC
           // echo $qi;
			mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
			}
        }  
        
        // Check duplicate ITNBR AND CUSNO in sellpricetmp table
        $qs2="SELECT Cusno,Itnbr FROM specialpriceawstmp WHERE StatusItem != 'H' AND Owner_Comp='$comp' "; // edit by CTC
        $sqlqs2=mysqli_query($msqlcon,$qs2);
        while($arr2=mysqli_fetch_array($sqlqs2)){
            set_time_limit(0);
            $cusnotmp=$arr2['Cusno'];
            $itnbrtmp=$arr2['Itnbr'];
            
            // Get cusno and itnbr from sellprice table
            $qs3="SELECT Cusno,Itnbr FROM specialpriceaws WHERE Cusno='$cusnotmp' AND Itnbr='$itnbrtmp' AND Owner_Comp='$comp' ";  // edit by CTC
            $sqlqs3=mysqli_query($msqlcon,$qs3);
            $arr3=mysqli_fetch_array($sqlqs3);

            if(!$arr3){
                if($radvalact=='add'){
                    $flag='addx';
                }else if($radvalact=='edit'){
                    $status='E';
                    $msg='Failed to edit because could not found Item Number: '.$itnbrtmp.' & Customer No.'.$cusnotmp;
                    $qu1="UPDATE specialpriceawstmp SET StatusItem='$status', Keterangan='$msg' WHERE Cusno='$cusnotmp' AND Itnbr='$itnbrtmp' AND Owner_Comp='$comp' "; // edit by CTC
                    mysqli_query($msqlcon,$qu1);
                    $error++;
                }else{
                    $flag='editallx';
                }
            }else{
                if($radvalact=='edit'){
                    $flag='editx';
                }else if($radvalact=='add'){
                    $status='E';
                    $msg='Failed to add because duplicate  Item Number: '.$itnbrtmp.' & Customer No. '.$cusnotmp;
                    $qu2="UPDATE specialpriceawstmp SET StatusItem='$status', Keterangan='$msg' WHERE Cusno='$cusnotmp' AND Itnbr='$itnbrtmp' AND Owner_Comp='$comp'"; // edit by CTC
                    mysqli_query($msqlcon,$qu2);
                    $error++;
                }else{
                     $flag='editallx';
                }
            }
        }
        
        // If there are error upload
        if($error>0){
            $errortable='Error';
            $flagfwd='3';
        }else{
            // If action Add 
            if($flag=='addx'){
                $msgsuccess='Add';
                $flagfwd='1';
            }
            // If action Replace Partial
            if($flag=='editx'){
                $msgsuccess='Replace';
                $flagfwd='1';      
            
            }
            // If action Replace All    
            if($flag=='editallx'){
                $msgsuccess="Confirm";
                $flagfwd='1';    
            }
        }
    }
    /* ========================================
       END UPLOAD XLS File
     ======================================== */
    
    if($flagfwd=='1'){
        $msg=$msgsuccess;
    }
    if($flagfwd=='2'){
        $msg=$msgval;
    }
    if($flagfwd=='3'){
        $msg=$errortable;
    }
    //echo $msg;
   echo "<SCRIPT type=text/javascript>document.location.href='../imspecialaws.php?msg=$msg'</SCRIPT>";
}
?>
