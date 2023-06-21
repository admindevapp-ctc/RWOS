<?php

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
                $fitnbr=$data[0];
                $fassycd=$data[1];
                $fitdsc=trim(str_replace($search,$replace,stripslashes($data[2])));
                $fitcls=trim($data[3]);
                $fplann=trim($data[4]);
                $fproduct=trim($data[5]);
                $fsubproduct=trim($data[6]);
                $flotsize=trim($data[7]);
                $fitcat=trim($data[8]);
                $fittyp=trim($data[9]);
                
                $num = count($data);   
                // Check 1st row as header and value radio button is yes
                if($radvalrow=='yesrow' && $row==1){
                    // Check count field
                    if($num==10){
                        // Check blank column
                        if(strlen($fitnbr)!=0 && strlen($fassycd)!=0 && strlen($fitdsc)!=0 && 
                            strlen($fitcls)!=0 && strlen($fplann)!=0 && strlen($fproduct)!=0 && 
                            strlen($fsubproduct)!=0 && strlen($flotsize)!=0 && strlen($fitcat)!=0 && 
                            strlen($fittyp)!=0){
                                // Check value 1st row
                                if(trim($fitnbr)=='ITNBR' && trim($fassycd)=='ASSYCD' && 
                                    trim($fitdsc)=='ITDSC' && trim($fitcls)=='ITCLS' && 
                                    trim($fplann)=='PLANN' && trim($fproduct)=='Product' &&
                                    trim($fsubproduct)=='SubProd' && trim($flotsize)=='Lotsize' &&
                                    trim($fitcat)=='ITCAT' && trim($fittyp)=='ITTYP'){
                                        $flotsize=0;
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
                        $msg='Header should have 10 fields';
                        $error++;
                    }
                }else{
                    // Check count field
                    if($num==10){
                        // Check blank column
                        if(strlen($fitnbr)!=0 && strlen($fassycd)!=0 && strlen($fitdsc)!=0 && 
                            strlen($fitcls)!=0 && strlen($fplann)!=0 && strlen($fproduct)!=0 && 
                            strlen($fsubproduct)!=0 && strlen($flotsize)!=0 && strlen($fitcat)!=0 && 
                            strlen($fittyp)!=0){
                            
                            // Check length
                            if(strlen($fitnbr)<=15 && strlen($fassycd)<=4 && strlen($fitdsc)<=30 && 
                                strlen($fitcls)==2 && strlen($fplann)<=5 && 
                                strlen($fproduct)<=50 && strlen($fsubproduct)<=50 && 
                                strlen($flotsize)<=9 && strlen($fitcat)==2 && 
                                strlen($fittyp)==2){
                                
                                    // Check numeric type for lot size
                                    if(!is_numeric($flotsize)){
                                        $status='E';
                                        $msg=$fitnbr.' - Lot size format should be numeric';
                                        $error++;
                                    }else{
                                        // Check duplicate ITNBR or not in bm008prtmp table
                                        $qs1="SELECT ITNBR FROM bm008prtmp WHERE ITNBR='$fitnbr'";
                                        $sqlqs1=mysqli_query($msqlcon,$qs1);
                                        $arrqs1=mysqli_fetch_array($sqlqs1);
                                        if($arrqs1){
                                            $status='E';
                                            $msg=$fitnbr.' - Duplicate records for ITNBR, please check your csv file';
                                            $error++;
                                        }
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
                        $msg='Should 10 fields';
                        $error++;
                    }
                }    
                
                //echo $row.' '.$radvalrow.' '.$fitnbr.' '.$fassycd.' '.$fitdsc.' '.$fitcls.' '.$fplann.' '.$fproduct.' '.$fsubproduct.' '.$flotsize.' '.$fitcat.' '.$fittyp.' '.$msg.'<br>';
                // Insert into bm008prtmp
                $qi="INSERT INTO bm008prtmp(ITNBR,ASSYCD,ITDSC,ITCLS,
                        PLANN,Product,SubProd,Lotsize,ITCAT,ITTYP,StatusItem,Keterangan) 
                    VALUES ('$fitnbr','$fassycd','$fitdsc','$fitcls',
                        '$fplann','$fproduct','$fsubproduct','$flotsize',
                        '$fitcat','$fittyp','$status','$msg')
                    ON DUPLICATE KEY UPDATE ITNBR='$fitnbr',ASSYCD='$fassycd',
                        ITDSC='$fitdsc',ITCLS='$fitcls',PLANN='$fplann',Product='$fproduct',
                        SubProd='$fsubproduct', Lotsize='$flotsize',ITCAT='$fitcat',
                        ITTYP='$fittyp',StatusItem='$status',Keterangan='$msg'";
                
                $sqlqi=mysqli_query($msqlcon,$qi) OR die(mysqli_error()); ;
                $row++;
            }
        }
        
        
        // If there are error upload
        if($error>0){
            $errortable='Error';
            $flagfwd='3';
        }else{
            // If action Add 
			 if($radvalact=='addx'){
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
            $fitnbr = $data->val($i, 1);
            $fassycd = trim($data->val($i, 2));
            $fitdsc = trim(str_replace($search,$replace,stripslashes($data->val($i, 3))));  
            $fitdsc = utf8_decode($fitdsc);
            $fitcls = $data->val($i, 4);
            $fplann = $data->val($i, 5);
            $fproduct = $data->val($i, 6);
            $fsubproduct = $data->val($i, 7);
            $flotsize = $data->val($i, 8);
            $fitcat = $data->val($i, 9);
            $fittyp= $data->val($i, 10);
              
            // Check 1st row as header and value radio button is yes
            if($radvalrow=='yesrow' && $i==1){
                // Check blank column
                if(strlen($fitnbr)!=0 && strlen($fassycd)!=0 && strlen($fitdsc)!=0 && 
                    strlen($fitcls)!=0 && strlen($fplann)!=0 && strlen($fproduct)!=0 && 
                    strlen($fsubproduct)!=0 && strlen($flotsize)!=0 && strlen($fitcat)!=0 && 
                    strlen($fittyp)!=0){
                    
                        // Check value 1st row
                        if(trim($fitnbr)=='ITNBR' && trim($fassycd)=='ASSYCD' && 
                            trim($fitdsc)=='ITDSC' && trim($fitcls)=='ITCLS' && 
                            trim($fplann)=='PLANN' && trim($fproduct)=='Product' &&
                            trim($fsubproduct)=='SubProd' && trim($flotsize)=='Lotsize' &&
                            trim($fitcat)=='ITCAT' && trim($fittyp)=='ITTYP'){
                                $flotsize=0;
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
                if(strlen($fitnbr)!=0 && strlen($fassycd)!=0 && strlen($fitdsc)!=0 && 
                    strlen($fitcls)!=0 && strlen($fplann)!=0 && strlen($fproduct)!=0 && 
                    strlen($fsubproduct)!=0 && strlen($flotsize)!=0 && strlen($fitcat)!=0 && 
                    strlen($fittyp)!=0){
                        
                        // Check length
                        if(strlen($fitnbr)<=15 && strlen($fassycd)<=4 && strlen($fitdsc)<=30 && 
                            strlen($fitcls)==2 && strlen($fplann)<=5 && strlen($fproduct)<=50 && 
                            strlen($fsubproduct)<=50 && strlen($flotsize)<=9 && strlen($fitcat)==2 && 
                            strlen($fittyp)==2){
                                // Check numeric type for lot size
                                if(!is_numeric($flotsize)){
                                    $status='E';
                                    $msg=$fitnbr.' - Lot size format should be numeric';
                                    $error++;
                                }else{
                                    // Check duplicate ITNBR or not in bm008prtmp table
                                    $qs1="SELECT ITNBR FROM bm008prtmp WHERE ITNBR='$fitnbr'";
                                    $sqlqs1=mysqli_query($msqlcon,$qs1);
                                    $arrqs1=mysqli_fetch_array($sqlqs1);
                                    if($arrqs1){
                                        $status='E';
                                        $msg=$fitnbr.' - Duplicate records for ITNBR, please check your csv file';
                                        $error++;
                                    }
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
              
            // Insert into bm008prtmp
            $qi="INSERT INTO bm008prtmp(ITNBR,ASSYCD,ITDSC,ITCLS,
                    PLANN,Product,SubProd,Lotsize,ITCAT,ITTYP,StatusItem,Keterangan) 
                VALUES ('$fitnbr','$fassycd','$fitdsc','$fitcls',
                    '$fplann','$fproduct','$fsubproduct','$flotsize',
                    '$fitcat','$fittyp','$status','$msg')
                ON DUPLICATE KEY UPDATE ITNBR='$fitnbr',ASSYCD='$fassycd',
                    ITDSC='$fitdsc',ITCLS='$fitcls',PLANN='$fplann',Product='$fproduct',
                    SubProd='$fsubproduct', Lotsize='$flotsize',ITCAT='$fitcat',
                    ITTYP='$fittyp',StatusItem='$status',Keterangan='$msg'";
            $sqlqi=mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
        }  
        
        // If there are error upload
        if($error>0){
            $errortable='Error';
            $flagfwd='3';
        }else{
            // If action Add 
            if($radvalact==='addx'){
                $msgsuccess='Add';
                $flagfwd='1';
            }
            // If action Replace Partial
            if($radvalact=='edit'){
                $msgsuccess='Replace';
                $flagfwd='1';      
            
            }
            // If action Replace All    
            if($radvalact=='editallx'){
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
    echo "<SCRIPT type=text/javascript>document.location.href='../imbm008pr.php?msg=$msg'</SCRIPT>";
    
}
?>
