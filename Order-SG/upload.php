<?

	/*  check Type file dan besarnya file sebelum diupload	
	Type file harus Jpeg dan gif, besarnya file harus lebih kecil dari 20 kb
	*/
	
		
			if ($_FILES["qqfile"]["error"] > 0){
    				echo "Return Code: " . $_FILES["foto"]["error"] . "<br />";
    			}else{    			
    				if (file_exists("upload/" . $_FILES["qqfile"]["name"])){
      					echo $_FILES["qqfile"]["name"] . " already exists. ";
					}else{
						move_uploaded_file($_FILES["qqfile"]["tmp_name"],"upload/" . $_FILES["qqfile"]["name"]);
					
      				}
						
			
    		}
		
		

?>
