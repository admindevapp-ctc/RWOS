<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['redir'];
		$_SESSION['type'];
		$_SESSION['com'];
		$_SESSION['user'];
		$_SESSION['alias'];
		$_SESSION['tablename'];
    	$_SESSION['custype'];
		$_SESSION['dealer'];
		$_SESSION['group'];
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
		if($type!='a'){
			header("Location: ../main.php");
		}
		}else{
			echo "<script> document.location.href='../../".$redir."'; </script>";
		}
	}else{	
		header("Location:../../login.php");
	}

?>

<html>
	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
 <style type="text/css">
<!--

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}

#pagination1 a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#333;
	text-decoration: none;
	background-color: #F3F3F3;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
}
#pagination1 a:hover 
{
color:#FF0084;
cursor: pointer;
}

#pagination1 a.current 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#FFF;
	background-color: #000;
}


-->
 </style>


<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
<script type="text/javascript">
$(function() {
		   

	$('#frmimport').submit(function(){
		if($('#userfile').val()==''){
			alert('Please choose file!');
			return false;
		}

		var ext = $('#userfile').val().split('.');
		
		if(ext[1] != "csv"){
			alert("<?php echo get_lng($_SESSION["lng"], "E0061"); ?>");
			return false;
		}  
	});
});
</script>



	</head>
	<body >
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->
		
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				
  				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
		</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
        	  <div class="headerbar">Administration</div>
               <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="sup_mainadm";
				include("navAdm.php");

			
			  ?>
        	</div>
		
			<div id="twocolRight">
			Download format csv here : <a href="prototype/supplier.csv" target="_blank" download ><img src="../images/csv.jpg" width="16" height="16" border="0"></a>	
             <form method="POST" enctype="multipart/form-data" name="uploadForm" id="frmimport" action='db/upload-supplier.php' >
            <table class='arial11'>
				<tr>
                    <td>Upload</td>
                    <td>:</td>
                    <td><input type="file" size="45"  id= "userfile" name="userfile"></td>
                </tr>
                <tr>
                    <td>File type</td>
                    <td>:</td>
                    <td>
                        <b><?php echo get_lng($_SESSION["lng"], "L0469"); // Only CSV file Allowed! ?></b>
                    </td>
                </tr>
                <tr>
                    <td>First row for header</td>
                    <td>:</td>
                    <td>
                        <input type="radio" name="rdfirstrow" value="yesrow"> Yes
                        <input type="radio" name="rdfirstrow" value="norow"> No
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="radio" name="rdreplace" value="add"> Add and Replace Partial
                        <input type="radio" name="rdreplace" value="editall"> Replace All (Delete All first)
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <input name="submit" type="submit" value="Submit"> 
                        <input type="reset" value="Reset">
                    </td>
                </tr>
            </table><br/>
        </form>

			<?
              include "../db/conn.inc";
			   $msg=$_GET['msg'];
			   $result=$_GET['result'];
				// If error upload
				
                if($result=='Error'){
                    $msgtbl="<H3>Error ".$msg."</H3><table width='100%'  class='tbl1' cellspacing='0' cellpadding='0'>
 						 <tr class='arial11whitebold' bgcolor='#AD1D36' >
                            <th width='5%' scope='col'>Supno</th>
                            <th width='5%' scope='col'>Supname</th>
                            <th width='20%' scope='col'>Address1</th>
                            <th width='20%' scope='col'>Address2</th>
                            <th width='20%' scope='col'>Address3</th>
                            <th width='10%' scope='col'>Email1</th>
                            <th width='10%' scope='col'>Email2</th>
                            <th width='10%' scope='col'>Website</th>
                            <th width='10%' scope='col'>logo</th>
                            <th width='5%' scope='col'>Duedate</th>
                            <th width='5%' scope='col'>HolidayCk</th>
                        </tr>";
                    $qse="SELECT * FROM supmastemp WHERE Owner_Comp='$comp' ";
					//echo $qse;
                    $sqlqse=mysqli_query($msqlcon,$qse);
                    while($arx=mysqli_fetch_array($sqlqse)){
                        $supno=$arrqa['supno'];
						$supnm=$arrqa['supnm'];
						$addr1=$arrqa['add1'];
						$addr2=$arrqa['add2'];
						$addr3=$arrqa['add3'];
						$email1=$arrqa['email1'];
						$email2=$arrqa['email2'];
						$website=$arrqa['website'];
						$logo=$arrqa['logo'];
						$duedate=$arrqa['duedate'];
						$holidayck=$arrqa['holidayck'];
                        $msgtbl.="<tr class='arial11black'>
                                    <td>$supno</td>
                                    <td>$supnm</td>
                                    <td>$addr1</td>
                                    <td>$addr2</td>
                                    <td>$addr3</td>
                                    <td>$email1</td>
                                    <td>$email2</td>
                                    <td>$website</td>
                                    <td>$logo</td>
                                    <td>$duedate</td>
                                    <td>$holidayck</td>
                                </tr>";
                    }
                    $msgtbl.="</table>";
                    $qd="DELETE FROM supmastemp";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgtbl;
                }
                
                // If succesfully add data
                if($result=='Add'){
                    
                    $msgsuccess='Add data success';

					
                    $qa="SELECT * FROM supmastemp ";
					//echo $qa;
                    $sqlqa=mysqli_query($msqlcon,$qa);
                    while($arrqa=mysqli_fetch_array($sqlqa)){
                        $search=array("'","�");
                        $replace=array("\\'","A");
                
                       
                        $supno=trim($arrqa['supno']);
						$supnm=trim($arrqa['supnm']);
						$addr1=trim($arrqa['add1']);
						$addr2=trim($arrqa['add2']);
						$addr3=trim($arrqa['add3']);
						$email1=trim($arrqa['email1']);
						$email2=trim($arrqa['email2']);
						$website=trim($arrqa['website']);
						$logo=trim($arrqa['logo']);
						$duedate=trim($arrqa['duedate']);
						$holidayck=trim($arrqa['holidayck']);
                        $qi2="Replace INTO supmas(Owner_comp, supno, supnm, add1, add2, add3
						, email1, email2, website, logo, duedate, holidayck)  VALUES('$comp', '$supno'
						, '$supnm', '$addr1', '$addr2', '$addr3', '$email1', '$email2'
						, '$website', '$logo' , '$duedate', $holidayck)"; 
                        mysqli_query($msqlcon,$qi2);

						//echo $qi2 ."<br/>";
                     }
                    $qd="DELETE FROM supmastemp ";
                    mysqli_query($msqlcon,$qd);
                    $msg=$msgsuccess;
				  // echo $qd;
                }
                
                // If succesfully replace partial data
              
                echo '<table width="90%" border="0" align="center" bgcolor="#AD1D36">';
				echo '<tr  class="arial11whitebold"><td align="center">';
				echo $msg;
				echo '</td></tr></table>';
            ?>

			<div id="footerMain1">
				<ul>
					<!--
					
					     
					-->
				</ul>

				<div id="footerDesc">

					<p>
					Copyright &copy; 2023 DENSO . All rights reserved  
					</p>
				</div>
			</div>
		</div>

	</body>
</html>