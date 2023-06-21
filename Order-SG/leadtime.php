<?php 

session_start();
require_once('./../core/ctc_init.php'); // add by CTC

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
		$owner_comp = ctc_get_session_comp(); // add by CTC
	 }else{
		echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
	header("Location:../login.php");
}

include('chklogin.php');
include "crypt.php";

?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="css/dnia.css">
	</style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
<style>
.theme-1 {
  background: green !important;
  border-color: green;
}
</style>
<script src="lib/jquery-1.4.2.js"></script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
	<script src="lib/jquery.bgiframe-2.1.2.js"></script>
	<script src="lib/jquery.ui.core.js"></script>
	<script src="lib/jquery.ui.widget.js"></script>
	<script src="lib/jquery.ui.mouse.js"></script>
	<script src="lib/jquery.ui.button.js"></script>
	<script src="lib/jquery.ui.draggable.js"></script>
	<script src="lib/jquery.ui.position.js"></script>
	<script src="lib/jquery.ui.resizable.js"></script>
	<script src="lib/jquery.ui.dialog.js"></script>
	<script src="lib/jquery.effects.core.js"></script>
    <script src="lib/jquery.ui.datepicker.js"></script>

	<link rel="stylesheet" href="css/demos.css">   
    <script type="text/javascript">
	
$(function() {
    var vaction="";
    var res="";
    var setday=$( "#leadsetday" ),
     startdate=$( "#startdate" ),   
     enddate=$( "#enddate" ),   
     //status=$("#leadtimestatus:checked"), 
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
                "Save change": function() {
                    var vsetday=parseInt(setday.val());
                    var vstartdate=startdate.val();
                    var venddate=enddate.val();
                    var vstatus=$("#leadtimestatus:checked").val();
                    var vordtype=ordtype.val();
                    var edata;
                        //check value
                        if(setday.val().length <= 0){
                            alert("please input Leadtime setday");
                            return false;
                        }
                        else if(vsetday > 712 || vsetday <= 0){
                            if(vsetday > 712){
                                setday.val("712");
                                return true;
                            }
                            else{
                                alert("the data shold be between 1 to 712");
                                return false;
                            }
                        }
                        if(vstatus == null){
                            alert("Please choose to check or not check the holiday.");
                            return false;
                        }
                        edata="setday="+vsetday +"&ordtype="+vordtype +"&startdate="+vstartdate +"&enddate="+venddate +"&status="+vstatus+"&action="+vaction;
                        //alert(edata);
                        $.ajax({
                            type: 'GET',
                            url: 'leadtimeaction.php',
                            data: edata,
                            success: function(data) {
								if(data == ''){
									alert('Leadtime Updated');
									$( "#dialog-form").dialog( "close" );
									window.location.reload();
								}else{
									alert(data);
								}
								
                                
                            }
                        });
                
                },
                Cancel: function() {
                    $( "#dialog-form").dialog( "close" );
                    window.location.reload();
                }
            }
        });

    
    
		$( ".edit" ).click(function() {
			pos =$( this ).attr("id");
			// alert(pos);
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
			$("#leadtimestatus[value='"+status+"']").attr('checked', true);
		   // $('#leadtimestatus').val(status);
			$('#ordtype').val(type);

			vaction='check';
			edata="action="+vaction;
			$.ajax({
				type: 'GET',
				url: 'leadtimeaction.php',
				data: edata,
				success: function(data) {
					// alert(data);
					if(data.toLowerCase().indexOf("error") >= 0){
						alert(data);
					}
					else{
					//edit
						vaction='edit';
						$( "#dialog-form" ).attr("title","change record");
						$(".ui-dialog-titlebar").addClass("theme-1");
						$("span.ui-dialog-title").text("Request Due Date order"); 
						$( "#dialog-form" ).dialog( "open" );
					}
					
				}
			});
		});

		$( ".add" ).click(function() {
			pos =$( this ).attr("id");
			// alert(pos);
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
			$("#leadtimestatus[value='"+status+"']").attr('checked', true);
		   // $('#leadtimestatus').val(status);
			$('#ordtype').val(type);

			vaction='check';
			edata="action="+vaction;
			$.ajax({
				type: 'GET',
				url: 'leadtimeaction.php',
				data: edata,
				success: function(data) {
					if(data.toLowerCase().indexOf("error") >= 0){
						alert(data);
					}
					else{
					//edit
						vaction='add';
						$( "#dialog-form" ).attr("title","change record");
						$(".ui-dialog-titlebar").addClass("theme-1");
						$("span.ui-dialog-title").text("Request Due Date order"); 
						$( "#dialog-form" ).dialog( "open" );
					}
					
				}
			});
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
<body>
<?php ctc_get_logo() ?> <!-- add by CTC -->
<div id="mainNav">
<?
	$_GET['selection']="main";
	include("navhoriz.php");
			
?>
</div> 
<div id="isi">
        
    <div id="twocolLeft">
       	<?
		  	$_GET['current']="leadtime";
			include("navUser.php");
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

<table width="50%"  class="tbl1" cellspacing="0" cellpadding="0">
  <tr class="arial11white" bgcolor="#AD1D36" >
    <th width="40%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0582"); ?></th>
    <th width="40%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0583"); ?></th>
    <th width="20%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0467"); ?></th>
    </tr>
    
     <?
          require('../db/conn.inc');
          
          $per_page=10;
          $num=5;
    $query="select * from awsduedate where Owner_Comp='$comp' and cusno = '$cusno'";  
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
    
    $query1="select * from awsduedate where Owner_Comp='$comp' and cusno = '$cusno' LIMIT $start, $per_page"; 
    $sql=mysqli_query($msqlcon,$query1);    
	$rowcount=mysqli_num_rows($sql);
	if($rowcount == 0){
		echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>-</td><td>-</td>" ;
			
		echo "<td class=\"lasttd\">";
		echo "<a href='#' class='add' id=''> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
		
		echo "<td ></tr>";
	}else{
		while($hasil = mysqli_fetch_array ($sql)){
			$vcomp=$hasil['Owner_Comp'];
			$vordtype=$hasil['ordtype'];
			$vsetday=$hasil['setday'];
			$vendday=$hasil['endday'];
			$vmenu_sts=$hasil['holiday_st'];
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
					$status= get_lng($_SESSION["lng"], "L0587");
					break;
				case 1:
					$status= get_lng($_SESSION["lng"], "L0586");
					break;  
			}   

			echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vsetday."</td><td>"
			.$status."</td>" ;
			
			echo "<td class=\"lasttd\">";
			echo "<a href='#' class='edit' id='".$vcomp."||".$vordtype."||".$vsetday."||".$vendday."||".$vmenu_sts."'> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
			
			echo "<td ></tr>";
		
		}
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
<table width="400" border="0">
  <tr>
    <td> <input type="hidden" id="ordtype" name="ordtype"></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr id="rowdate">
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0584"); ?><!--Standard Leadtime--></span> :</td>
    <td><input type="number" name="leadsetday" id="leadsetday"  min="1" max="712" maxlength="3" onkeypress="return onlyNumberKey(event)" /></td>
    <td colspan="2" ><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0585"); ?><!-- Day(s)--></span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
        <input type="radio" name="leadtimestatus" id="leadtimestatus" class="arial11blackbold" value="1"><?php echo get_lng($_SESSION["lng"], "L0586"); ?><!-- Check Holiday-->
        <input type="radio" name="leadtimestatus" id="leadtimestatus" class="arial11blackbold" value="0"><?php echo get_lng($_SESSION["lng"], "L0587"); ?><!-- No CHeck Holiday-->
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
      
     
          
      </ul>

    <div id="footerDesc">

	<p>
	Copyright © 2023 DENSO . All rights reserved  
	
    </div>
</div>
 </div>
 </div>

	</body>
</html>

