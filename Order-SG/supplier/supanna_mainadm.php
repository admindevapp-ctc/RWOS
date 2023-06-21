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
		$supno=$_SESSION['supno'];
		$comp = ctc_get_session_comp(); // add by CTC
		if($type!='s'){
			header("Location:../main.php");
		}
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<?php

	require_once 'cata_dbconfig.php';
	require('../db/conn.inc');
	if(isset($_GET['delete_id']))
	{
		$arr_delete = explode(',',$_GET['hidid']);

		// select image from db to delete
		$DB_con->beginTransaction();
		$stmt_select = $DB_con->prepare('SELECT PrtPic FROM supannounce WHERE ID =:uid AND Owner_Comp=:comp and supno = :supno');
		$stmt_delete = $DB_con->prepare('DELETE FROM supannounce WHERE ID =:uid AND Owner_Comp=:comp and supno = :supno');

		foreach($arr_delete as $v){
		$stmt_select->execute(array(':uid'=>$v,':comp'=>"$comp",':supno'=>"$supno"));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		unlink("../sup_annaimages/".$imgRow['PrtPic']);
		
		$stmt_delete->bindParam(':uid',$v);
		$stmt_delete->bindParam(':comp',$comp);
		$stmt_delete->bindParam(':supno',$supno);
		$stmt_delete->execute();
		}
		$DB_con->commit();
		header("Location: supanna_mainadm.php");
	}

?>

<html>

	<head>
    <title>Denso Ordering System</title>
	<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
   
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
	
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">
	<script src="../lib/jquery-1.4.2.min.js"></script>
	<script src="../lib/jquery.ui.core.js"></script>
 	<script src="../lib/jquery.ui.widget.js"></script>
	<script src="../lib/jquery.ui.mouse.js"></script>
	<script src="../lib/jquery.ui.button.js"></script>
	<script src="../lib/jquery.ui.draggable.js"></script>
	<script src="../lib/jquery.ui.position.js"></script>
	<script src="../lib/jquery.ui.resizable.js"></script>
	<script src="../lib/jquery.ui.dialog.js"></script>
	<script src="../lib/jquery.effects.core.js"></script>
    <script src="../lib/jquery.ui.datepicker.js"></script>
	<script src="../lib/jquery.ui.autocomplete.js"></script>
	<link rel="stylesheet" href="../css/demos.css">   
	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
	<link rel="stylesheet" type="text/css" href="../css/custom_datatable.css">
	<link rel="stylesheet" href="../admin/bootstrap/css/bootstrap.min.css">

    
<script type="text/javascript">
function newPopup(url) {
	popupWindow = window.open(
		url,'popUpWindow','height=500,width=500,left=600,top=350,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=no')
}
</script>
</head>
<body >

   		<?php ctc_get_logo_new() ?>

		<div id="mainNav">
		<?php 
			  	$_GET['step']="1";
				include("supnavhoriz.php");
			?>
	</div> 
    	<div id="isi">
        
        <div id="twocolLeft">
           	<div class="hmenu">
               <?
			  	$MYROOT=$_SERVER['DOCUMENT_ROOT'];
			  	$_GET['current']="supannouncement";
				include("supnavAdm.php");
			  ?>
            </div>
            <div id="twocolRight">
            <?
		  require('../db/conn.inc');

		//Supplier
		$query="select * from supmas where Owner_Comp='$comp' and supno='$supno'  ";
		$sql=mysqli_query($msqlcon,$query);	
		//echo $query;
		while($hasil = mysqli_fetch_array ($sql)){
			
			$supno=$hasil['supno'];	
			$supnm=$hasil['supnm'];
			$suplogo= $hasil['logo'];
			$inpsupcode= $supno;	
			$inpsupname= $supnm;		
			
		}

		 $xcusno='';
		 $xprtno='';
		  if(isset($_GET["vcusno"])){
				$xcusno=$_GET["vcusno"];
				$xprtno=$_GET["vprtno"];
		  }
		 	$inpcusno= '<select name="vcusno" id="vcusno" class="arial11blue">';
			$inpcusno= $inpcusno .  ' <option value=""></option>';
			$query="select Cusno, Cusnm from cusmas where Owner_Comp='$comp' order by cusno ";
			$sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
				$ycusno=$hasil['Cusno'];
				$ycusnm=$hasil['Cusnm'];
				
				if(trim($ycusno)==trim($xcusno)){
				   	 $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'" selected>'.$ycusno.' - '. $ycusnm. ' </option>';
				}else{
					  	 $inpcusno= $inpcusno .  ' <option value="'.$ycusno.'">'.$ycusno.' - '. $ycusnm. ' </option>';		
				}
				
			}
        			$inpcusno= $inpcusno . ' </select>';
			
			$inpprtno="<input type=\"text\" name=\"vprtno\"  value ='" . $xprtno. "' class=\"arial11black\" maxlength=\"30\" size=\"30\" ></input>";
		  ?>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" >
		  <tr class="arial11blackbold" style="vertical-align: top;">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td width="20%" colspan="2" class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "M009");?></td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
                <td rowspan="3" align="right" width="47%"><img src='<?php echo "../sup_logo/".$suplogo; ?>' height="80" width="200" /></td>
            </tr>
			<tr class="arial11blackbold">
				<td colspan="3">
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0451");?></span>
					<span class="arial12Bold">:</span>
				
					<span class="arial12Bold"><? echo $supno?></span>	
				</td>
				<td>
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0452");?></span>
					<span class="arial12Bold">:</span> 
				</td>
                <td>
					<span class="arial12Bold"align="left"><? echo $supnm?></span>	
				</td>
       	 	</tr>
			  <tr class="arial11blackbold">
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
				  <td>&nbsp;</td>
			  </tr>
		  </table> 
		<div class="page-header" style="margin:0px;">
    		<h1 class="h2" style="margin:0px;">
				<a class="btn btn-default" href="announcementmain/anna_addnew.php"> <span class="glyphicon glyphicon-plus"></span> &nbsp;<?php echo get_lng($_SESSION["lng"], "L0490");?></a>
			</h1> 
		</div>

<marquee direction="left" style="font-size:9pt;color:red;"  scrolldelay="50" scrollamount="3" onmouseout="this.start()" onmouseover="this.stop()">
				<?php
              	include "../db/conn.inc";
					$sql = "SELECT * FROM `supannounce` WHERE `start`<=CURRENT_DATE and `end`>=CURRENT_DATE AND Owner_Comp='$comp' and supno = '$supno' GROUP BY `title`,`detail`,`start`,`end` ORDER BY `ID` DESC";
					// echo $sql ."<br/>";
					$result = mysqli_query($msqlcon,$sql);
				          if(mysqli_num_rows($result) > 0)  
                     {  
                          while($row = mysqli_fetch_array($result))  
                          {  
							   echo '<img src="../images/marquee.gif" width="20" height="20" />';
							   //echo '<a href="anna_details.php?edit_id='.$row[0].'"> '.$row['title'].'</a>'; 
                               //echo '<label><a href="JavaScript:newPopup('.$row['0'].')" target="_blank">'.$row['title'].'</a></label>';  
							    echo'<label><a href=JavaScript:newPopup("announcementmain/anna_details.php?edit_id='.$row['ID'].'")>'.$row['title'].'</a></label>'; 
							 
                          }  
                     } 
				?>
</marquee>	

<span class="arial11blackbold"><h5><?php echo get_lng($_SESSION["lng"], "L0495"); //All Announcement ?></h5></span>
<? function tableheader (){  ?>
 <table cellpadding="0" cellspacing="0" border="0" class="tbl1 tblanna" id="example" width="97%">
<thead>
		
		<tr align="center" height="30" class="arial11whitebold" bgcolor="#AD1D36">
			<th width="8%" style="text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0457"); //Company Code ?></th>
			<th width="8%" style="text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0451"); //Supplier code<?></th>
			<th width="8%"><?php echo get_lng($_SESSION["lng"], "L0482"); //Customer Number?></th>
			<th width="10%"><?php echo get_lng($_SESSION["lng"], "L0480"); //Title?></th>
            <th width="21%"><?php echo get_lng($_SESSION["lng"], "L0481"); //Detail?></th>
            <th width="8%" style="text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0483"); //Effective From?></th>
			<th width="8%" style="text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0484"); //Effective To?></th>
			<th width="10%" style="text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0485"); //Update by?></th>
			<th width="5%"><?php echo get_lng($_SESSION["lng"], "L0431"); //Picture?></th>			
			<th width="5%" style="text-align:center;"><?php echo get_lng($_SESSION["lng"], "L0481"); //Detail?></th>
		    <th width="10%" style="text-align: center;"><?php echo get_lng($_SESSION["lng"], "L0467"); //Action?></th>
			
		</tr>
	</thead>
	<tbody>

<?php
}
	require_once "announcementmain/anna_function.php";
	include('cata_connect.php');
	
	if(isset($_GET["page"]))
	$page = (int)$_GET["page"];
	else
	$page = 1;
	$setLimit = 10;
	$pageLimit = ($page * $setLimit) - $setLimit;
	
	$stmt = $DB_con->prepare('SELECT  ID,title,detail,PrtPic,start,end,updateby,Owner_Comp,cusno,GROUP_CONCAT(cusno ORDER BY CAST(cusno AS INT) ASC) as `allcus`,GROUP_CONCAT(ID ORDER BY ID ASC) as `allID`,title FROM supannounce WHERE Owner_Comp=? and supno=? GROUP BY supno, title, detail,`start`,`end` order BY cusno ASC limit ?,?');  // edit by CTC
	$stmt->bindValue(1, $comp, PDO::PARAM_STR_CHAR);
	$stmt->bindValue(2, $supno, PDO::PARAM_STR_CHAR);
	$stmt->bindValue(3, $pageLimit, PDO::PARAM_INT);
	$stmt->bindValue(4, $setLimit, PDO::PARAM_INT);
    $stmt->execute();
	
	if($stmt->rowCount() > 0)
	{
		tableheader ();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			extract($row);
			$urlhidid =  urlencode($allID); // Use in GET url to muntiple delete & update
			if(strlen($allcus) >=20){
				$arr = explode(',',$allcus);
				$text_txt = '';
				
				$i = 1;
				foreach($arr as $k => $v){
					if($i == 10){ //Amount of cusno per line
						$lnbrk = ' &#013; '; 
						$i = 1;
					}else{
						$lnbrk = '';
						$i++;
					}
					$sep = ', ';
					if($k+1 == count($arr)){
						$sep = '';
					}
					$text_txt .= $v .$sep. $lnbrk;
				}
				$hov_all='';
				$txt_cus = substr($allcus, 0, 20)."...";
				$hov_all = $allcus.' &#013; '.$allcus.$allcus.$allcus.$allcus.$allcus;
			}else{
				$txt_cus = $allcus;
				$hov_all=''; 
			}
			echo "<tr class=\"arial11black\" align=\"Left\"height=\"10\"><td style='text-align:center;'>".$Owner_Comp."</td><td style='text-align:center;'>".$supno."</td><td title='".$text_txt."'>".$txt_cus."</td><td >".$title."</td><td>".$detail."</td><td style='text-align:center;'>".date("d/m/Y", strtotime($start))."</td><td style='text-align:center;'>".date("d/m/Y", strtotime($end))."</td><td style='text-align:center;'>".$updateby."</td>" ;
			?>
			
			<td><img src="../sup_annaimages/<?php echo $row['PrtPic']; ?>" class="img-rounded" width="50px" height="50px" /></td>
			<td align="center" style="text-align:center;"><a href="JavaScript:newPopup('announcementmain/anna_details.php?edit_id=<?php echo $row['ID']; ?>')"><?php echo get_lng($_SESSION["lng"], "L0481"); //Detail?></a></td>
			<?
			   echo "<td class=\"lasttd\">";?>
				<a  href="announcementmain/anna_editform.php?edit_id=<?php echo $row['ID']; ?>&hidid=<?php echo $urlhidid;?>" title="click for edit" onclick="return confirm('Confirm to edit ?')"><?php echo get_lng($_SESSION["lng"], "L0015"); //Edit?></a>
				<a  href="?delete_id=<?php echo $row['ID']; ?>&hidid=<?php echo $urlhidid;?>" title="click for delete" onclick="return confirm('Confirm to delete ?')"><?php echo get_lng($_SESSION["lng"], "L0016"); //Delete?></a>
			<?
			echo "</td ></tr>";
			}
		  ?>
		  
		  
		<tr align="right" valign="middle" height="30" >
       	<td colspan="13" class="lastpg"><div id="pagination"><?php echo displayPaginationBelow($setLimit,$page);?></div></td>
        </tr>
		
		</tbody>
     </table>
	
			<?php	
	}
	else
	{
		?>
        <div class="col-xs-12">
        	<div class="alert alert-warning" align ="center">
            <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ......
            </div>
        </div>
        <?php
	}
	
?>
            </div>
        </div>
        </div>

<div id="footerMain1">
	<ul>
      <!--
     
          
	 -->
      </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2020 DENSO . All rights reserved  
	
  </div>
</div>
</div>
	</body>
</html>
