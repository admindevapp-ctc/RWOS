<?php 

session_start();
require_once('../../core/ctc_init.php'); // add by CTC

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
	$(document).ready(function() {
		
	if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
		//alert( "This page is reloaded" );
		window.location = window.location.href.split("?")[0];
	} 

        var supno = $("#supref :selected").val();
		$('#ConvExcel').click(function(){
			url= 'supref_gettblXLS.php?supno='+supno,
			window.open(url);	
					
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
			  	$_GET['current']="sup_mainref";
				include("navAdm.php");

			
			  ?>
        </div>
		<?
		  require('../db/conn.inc');
		 
		    $inpsupref= '<select name="supref" id="supref" class="arial11blue" style="width:200px">';
			$inpsupref= $inpsupref .  ' <option value="" selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
			$query="SELECT distinct supmas.supno FROM supmas join supref on supmas.supno = supref.supno and supmas.Owner_Comp = supref.Owner_Comp where supmas.Owner_Comp='$comp' order by supmas.supno ";
			//echo $query;
            $sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
                $supref=$_GET['supref'];
                $selected = ($hasil["supno"] == $supref) ? "selected" : "";
				$supno=$hasil['supno'];
				$inpsupref= $inpsupref .  ' <option '.$selected.'  value="'.$supno.'">'.$supno.'</option>';		
				
			}
        	$inpsupref= $inpsupref . ' </select>';
			
		 ?>

        <div id="twocolRight">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
        <tr class="arial11blackbold">
            <td>&nbsp;</td>
            <td width="19%">&nbsp;</td>
            <td width="25%">&nbsp;</td>
            <td width="3%"></td>
            <td width="19%">&nbsp;</td>
            <td width="1%">&nbsp;</td>
            <td width="30%">&nbsp;</td>
        </tr>
        <tr class="arial11blackbold">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="arial11blackbold">
            <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
            <td colspan="6" class="arial21redbold"> Supplier & Customer Reference Master</td>
            </tr>
        <tr class="arial11blackbold">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
                
        </table>     

        <form name ="search" method="get">
            <fieldset style="width:98%">
             <legend> &nbsp;Search Information</legend>
                <table width="97%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11blackbold">
                    <td width="16%" ><div align="right"><span class="arial12BoldGrey"><?php  echo get_lng($_SESSION["lng"], "L0451"); ?></span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="15%"><span class="arial12Bold"><? echo $inpsupref ?></span></td>
                    <td width="2%"></td>
                    <td width="19%"><input type="submit" name="button" id="button" value="Search" class="arial11"></td>
                    <td width="1%">&nbsp;</td>
                    <td width="45%">&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td >&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </table>
            </fieldset>
        </form>
       
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                <th scope="col">&nbsp;</th>
                <th width="90" scope="col">
					<a href="supref_import.php" id="ImportExel" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0005"); ?></font>
                        </div>
                    </a>
                </th>
				<th width="10"></th>
                <th width="140" scope="col">
                    <div id="ConvExcel" style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;cursor:pointer;'>
                        <img src="../images/excel.jpg" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                        <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0346"); ?></font>
                    </div>
                </th>
				<th width="10"></th>
                <th width="90" scope="col">
					<a href="sup_refmas.php?action=add" id="new" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/new.png" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0006"); ?></font>
                        </div>
                    </a>
                </th>
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
            </table>

        <!--
                <a href="#" id="ConvExcel"><img src="../images/export.png" width="80" height="20" border="0"></a>
                <a href="sup_refmas.php?action=add" id="new"><img src="../images/newtran.png" width="80" height="20"></a>
        -->
            <table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
        <tr class="arial11whitebold" bgcolor="#AD1D36" >
            <th width="20%" height="30" scope="col">Company Code</th>
            <th width="20%" height="30" scope="col">Supplier Number</th>
            <th width="20%" height="30" scope="col">Customer Number</th>
            <th width="20%" scope="col">Shipto</th>
            <th width="20%" scope="col">Action</th>
            </tr>
        <?
                require('../db/conn.inc');
                
                $per_page=10;
                $num=5;
                
                $pages = ceil($count/$per_page);
                $page = $_GET['page'];
                if($page){ 
                    $start = ($page - 1) * $per_page; 			
                }else{
                    $start = 0;	
                    $page=1;
                }
                $criteria=" where Owner_Comp='$comp' ";
                $xsupref=$_GET["supref"];
                if(isset($xsupref)){
                    if(trim($xsupref)!=''){
                      $criteria .= ' and supno="'.$xsupref.'"';
                      
                    }
                }
              $query="select * from supref". $criteria;
              $sql=mysqli_query($msqlcon,$query);
              //echo  $query;


                $count = mysqli_num_rows($sql);
                $query1="select * from supref $criteria order by supno LIMIT $start, $per_page"; // edit by CTC
                $sql=mysqli_query($msqlcon,$query1);	
                //echo "<br/>".$query1;
                if( ! mysqli_num_rows($sql) ) {
                    echo "<tr height=\"30\"><td colspan=\"5\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... */ . "</td></tr>";
                }
                    while($hasil = mysqli_fetch_array ($sql)){
                        $vowner=$hasil['Owner_comp'];
                        $vsupno=$hasil['supno'];
                        $cusno=$hasil['Cusno'];
                        $shipto=$hasil['shipto'];
                        /*
                        $valias=$hasil['alias'];
                        if($valias=='')$valias='-';
                        $vtype=trim($hasil['Custype']);
                        */
                    echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vowner."</td><td>".$vsupno."</td><td>".$cusno."</td><td>".$shipto."</td>" ;
                   
                    echo "<td class=\"lasttd\">";
                    echo "<a href='sup_refmas.php?action=edit&supno=".$vsupno."&cusno=".$cusno."'  > <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a> ";
                    echo "<a href='sup_updrefmas.php?action=delete&id=".$vsupno."&cusno=".$cusno."' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                    echo "<td ></tr>";
                    
                    }
                    
                    require('pager.php');
                if($count>$per_page){		
                    echo "<tr height=\"30\"><td colspan=\"6\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
                    //echo $query;
                    $fld="page";
                    paging($query,$per_page,$num,$page);
                    echo "</div></td></tr>";
                }
                
                
                ?>
        
        <tr>
            <td colspan="6"  align="right" class="lasttd" >
                <img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> =edit</span>
                <img src="../images/delete.png" width="20" height="20"><span class="arial11redbold"> =delete</span>
            </td>
            </tr> 
        </table>
        <div id="result" class="arial11redbold" align="center"> </div>
<p>            
<div id="footerMain1">
	<ul>
      <!--
     
          
	 -->
      </ul>

    <div id="footerDesc">

	<p>
	Copyright &copy; 2023 DENSO . All rights reserved  
	
  </div>
</div>
</div>
	</body>
</html>
