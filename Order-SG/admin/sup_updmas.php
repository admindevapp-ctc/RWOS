<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{   
	if($_SESSION['redir']=='Order-SG'){
		$cusno=	$_SESSION['cusno'];
		$cusnm=	$_SESSION['cusnm'];
		$password=$_SESSION['password'];
		$alias=$_SESSION['alias'];
		$table=$_SESSION['tablename'];
		$type=$_SESSION['type'];
		$custype=$_SESSION['custype'];
		$user=$_SESSION['user'];
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='a')header("Location:../main.php");
	 }else{
		echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	

	header("Location:../login.php");
}

$xid=trim($_GET['id']);
$xaction=trim($_GET['action']);
//echo ">>".$xid . "action >>".$xaction;

$vsupno=trim($_POST['vsupno']);
$vsupname=trim($_POST['vsupnm']);
$vaddr1=trim($_POST['vaddr1']);
$vaddr2=trim($_POST['vaddr2']);
$vaddr3=trim($_POST['vaddr3']);
$vemail1=trim($_POST['vemail1']);
$vemail2=trim($_POST['vemail2']);
$vweb=trim($_POST['vweb']);
$vlogo=trim($_POST['vlogo']);
$vduedate=trim($_POST['vduedate']);
$vholidayck=trim($_POST['vholiday']);
$vaction =trim($_POST['vaction']);
$supimage=trim($_POST['vsupimage']);
//echo "supimage ". $supimage . "<br/>";
require('../db/conn.inc');
if($vsupname==''){
	$errorx='Suppiler Name';
}

if ( 0 < $_FILES['file']['error'] ) {
	    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
	if($_FILES['sup_image']['name'] != ''){
	// upload image
	$imgFile = $_FILES['sup_image']['name'];
	$tmp_dir = $_FILES['sup_image']['tmp_name'];
	$imgSize = $_FILES['sup_image']['size'];
	if($imgFile != ''){
	$upload_dir = '../sup_logo/'; // upload directory
		if (!file_exists($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$pathimg = rand(1000,1000000).".".$imgExt;
				
			
			
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size '2MB'
				if($imgSize < 2000000){
					
					$png = imagecreatefrompng($tmp_dir);
						  
						// Turn off alpha blending
						imagealphablending($png, false);
						  
						// Add alpha as 100 to image
						$transparent = imagecolorallocatealpha($png, 255, 255, 255, 100);
						imagefill($png, 0, 0, $transparent);
						  
						// Set alpha flag to false so that
						// alpha info is not saved in the image
						imagesavealpha($png, false);
						  
						// Save the image
						if(!imagepng($png, $upload_dir.$pathimg)){
							move_uploaded_file($tmp_dir,$upload_dir.$pathimg);
						}

					imageDestroy($png);
				}
				else{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
			imagedestroy($tmp_dir);
		}
	}
	else{
		$pathimg =  $supimage;
	}
}


if($vaction=='' && $xid!=''){
	$vaction=$xaction;	
}
$gflag='';
//echo $vaction;

//check Holiday
//$query="SELECT  calcd FROM supsc003pr WHERE Owner_Comp='$comp'and DATE_FORMAT(now(),'%Y%m') = yrmon";
//echo $query;
//$sql=mysqli_query($msqlcon,$query);
//if($hasil = mysqli_fetch_array ($sql)){
	if($vaction=='add'){
		if($errorx==''){	
			//check CUST3 and Alias
			
			$query="Select * from  supmas  where  supno='$vsupno' and Owner_Comp='$comp'";
			//echo $query;
			$sql=mysqli_query($msqlcon,$query);
			if($hsl = mysqli_fetch_array ($sql)){
				$errorx="Supplier already created";
				$gflag='1';
			}else{

				$isvholidayckEmpty = (!isset($vholidayck) || trim($vholidayck) === '');

				if(!$isvholidayckEmpty){
					$query="insert into supmas (Owner_comp, supno, supnm, add1, add2, add3, email1, email2, website, logo, duedate, holidayck) values('$comp', '$vsupno', '$vsupname', '$vaddr1', '$vaddr2', '$vaddr3', '$vemail1', '$vemail2', '$vweb', '$pathimg','$vduedate', $vholidayck)";			
				}
				else{
					$query="insert into supmas (Owner_comp, supno, supnm, add1, add2, add3, email1, email2, website, logo, duedate) values('$comp', '$vsupno', '$vsupname', '$vaddr1', '$vaddr2', '$vaddr3', '$vemail1', '$vemail2', '$vweb', '$pathimg','$vduedate')";
				}
				mysqli_query($msqlcon,$query);
			}
		}
	}else{
		if($vaction=='edit'){
				if($errorx==''){	

					$isvholidayckEmpty = (!isset($vholidayck) || trim($vholidayck) === '');
		
					if(!$isvholidayckEmpty){
						$query="update supmas set supnm='$vsupname', add1='$vaddr1',add2='$vaddr2'
						, add3='$vaddr3', email1='$vemail1',email2= '$vemail2'
						, website='$vweb', logo='$pathimg', duedate= '$vduedate', holidayck=$vholidayck
						where trim(supno) = '$vsupno' and Owner_Comp='$comp' ";
					}
					else{
						$query="update supmas set supnm='$vsupname', add1='$vaddr1',add2='$vaddr2'
						, add3='$vaddr3', email1='$vemail1',email2= '$vemail2'
						, website='$vweb', logo='$pathimg', duedate= '$vduedate'
						where trim(supno) = '$vsupno' and Owner_Comp='$comp' ";
					}
					mysqli_query($msqlcon,$query);
				}
		}else{
			if($vaction=='delete'){
				unlink("../sup_logo/".$pathimg);
				$query="delete from supmas where trim(supno) = '$xid' and Owner_Comp='$comp'";
				mysqli_query($msqlcon,$query);
				$errorx='';
			}

		}
			
			
	}
//}
//else{
//	$gflag = '1';
//	$errorx  = get_lng($_SESSION["lng"], "E0006")/* Error: Calender was not found, Please contact DENSO PIC */; 
//}
//echo $query;

if($errorx!=''){	
	if($gflag=='1'){
			//echo $errorx ;
	}else{
			$errorx = $errorx . ' should be filled';
	}
echo "<script> document.location.href='sup_mas.php?action=".$vaction."&result=error&msg=".$errorx."'; </script>";
}else{
	//echo 'ok';
	echo "<script> document.location.href='sup_mainadm.php'; </script>";
}

?>
