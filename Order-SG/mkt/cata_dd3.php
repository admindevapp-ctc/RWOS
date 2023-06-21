<?
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
		//$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<?php

require "cata_config.php";
?>



<html>

<head>
<meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" /> 
<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value; 
self.location='cata_cataloguemain.php?cat=' + val ;
}
function reload3(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value; 
var val2=form.subcat.options[form.subcat.options.selectedIndex].value; 

self.location='cata_cataloguemain.php?cat=' + val + '&cat3=' + val2 ;
}

</script>
</head>

<body>

<?php


///////// Getting the data from Mysql table for first list box//////////
$quer2="SELECT DISTINCT CarMaker FROM catalogue order by CarMaker"; 
///////////// End of query for first list box////////////

/////// for second drop down list we will check if category is selected else we will display all the subcategory///// 
$cat=$_GET['cat']; // This line is added to take care if your global variable is off
if(isset($cat) and strlen($cat) > 0){
$quer="SELECT DISTINCT ModelName FROM catalogue where CarMaker LIKE '$cat%' order by ModelName"; 
}else{$quer="SELECT DISTINCT ModelName FROM catalogue order by ModelName"; } 
////////// end of query for second subcategory drop down list box ///////////////////////////


/////// for Third drop down list we will check if sub category is selected else we will display all the subcategory3///// 
$cat3=$_GET['cat3']; // This line is added to take care if your global variable is off
if(isset($cat3) and strlen($cat3) > 0){
$quer3="SELECT DISTINCT ModelCode FROM catalogue where ModelName LIKE '$cat3' order by ModelCode"; 
}else{$quer3="SELECT DISTINCT ModelCode,id FROM catalogue order by ModelCode"; } 
////////// end of query for third subcategory drop down list box ///////////////////////////


echo "<form method=post name=f1 id='orderdatefrom' action='cata_searchidandname.php'>";
//////////        Starting of first drop downlist /////////

echo "<select name='cat' onchange=\"reload(this.form)\"><option value='' disabled='disabled' selected='selected'>Select Maker</option>";
foreach ($dbo->query($quer2) as $noticia2) {
if($noticia2['CarMaker']==@$cat){echo "<option selected value='$noticia2[CarMaker]'>$noticia2[CarMaker]</option>"."<BR>";}
else{echo  "<option value='$noticia2[CarMaker]'>$noticia2[CarMaker]</option>";}
}
echo "</select>";

//////////////////  This will end the first drop down list ///////////

//////////        Starting of second drop downlist /////////
echo "<select name='subcat' onchange=\"reload3(this.form)\"><option value=''disabled='disabled' selected='selected'>Select Model Name</option>";
foreach ($dbo->query($quer) as $noticia) {
if($noticia['ModelName']==@$cat3){echo "<option selected value='$noticia[ModelName]'>$noticia[ModelName]</option>"."<BR>";}
else{echo  "<option value='$noticia[ModelName]'>$noticia[ModelName]</option>";}
}
echo "</select>";
//////////////////  This will end the second drop down list ///////////

//////////        Starting of third drop downlist /////////
echo "<select name='subcat3' ><option value=''disabled='disabled' selected='selected'>Select Model Code</option>";
foreach ($dbo->query($quer3) as $noticia) {
echo  "<option value='$noticia[ModelCode]'>$noticia[ModelCode]</option>";
}
echo "</select>";
?>
<!--//////////////////  This will end the third drop down list /////////// -->
<input type=submit value='<?php echo get_lng($_SESSION["lng"], "L0213"); ?>' class='arial11'></form>

</body>
</html>
