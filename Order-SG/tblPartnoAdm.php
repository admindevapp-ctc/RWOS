<? session_start() ?>
<?
if(isset($_SESSION['cusno']))
{       
		$_SESSION['cusno'];
		$_SESSION['cusnm'];
		$_SESSION['cusad1'];
		$_SESSION['cusad2'];
		$_SESSION['cusad3'];
		$_SESSION['type'];
		$_SESSION['zip'];
		$_SESSION['state'];
		$_SESSION['phone'];
		$_SESSION['password'];
		

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$cusad1=$_SESSION['cusad1'];
	$cusad2=$_SESSION['cusad2'];
	$cusad3=$_SESSION['cusad3'];
	$type=$_SESSION['type'];
	$zip=$_SESSION['zip'];
	$state=$_SESSION['state'];
	$phone=$_SESSION['phone'];
	$password=$_SESSION['password'];
	
   
}else{	
header("Location: login.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<html>
	<head>
    <title>Denso Ordering System</title>
   	
    


<link rel="stylesheet" type="text/css" media="screen"  href="css/ui-lightness/jquery-ui-1.7.2.custom.css">
<link rel="stylesheet" type="text/css" media="screen"  href="css/dnia.css">
<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/jquery.searchFilter.css" />
<script src="lib/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.2.custom.min.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>


<style>
/*  2 column About Denso  */


#isi1{
	margin: 0;
	padding:0;
	width: 800px;
	font-size: 11px;
	color: #000000;
	background-color: #FFF;
}

#twocolLeft1{
	float:left;
	background-color: #FFF;
	width: 171px;
	margin-top: 0px;
	padding-top: 0px;
	padding-left: 0px;
    height: 550px;
	background-image: url(images/HBG.png);
	background-repeat: repeat-x;
	background-position: 0px 0px;
	
}
#twocolRight1{
	float:right;
	width:570px;
	background-color: #FFFFFF;
	padding-top: 10px;
	padding-left: 5px;
	margin-top: 0px;
	margin-bottom: 0px;
	margin-right:0px;
	margin-left: 10px;
	text-align: left;
}




.ui-tabs-nav li {position: relative;}
.ui-tabs-selected a span {padding-right: 10px;}
.ui-tabs-close {display: none;position: absolute;top: 3px;right: 0px;z-index: 800;width: 16px;height: 14px;font-size: 10px; font-style: normal;cursor: pointer;}
.ui-tabs-selected .ui-tabs-close {display: block;}
.ui-layout-west .ui-jqgrid tr.jqgrow td { border-bottom: 0px none;}
#list2{
	font-size: 10px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
 .ui-jqgrid-title { height:20px; }
 .ui-jqgrid tr{
	font-size: 11px;
	font-weight: bold;
	
 }
</style>
     
<script type="text/javascript">
$(function() {
	jQuery("#list2").jqGrid({
   	url:'gettblpartno.php',
	datatype: "json",
 	colNames:['Part Number','Description', 'Class', 'Type'],
 	colModel:[
   		{name:'ITNBR',index:'ITNBR', width:100},
   		{name:'ITDSC',index:'ITDSC', width:300},
   		{name:'ITCLS',index:'ITCLS', width:50},
		{name:'ITTYP',index:'ITTYP', width:50}
    ],
    height:'auto',
   	rowNum:15,
   	rowList:[15,30,45, 60, 600],
   	pager: '#pager2',
   	sortname: 'ITNBR',
    viewrecords: true,
    sortorder: "asc",
    caption:"Denso Part Number List"
});

	jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false, search:true}); 
	
	
	});
</script>


	</head>
	<body >
    

	   
	   
   		<div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
              
<ul>  
			<?
			   	if($type=='a'){ 
            		echo "<li><a href=\"mainadm.php\" target=\"_self\">Administration</a></li>";
                  }else{
  					echo "<li ><a href=\"main.php\" target=\"_self\">Ordering</a></li>";
                  }
			?>
  				<li><a href="Profile.php" target="_self">User Profile</a></li>
  				<li id="current"><a href="Table" target="_self">Table Part</a></li>
  				<li ><a href="logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi1">
   	<p>
    <br />
               
        <div align="center">   
          <table id="list2"></table>
           <div id="pager2"></div> 
   		</div>
        <p>
        <div id="footerMain1">
	<ul>
      
     
          </ul>

    <div id="footerDesc">

	<p>
	Copyright & 2014 DENSO Thailan. All rights reserved  
	
  </div>
</div>

	</body>
</html>
