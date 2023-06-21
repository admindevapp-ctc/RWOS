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
		
	 	$qd="DELETE FROM excratetmp where Owner_Comp='$comp'";
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
            $vcurcd = trim($data->val($i, 1));
            $vexcrate = trim($data->val($i, 2));
            $vEffdate = trim($data->val($i, 3));  
          
              
            // Check 1st row as header and value radio button is yes
            if($radvalrow=='yesrow' && $i==1){
              	$status='H';
            }else{
                // Check blank column
				$status='D';
				if(strlen(trim($vcurcd))!='2'){
					$status='E';
					$msg='CurCD should be 2 digits';
					$error++;
				}else{
					if(!is_numeric($vexcrate)){
						$status='F';
						$vexcrate=0;
						$msg='Exchange Rate should be numeric!';
						$error++;
					}else{
						if(strlen(trim($vEffdate))!=8 || !is_numeric($vEffdate)){
							$status='E';
							$msg='Effective date From should be YYYYMMDD!';
							$error++;
						}
							
					}
				
				}
				
            }
             
			 $skr=date('Ymd');
			 
            // Insert into sellpricetmp
            if($status!='H' ){
			$qi="INSERT INTO  excratetmp(CurCD, Rate, EfDateFr, uptime, StatusItem, Keterangan,Owner_Comp) 
                VALUES ('$vcurcd',$vexcrate,'$vEffdate','$skr', '$status','$msg','$comp')
                ON DUPLICATE KEY UPDATE CurCD='$vcurcd',rate=$vexcrate,
                    EfDateFr='$vEffdate',uptime='$skr',StatusItem='$status',Keterangan='$msg',Owner_Comp='$comp'";
			//echo $qi;		
            mysqli_query($msqlcon,$qi) OR die(mysqli_error()); 
        	}  
			
			
		}
		if($error<=0){
			$msg='Add';
		}else{
			$msg='Error';
		}
		 echo "<SCRIPT type=text/javascript>document.location.href='../imexchange.php?msg=$msg'</SCRIPT>";
	}
}

?>
