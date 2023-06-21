<?php
	// echo "session imptable =" . $_SESSION['imptable'];
		
session_start();
require_once('../../core/ctc_init.php'); // add by CTC
    $comp = ctc_get_session_comp(); 

		if(basename($_FILES['file']['name'])==""){
			echo "<script>document.location.href='test.php';</script>";
		}
			
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Error: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{			 
  		$filename=basename($_FILES['file']['name']);
  		$ext = substr($filename, strrpos($filename, '.') + 1);
		/*echo $ext;
		echo "<br>";
		echo $_FILES["file"]["type"];
		echo "<br>";
		echo $_FILES["file"]["size"];
*/



        if (($ext == "xls") && ($_FILES["file"]["size"] < 2000000)) {
		
			require('../db/conn.inc');
			include "../excel_reader2.php";
		
			
		
		$count=0;
		$data = new Spreadsheet_Excel_Reader($_FILES['file']['tmp_name'], true);
			//read the number of rows from excel data
			$count = $data->rowcount($sheet_index=0);
			$sukses = 0;//success
			$failed = 0; // failed
			$warning=0;
			$error=0;
			$flag="";
			$errorCount=0;
            $status='';

            $search=array("'","Ã");
            $replace=array("\\'","A");

			for ($i=2; $i<=$count; $i++)
			{	
				$supno = trim($data->val($i, 1));
				$supnm = trim($data->val($i, 2));
				$add1 = trim($data->val($i, 3));
				$add2 = trim($data->val($i, 4));
				$add3 = trim($data->val($i, 5));
				$email1 = trim($data->val($i, 6));
				$email2 = trim($data->val($i, 7));
				$logo = trim($data->val($i, 8));
				$website = trim($data->val($i, 9));
				$duedate = trim($data->val($i, 10));
				$holidayck = trim($data->val($i, 11));
                
                if(!is_numeric($duedate)){
                    $msg='Duedate should be numeric!';
                    $status='F';
                    $error++;
                }
                if(!is_numeric($holidayck)){
                    $msg=$msg.' holidayck should be numeric!';
                    $status='F';
                    $error++;
                }
                

                if($status!='F' ){
				$query = "INSERT INTO TestTable
                    (Owner_comp, supno, supnm, add1, add2, add3, email1, email2, logo, website, duedate, holidayck)
                    VALUES('$comp', '$supno', '$supnm', '$add1', '$add2', '$add3', '$email1', '$email2', '$logo','$website',$duedate,$holidayck)";
				
				    $hasil = mysqli_query($msqlcon,$query);

                    echo $query . "<br>";
                }

				echo $status;
				if($hasil)
				{
					$sukses++;
				}else{ 
					$failed++;
				//echo $query;
				}
			
						
				}//for
 
		// tampilan status sukses dan failed
		$amend='1';
		
		

        }
    }
?>
	