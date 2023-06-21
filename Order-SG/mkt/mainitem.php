<? session_start() ?>
<?
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
		if($type!='a')header("Location:../main.php");
	 }else{
		   echo "<script> document.location.href='../../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}
?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

 <style type="text/css">
<!--
#example tbody {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
}
#example thead{
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	
}
#example thead tr th{
	border-bottom-width: 2px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
	background-image: url(images/thbg.png);
	border-top-width: 1px;
	border-top-style: solid;
	border-top-color: #B00;
}

#example tbody tr td table tr td{
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #B00;
		
}

#pagination a 
{
	list-style: none;
	margin-right: 5px;
	padding:5px;
	color:#0063DC;
	text-decoration: none;
	background-color: #E8E8E8;
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
-->
    </style>


<link rel="stylesheet" href="../css/base/jquery.ui.all.css">
	<script src="../lib/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" language="JavaScript" src="../lib/jquery-ui-1.8.5.custom.min.js"></script>
	<link rel="stylesheet" href="../css/demos.css">  
 <script type="text/javascript" charset="utf-8">
			var globalfield="";
			var globalsort="";
			var globalsearch="";
			var globalchoose="";
			var globaldesc=""
			$(document).ready(function() {
			var choose = $( "#OptChoose" ),
			field= $( "#OptField" ),
			desc= $( "#description" ),
			allFields = $( [] ).add(field).add(choose).add(desc),
			tips = $( ".validateTips" );
									   
				updatetbl('','','','','');
				//$('#thnprd').change(function(){updatetbl('','','','','')});		
				
				$('#Partno').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="ITNBR";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="ITNBR";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				
				$('#PartName').click(function(){
						if($(this).hasClass('PartAsc')){					
							$(this).removeClass('PartAsc');
							$(this).addClass('PartDesc');
							globalfield="Product";
							globalsort="asc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}else{
							$(this).removeClass('PartDesc');
							$(this).addClass('PartAsc');
							globalfield="Product";
							globalsort="desc";
							updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
						}
				 })
				
				$('#ConvExcel').click(function(){
						url= 'gettblItemXLS.php',
						window.open(url);	
					
			  })
			  
			  function updatetbl(namafield, order, searchfield, choose, desc){
					
					
						
						var edata="namafield="+namafield+"&sort="+order+"&search="+searchfield+"&choose="+choose+"&description="+desc;
						//alert(edata);
						$('#result1').empty().html('<div align="center"><img src="images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'getItemAll.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'getitempgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
				}
			
		$("#search").click(function(){
			$( "#dialog-form" ).dialog( "open" );							
		})
	
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height:200,
			width: 450,
			modal: true,
			buttons: {
				"Search": function() {
				var data;					
				var edata;
				var bValid;
				var vfield=$('#OptField').val();
				var vchoose=$('#OptChoose').val();
				var vdesc=$('#description').val();
				if(vfield==""){
					data="Please choose field to search";
					$('#optfield').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					bValid="";
					return false;
				}else{
					$('#optfield').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				if(vchoose==""){
					data="Please choose criteria to search";
					$('#optchoose').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					return false;
					bValid="";
				}else{
					$('#optchoose').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				if(vdesc==""){
					data="Please fill description to search";
					$('#description').addClass( "ui-state-error" );
					$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
					return false;
					bValid="";
				}else{
					$('#description').removeClass( "ui-state-error" );
					$( ".validateTips" ).text("Search Option").removeClass( "ui-state-highlight" );
					bValid="1";
				}
				
				
				
			   if ( bValid =="1") {
					globalsearch=vfield;
					globalchoose=vchoose;
					globaldesc=vdesc;	
					updatetbl(globalfield,globalsort,globalsearch,globalchoose,globaldesc);
									
					}
				},
				Cancel: function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});
									   
								   
		   });
			
			 function paging(x){
						var edata="page="+x+"&namafield="+globalfield+"&sort="+globalsort+"&search="+globalsearch+"&choose="+globalchoose+"&description="+globaldesc;;
						$('#result1').empty().html('<div align="center"><img src="images/35.gif" height="20"/></div>');
						$.ajax({
							type: 'GET',
							url: 'getItemAll.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#result1').html(data);
								}
						})
						
						$.ajax({
							type: 'GET',
							url: 'getItempgall.php',
							data: edata,
							success: function(data) {
								//alert(data);
								$('#pagination').html(data);
								}
						})
						
				

			 }
				
	</script>

    
    
 
	</head>
	<body >
   		<div id="header">
        <img src="../images/denso.jpg" width="206" height="54" />
        </div>
		<div id="mainNav">
       
        
			<ul>  
  				<li id="current"><a href="mainRFQ.php" target="_self">Marketing</a></li>
				<li><a href="Profile.php" target="_self">User Profile</a></li>
   				<li ><a href="../logout.php" target="_self">Log out</a></li>
  				  				
			</ul>
	</div> 
    	<div id="isi">
        
        
        <div id="twocolRight1">
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="22%">Customer Number</td>
    <td width="2%">:</td>
    <td width="26%"><? echo $cusno ?></td>
    <td width="4%"></td>
    <td width="20%">Customer Name</td>
    <td width="2%">:</td>
    <td width="25%"><? echo $cusnm ?></td>
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
    <td></td>
    <td>&nbsp;</td>
	
    <td colspan="5">
	<table width="97%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td width="150">
		</td>	
	    <td>&nbsp;</td>
	    </tr>
	  </table></td>
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
        <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="middle" class="arial11" height="30">
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th valign="middle" scope="col"  align="right"><? 
	if($type!="v") echo "<a href=\"imbm008pr.php\"><img src=\"../images/importxls.gif\" width=\"80\" height=\"20\"></a>";
	?>
    </th>
    <th colspan="2" scope="col" align="right"><a href="#" id="search" ><img src="../images/search.png" width="101" height="25" border="0"></a><a href="#" id="ConvExcel"><img src="../images/export.png" width="101" height="25" border="0"></a></th>
    </tr>
  <tr valign="middle" class="arial11">
    <th width="20%" scope="col">&nbsp;</th>
    <th width="20%" scope="col">&nbsp;</th>
    <th width="26%" valign="middle" scope="col"></th>
    <th width="14%" scope="col"></th>
    <th width="20%" scope="col" align="right"></th>
  </tr>
</table>



<table cellpadding="0" cellspacing="0" border="0" class="tbl1" id="example" width="97%">
<thead >
		
		<tr align="center" height="20" >
			<th width="15%"><div id="Partno"><div class="PartAsc"><a href="#" >Part Number</a></div></div></th>
           <th width="20%">Part Name</th>
      <th width="15%"><div id="PartName">
        <div class="PartAsc"><a href="#" >Product</a></div></div></th>
			<th width="15%">Sub Product</th>
			<th width="10%">Lot Size</th>
            <th width="8%">Assy</th>
            <th width="8%">Category</th>
			<th width="8%" class="lastth">action</th>
		</tr>
	</thead>
	<tbody>
		<tr>
        <td colspan="8" id="result1"></td>
        </tr>
        
        <tr align="right" valign="middle" height="30" >
       	  <td colspan="8" class="lastpg"> <div id="pagination"> </div></td>
        </tr>
        
	</tbody>
	
</table>


<div id="loading" style="display:none;" align="center"><img src="images/35.gif" width="64" height="64" /></div>


        <div id="dialog-form" title="Search" >
				<p class="validateTips">Search Option</p>
		  <select name="field" id="OptField" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="partno" >Part Number</option>
        	<option value="ITDSC">Description</option>
            <option value="product">Product</option>
        </select>
         <select name="choose" id="OptChoose" class="arial11blackbold" style="width: 100px">
        	<option value="" ></option>
	       	<option value="eq" >equals</option>
        	<option value="like">contains</option>
          </select>
			
        <input type="text" name="descrip" id="description"  class="arial11blackbold" maxlength="30" size="30">
     

                
                </div>   
                </div> 
                <p><br>
<div id="footerMain1">
	<ul>
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
  </div>
</div>

	</body>
</html>
