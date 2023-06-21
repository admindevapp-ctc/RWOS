<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC

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
		if($type!='a'){
			header("Location:../main.php");
		}
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


.theme-1 {
  background: red !important;
  border-color: red;
}

.theme-2 {
  background: green!important;
  border-color: green;
}

.theme-3 {
  background: orange!important;
  border-color: orange;
}

 </style>


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
    <style>
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		button .btn{
			height: 18px;
			width: 18px;
		}
	</style>
    
     
<script type="text/javascript">

$(function() {
	var vaction="";
	var res="";
	var setday=$( "#leadsetday" ),
     startdate=$( "#startdate" ),	
     enddate=$( "#enddate" ),	
	 status=$( "#leadtimestatus" ),	
	 ordtype = $( "#ordtype" );
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			width: 500,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				"save change": function() {
					
                    var vsetday=parseInt(setday.val());
                    var vstartdate=startdate.val();
                    var venddate=enddate.val();
                    var vstatus=status.val();
                    var vordtype=ordtype.val();
                    var edata;
						//check value
						if(vsetday > 712 || vsetday <= 0){
							alert("the data shold be between 1 to 712")
						}
						else{

							edata="setday="+vsetday +"&ordtype="+vordtype +"&startdate="+vstartdate +"&enddate="+venddate +"&status="+vstatus+"&action="+vaction;
							// alert(edata);


								$.ajax({
									type: 'GET',
									url: 'leadtimeaction.php',
									data: edata,
									success: function(data) {
										//console.log(data);
										$( "#dialog-form").dialog( "close" );
										window.location.reload();
									}
								});
						}
				
			    },
				Cancel: function() {
					$( "#dialog-form").dialog( "close" );
					window.location.reload();
				}
			}
		});

	
	
	$( ".edit" ).click(function() {
				pos =$( this ).attr("id");
				//alert(pos);
              //  id=".$vcomp."||".$type."||".$vsetday."||".$vendday."||".$status."
				var xdata=pos.split("||");
				var comp=xdata[0];
				var type=xdata[1];
				var setday=xdata[2];
				var endday=xdata[3];
				var status=xdata[4];
				
				$('#leadsetday').val(setday);
				$('#startdate').val(setday);
                $('#enddate').val(endday);
				$('#leadtimestatus').val(status);
				$('#ordtype').val(type);
				
				vaction='edit';
					$( "#dialog-form" ).attr("title","change record");
                    //Normal
                    var head;
                    if(type == "N"){
                        head = "Normal Order";
                        $('#rowdate').show();
                        $('#rowurgent').hide();
                        $(".ui-dialog-titlebar").removeClass("theme-1");
                        $(".ui-dialog-titlebar").addClass("theme-2");
                        $(".ui-dialog-titlebar").removeClass("theme-3");
                    }
                    else if(type == "A"){
                        head = "A";
                        $('#rowdate').show();
                        $('#rowurgent').hide();
                        $(".ui-dialog-titlebar").removeClass("theme-1");
                        $(".ui-dialog-titlebar").removeClass("theme-2");
                        $(".ui-dialog-titlebar").addClass("theme-3");
                    }
                    else if(type == "R"){
                        head = "Request Due Date order";
                        $('#rowdate').show();
                        $('#rowurgent').hide();
                        $(".ui-dialog-titlebar").removeClass("theme-1");
                        $(".ui-dialog-titlebar").removeClass("theme-2");
                        $(".ui-dialog-titlebar").addClass("theme-3");
                    }
                    else if(type == "U"){
                        head = "Urgent order";
                        $('#rowurgent').show();
                        $('#rowdate').hide();
                        $(".ui-dialog-titlebar").addClass("theme-1");
                        $(".ui-dialog-titlebar").removeClass("theme-2");
                        $(".ui-dialog-titlebar").removeClass("theme-3");
                        
                    }
					$("span.ui-dialog-title").text(head); 
					$( "#dialog-form" ).dialog( "open" );
						
			
			});

			
	});
	function onlyNumberKey(evt) {
              
			  // Only ASCII character in that range allowed
			  var ASCIICode = (evt.which) ? evt.which : evt.keyCode
			  if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
				  return false;
			  return true;
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
			  	$_GET['current']="Leadtime";
				include("navAdm.php");
			  ?>
        </div>
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
    <td colspan="6" class="arial21redbold">Leadtime Setting</td>
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

<table width="100%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11white" bgcolor="#AD1D36" >
  	<th width="20%" scope="col">Order Type</th>
  	<th width="20%" scope="col">Standard Leadtime(Day)</th>
    <th width="20%" scope="col">Cut Of Time</th>
    <th width="20%" scope="col">Status</th>
    <th width="20%" scope="col">Action</th>
    </tr>
    
     <?
		  require('../db/conn.inc');
		  
		  $per_page=10;
		  $num=5;
	$query="select * from duedate where Owner_Comp='$comp'";  
	$sql=mysqli_query($msqlcon,$query);
	$count = mysqli_num_rows($sql);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
	
	$query1="select * from duedate where Owner_Comp='$comp' LIMIT $start, $per_page"; 
	$sql=mysqli_query($msqlcon,$query1);	
    
			while($hasil = mysqli_fetch_array ($sql)){
				$vcomp=$hasil['Owner_Comp'];
				$vordtype=$hasil['ordtype'];
				$vsetday=$hasil['setday'];
				$vendday=$hasil['endday'];
				$vmenu_sts=$hasil['menu_sts'];
                switch($vordtype){
					case "N":
						$type="Normal Order";
						break;
					case "R":
						$type="Requested Due Dat Order";
						break;	
					case "A":
						$type="A";
						break;	
					case "U":
						$type="Urgent Order";
						break;	
						
				}	
                switch($vmenu_sts){
					case 0:
						$status="Not show menu";
						break;
					case 1:
						$status="Show menu";
						break;	
				}	

		    echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$type."</td><td>"
            .$vsetday."</td><td>".$vendday."</td><td>".$status."</td>" ;
			
			echo "<td class=\"lasttd\">";
			echo "<a href='#' class='edit' id='".$vcomp."||".$vordtype."||".$vsetday."||".$vendday."||".$vmenu_sts."'> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
			
			echo "<td ></tr>";
			
			}
			
			require('pager.php');
		if($count>$per_page){		
		  	echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
		  	//echo $query;
		  	$fld="page";
		  	paging($query,$per_page,$num,$page);
		  	echo "</div></td></tr>";
		}
		
		
		  ?>
 
 <tr>
    <td colspan="5"  align="right" class="lasttd" ><img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span></td>
    </tr> 
</table>
<div id="result" class="arial11redbold" align="center"> </div>
<p>

<div id="dialog-form" >
<form>
<table width="450" border="0">
  <tr>
    <td> <input type="hidden" id="ordtype" name="ordtype"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="rowdate">
    <td style="text-align:right;"><span class="arial11redbold" style="margin-right:15px;">Standard Leadtime :</span> </td>
    <td>
		<div class="row" style="display:flex">
			<input type="number" name="leadsetday" id="leadsetday"  min="1" max="712" maxlength="3" onkeypress="return onlyNumberKey(event)" />
			<div style="padding: 3 10px;" class="arial11redbold"> Day(s)</span></td>
		</div>
	<td colspan="2" ></td>
  </tr>
  <tr id="rowurgent" style="">
    <td><span class="arial11redbold">Disable time</span> :</td>
    <td>
        <input type="time" id="startdate"/>
    </td>
    <td><span class="arial11redbold">Enable time</span> :</td>
    <td>
        <input type="time" id="enddate"/>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:right;"><span class="arial11redbold" style="margin-right:15px;">Status  :</span></td>
    <td>
    <select name="leadtimestatus" id="leadtimestatus" class="arial11blackbold" style="width: 125px" >
        <option value="0">not show menu</option>
        <option value="1">show menu</option>
    </select>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   </table>
            
</form>
 </div>





              
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
