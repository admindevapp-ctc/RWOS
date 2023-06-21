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
	$query = "DELETE FROM `awscusmas_temp` WHERE `Owner_Comp` = '$comp';";
	mysqli_query($msqlcon,$query);
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
	.loading-screen {
		position: absolute;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		color: white;
		font-size: 24px;
	}

	.loading-text {
		margin-left: 10px;
	 
	}
	.pageing button {
	  background-color: #ffffff;
	  color: #ad1d36;
	  border: 1px solid #ad1d36;
	  padding: 4px 8px;
	  margin: 0 2px;
	  font-size: 12px;
	  font-weight: bold;
	  cursor: pointer;
	  transition: all 0.2s ease-in-out;
	}

	.pageing button:hover:not(.active) {
	  background-color: #ad1d36;
	  color: #ffffff;
	  border: none;
	}

	.pageing button.active {
	  background-color: #ad1d36;
	  color: #ffffff;
	  border: none;
	}

	.pageing button.active:hover {
	  background-color: #ffffff;
	  color: #ad1d36;
	  border: 1px solid #ad1d36;
	}
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


<!--<script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>-->
<script src="DataTables/datatables.min.js"></script>

<script type="text/javascript">
var obj_data = {};

async function update_table() {
	$('.result').html('<tr><td colspan="6"><div align="center" width="100%">Validating data please wait...<img src="../images/35.gif" height="20"/></div></td></tr>');
	if($('.err_txt').length >= 1){
		$('.result').html('');
	}
	$.ajax({
		type: 'POST',
		url: 'aws_import_table.php',
		data: {},
		dataType: 'json',
		success:await function(data) {
			$('.result').html('');
			html_table(data);
			obj_data = data;
			html_pg(data);

		}
	});
}

function html_table(data={},page='1'){
	let html_row = '';
	let html_error_header = '';
	let error_st = '';
	$.each( data, function( k, v ) {
		$.each( data[k], function( k1, v1 ) {
			let error 	= v1['error'];
			if(v1['error'] != ''){
				error_st = '1';
				html_error_header = `<th width='10%' scope='col'>Error</th>`;
			}
		});
	});
	$.each( data[page], function( k, v ) {
		let cusno1 	= v['cusno1'];
		let ship_to_cd1 	= v['ship_to_cd1'];
		let cusno2 	= v['cusno2'];
		let ship_to_cd2 	= v['ship_to_cd2'];
		let ship_to_adrs1 	= v['ship_to_adrs1'];
		let ship_to_adrs2 	= v['ship_to_adrs2'];
		let ship_to_adrs3 	= v['ship_to_adrs3'];
		let mail_add1 	= v['mail_add1'];
		let mail_add2 	= v['mail_add2'];
		let mail_add3 	= v['mail_add3'];
		let error 	= v['error'];
		let html_error = '';
		if(error_st == '1'){
			html_error = `<td>${error}</td>`;
		}
		
		html_row +=`
			<tr class='arial11black'>
				<td align="center">${cusno1}</td>
				<td align="center">${ship_to_cd1}</td>
				<td align="center">${cusno2}</td>
				<td align="center">${ship_to_cd2}</td>
				<td>${ship_to_adrs1}</td>
				<td>${ship_to_adrs2}</td>
				<td>${ship_to_adrs3}</td>
				<td>${mail_add1}</td>
				<td>${mail_add2}</td>
				<td>${mail_add3}</td>
				${html_error}
			</tr>
		`;
	});
	
	if(error_st != '1'){
		html_button = `
			<span class=\"arial21redbold\">Do you want to Upload data?</span>
                    
			<form method="POST" enctype="multipart/form-data" action="db/replace-all-awscusmas.php">
				<input type="submit" name="yesbtn" value="Yes">
				<input type="button" onclick='hide()' value="No">
			</form>
			
		`;
	}else{
		html_button = `
		<span class=\"arial21redbold\">Error found.  Cannot proceed.</span>
		<br><br><input type ='button' onclick='hide()' value='upload again'><br><br>`;
	}
	let html = `
		${html_button}
	
	
		
		
		
		<table width='100%' border='1' class='idtable' cellspacing='0' cellpadding='0'>
			<tr class='arial11whitebold' bgcolor='#AD1D36'>
				<th width='10%' scope='col'>1st Customer</th>
				<th width='5%' scope='col'>Shipto 1 st</th>
				<th width='10%' scope='col'>2nd customer</th>
				<th width='5%' scope='col'>Shipto 2 nd</th>
				<th width='10%' scope='col'>Ship to address1</th>
				<th width='10%' scope='col'>Ship to address2</th>
				<th width='10%' scope='col'>Ship to address3</th>
				<th width='10%' scope='col'>e-mail1</th>
				<th width='10%' scope='col'>e-mail2</th>
				<th width='10%' scope='col'>e-mail3</th>
				${html_error_header}
			</tr>
			${html_row}
		</table>
	`;
	
	
	$('.result').html('');
	$('.result').html(html);

}
function html_pg(data, page = 1) {
	  $('.pageing').html('');
	  let html = '';
	  html += `<button class="pgback" onclick="paging(${page - 1})">Back</button>`;

	  let numPages = Object.keys(data).length;
	  let maxPages = 3;
	  let halfMaxPages = Math.floor(maxPages / 2);

	  let startPage = Math.max(page - halfMaxPages, 1);
	  let endPage = Math.min(startPage + maxPages - 1, numPages);

	  if (startPage > 1) {
		html += '<button onclick="paging(1)">1</button>';
		if (startPage > 2) {
		  html += ' << ';
		}
	  }

	  for (let i = startPage; i <= endPage; i++) {
		let activeClass = i === page ? 'active' : '';
		html += `<button onclick="paging(${i})" class="${activeClass}">${i}</button>`;
	  }

	  if (endPage < numPages) {
		if (endPage < numPages - 1) {
		  html += ' >> ';
		}
		html += `<button onclick="paging(${numPages})">${numPages}</button>`;
	  }

	  html += `<button class="pgnext" onclick="paging(${page + 1})">Next</button>`;

	  $('.pageing').html(html);
	  if (page == 1) {
		$('.pgback').prop('disabled', true);
	  } else {
		$('.pgback').prop('disabled', false);
	  }
		  
	  if (page == numPages) {
		$('.pgnext').prop('disabled', true);
	  }else{
		$('.pgnext').prop('disabled', false);

	  }
	}
	
	
function paging(page){
	current_page = page;
	console.log(current_page);
	html_table(obj_data,page);
	html_pg(obj_data,page)
}

$(function() {
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	
	const get_param = urlParams.get('msg')
	
	if(get_param !== null){
		update_table();
	}

    $.urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results == null){
			return null;
			}
			else {
			return decodeURI(results[1]);
			}
		}

	var msg = $.urlParam('msg');
    if(msg != null){
        $("#frmimport").hide();
        $("#formatexcel").hide();
    }

	$('#frmimport').submit(function(){
		if($('#userfile').val()==''){
			alert('Please choose file!');
			return false;
		}
        var filepath = $('#userfile').val();
        var ext = $('#userfile').val().split('.');
		//alert(ext[1]);
		if(ext[1].toLowerCase() != "xls"){
			alert("<?php echo get_lng($_SESSION["lng"], "E0079"); ?>");
			return false;
		}else{
			$('body').prepend('<div class="loading-screen"><div class="spinner"></div><span class="loading-text">Data validation ongoing. Please wait...</span></div>');
		} 
	});


});

function hide(){
    window.location.href = "aws_import.php";
}
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
			  	$_GET['current']="awscusmas";
				include("navAdm.php");

			
			  ?>
        	</div>
		
			<div id="twocolRight">
            <span class="arial12Bold" height="40">2 <sup>nd</sup> Customer MA </span><br/><br/>
			<span height="40" id="formatexcel">Download format excel here : <a href="prototype/awscusmas.xls" target="_blank" download ><img src="../images/csv.jpg" width="16" height="16" border="0"></a></span>
            <form method="POST" enctype="multipart/form-data" name="uploadForm" id="frmimport" action='db/upload-awscusmas.php' >
            <table class='arial11'>
				<tr height="40">
                    <td>Upload</td>
                    <td>:</td>
                    <td><input type="file" size="45"  id= "userfile" name="userfile"></td>
                </tr>
				<tr height="40">
                    <td colspan="3">
                        <span class="arial21redbold">Please upload your excel file(.xls)</span>
                    </td>
                </tr>
				<tr height="40">
                    <td colspan="3">
                        <span class="arial21redbold">Note : The data will delete all and replace all, Please make sure all pending orders has been approved before uploading.</span>
                    </td>
                </tr>
				<tr height="40">
                    <td></td>
                    <td></td>
                    <td>
                        <input name="submit" type="submit" value="Submit"> 
                        <input type="reset" value="Reset">
                    </td>
                </tr>
            </table><br/>
            </form>
			<div class="result">
			</div>
			<div class="pageing" style="width:97%;text-align:right;margin-top: 13px;"></div>

			

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