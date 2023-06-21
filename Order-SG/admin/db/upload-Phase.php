<?php

session_start();
include "../../db/conn.inc";
include "excel_reader2.php";
require_once('./../../../core/ctc_init.php'); // add by CTC

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
	
	echo "<br>";
	echo "flag fwd =". $flagfwd; 
	echo "<br>";
	
	
    if($flagpro!=""){
		
	 	$qd="DELETE FROM phaseouttmp";
     	mysqli_query($msqlcon,$qd);	
	}
	
	
    
    /* ========================================
       UPLOAD XLS File
     ======================================== */
    if($flagpro=='upxls'){
        // Read excel file
        $data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);
            
        // Read row and column
        $baris = $data->rowcount($sheet_index=0);
        
        $error=0;
		$failed=0;
        // Start from 1st row
        for ($i=1; $i<=$baris; $i++){
            $msg='';
            $status='';
            $search=array("'","Ã");
            $replace=array("\\'","A");
            // Read data
            $vitnbr = trim($data->val($i, 1));
            $vsub = trim($data->val($i, 2));
            $vdesc = trim($data->val($i, 3));  
          
              
            // Check 1st row as header and value radio button is yes
            if($radvalrow=='yesrow' && $i==1){
              	$status='H';
            }else{
                // Check blank column
				$status='D';
				if(strlen(trim($vitnbr))>15){
					$status='E';
					$msg='Item should be not more than 15 digits';
					$error++;
				}else{
					if(strlen(trim($vdesc))>30){
						$status='F';
						$msg='Description should be not more than 30 digits!';
						$error++;
									
					}
				
				}
				
            }
             
			 $skr=date('Ymd');
			 
            // Insert into sellpricetmp
            if($status!='H' ){
			$qi="INSERT INTO  Phaseouttmp(ITNBR, SUBITNBR, ITDSC,  StatusItem, Keterangan,Owner_Comp) 
                VALUES ('$vitnbr','$vsub','$vdesc', '$status','$msg','$comp')
                ON DUPLICATE KEY UPDATE SUBITNBR='$vsub',ITDSC='$vdesc',
                    StatusItem='$status',Keterangan='$msg',Owner_Comp='$comp'";
			//echo $qi;		
            mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
        	}  
			
			
		}
		if($error<=0){
			$msg=$radvalact;
		}else{
			$msg='Error';
		}
		echo "<SCRIPT type=text/javascript>document.location.href='../imPhaseOut.php?msg=$msg'</SCRIPT>";
		}
}

?>
