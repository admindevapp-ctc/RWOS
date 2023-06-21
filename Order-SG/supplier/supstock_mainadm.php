<?php 

session_start();
require_once('./../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');

if(isset($_SESSION['cusno']))
{       
	if($_SESSION['redir']=='Order-SG'){
		$_SESSION['cusno'];
		$_SESSION['supno'];
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
		$supno=	$_SESSION['supno'];
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
		
        if($type!='s'){
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

	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
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
	
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
	
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
    
     
	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
        <link rel="stylesheet" type="text/css" href="../css/custom-bootstrap.css">
<script type="text/javascript">
$(function() {
   
	if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
		//alert( "This page is reloaded" );
		window.location = window.location.href.split("?")[0];
	} 

	var vaction="";
	var res="";
	var  cusno=$( "#cusno" ),
	 itnbr=$( "#itnbr" ),	
	 qty = $( "#stockqty" ),
	 allFields = $( [] ).add( itnbr).add( qty ),
	 tips = $( ".validateTips" );
		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min ) {
			if (o.val().length != min ) {
				o.addClass( "ui-state-error" );
				updateTips( "<?php echo get_lng($_SESSION["lng"], "W9024")?>" + n +  "<?php echo get_lng($_SESSION["lng"], "W9026")?>" +
					min  + "<?php echo get_lng($_SESSION["lng"], "W9025")?>" );
				return false;
			} else {
				return true;
			}
		}

		function checkLengthmax( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				if(max!=min){	
					updateTips( "<?php echo get_lng($_SESSION["lng"], "W9024")?>" + n + "<?php echo get_lng($_SESSION["lng"], "W9027")?>" +
						min + "<?php echo get_lng($_SESSION["lng"], "W9028")?>" + max + "<?php echo get_lng($_SESSION["lng"], "W9025")?>");
						return false;
					}else{
						updateTips(  "<?php echo get_lng($_SESSION["lng"], "E0052")?>"  );
						return false;
					}
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		//setup modal
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			width: 600,
			modal: true,
			position: { 
				my: "center",
				at: "center", 
				of: $("body"),
				within: $("body")
			},
			buttons: {
				"<?php echo get_lng($_SESSION["lng"], "L0487"); ?>": function() {
					var bValid = true;
					allFields.removeClass( "ui-state-error" );
					//alert(excrate);
					bValid = bValid && checkLengthmax( itnbr,"<?php echo get_lng($_SESSION["lng"], "L0464")?>", 2,15 );
					//bValid = bValid && checkLengthmax( qty,"<?php echo get_lng($_SESSION["lng"], "L0519")?>", 3,5 );
					//bValid = bValid && checkRegexp( qty, /^[\d ]+([.,][\d ]+)?$/, "Qty should be Numeric value" );
					
					/** check part number **/
				
				var vitnbr=itnbr.val();
				var vqty=qty.val();
				var edata;
				
				edata="itnbr="+vitnbr+"&stockqty="+vqty+"&action="+vaction;
				//alert(edata);
				
			
					/********************************************************/																	                   if ( bValid ) {
						
						$.ajax({
						type: 'GET',
						url: 'getsupstock.php',
						data: edata,
						success: function(data) {
							xdata=jQuery.trim(data);
							//alert(data);
							if(xdata.substr(0,5)=='Error'){
								//alert(data);
								qty.addClass( "ui-state-error" );
								$( ".validateTips" ).text(data).addClass( "ui-state-highlight" );
								updateTips( data);
								return false;
							}else{
							
								$( "#dialog-form").dialog( "close" );
								window.location.reload();
							}
						}
					});
						
				 }
				},
				<?php echo get_lng($_SESSION["lng"], "L0045"); ?>: function() {
					
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
			}
		});
	
		$( ".edit" ).click(function() {
				pos =$( this ).attr("id");
				//alert(pos);
				var xdata=pos.split("||");
				var ownerno=xdata[0];
				var supno=xdata[1];
				var partno=xdata[2];
				var qty=xdata[3];

				$('#itnbr').val(partno);
				$('#itnbr').attr("disabled", true);
				$('#stockqty').val(qty);
				
				vaction='edit';
					$( "#dialog-form" ).attr("title","<?php echo get_lng($_SESSION["lng"], "L0530"); ?>");
					//var xtitle=$( "#dialog-form" ).attr('title')
					$("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0530"); ?>'); 
					//alert(xtitle);
					$( "#dialog-form" ).dialog( "open" );
						
			
			});

			$( "#new" ).click(function() {
				$('#itnbr').removeAttr("disabled");
				$('#stockqty').removeAttr("disabled");
		
				$( ".validateTips" ).text('').removeClass( "ui-state-highlight" );
				$("span.ui-dialog-title").text('<?php echo get_lng($_SESSION["lng"], "L0529");?>'); 
				$( "#dialog-form" ).dialog( "open" );
				vaction='add';
			});
			
			$.urlParam = function(name){
				var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
				if (results == null){
				return null;
				}
				else {
				return decodeURI(results[1]);
				}
			}
			$('#ConvExcel').click(function(){

				//let searchParams = new URLSearchParams(window.location.search)
				let vprtno = $.urlParam ('intpartno');

				url= 'supgetstockXLS.php?partno=' + vprtno,
				window.open(url);	
					
		 	});
			
		 
	});
</script>
</head>
<body >
   	<?php ctc_get_logo(); ?> <!-- add by CTC -->
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
			$_GET['current']="supstockAdm";
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
			
            // Customer
            $inpcus= '<select name="cuscode" id="cuscode" class="arial11blue">';
			$inpcus= $inpcus .  ' <option value=""></option>';
			$query="select * from supref where Owner_Comp='$comp' and supno='$supno'  ";
			$sql=mysqli_query($msqlcon,$query);	
			while($hasil = mysqli_fetch_array ($sql)){
                $cusno=$hasil['Cusno'];	
                $vcusno=$_GET['cuscode'];
                $selected = ($hasil["Cusno"] == $vcusno) ? "selected" : "";
				$inpcus= $inpcus .  ' <option  '.$selected.' value="'.$cusno.'">'.$cusno.'</option>';			
				
			}
        	$inpcus= $inpcus . ' </select>';

		 ?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
		<tr class="arial11blackbold" style="vertical-align: top;">
                <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                <td width="10%" class="arial21redbold"><?php echo get_lng($_SESSION["lng"], "M008");?></td>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
                <td rowspan="3" align="right" width="47%"><img src='<?php echo "../sup_logo/".$suplogo; ?>' height="80" width="200" /></td>
            </tr>
			<tr class="arial11blackbold">
				<td colspan="2">
					<span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0451");?></span>
					<span class="arial12Bold">:</span>
				</td>
				<td>
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

        <form name ="search" method="get">
            <fieldset style="width:98%">
             <legend> &nbsp;<?php echo get_lng($_SESSION["lng"], "L0456"); //Search Information?></legend>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11blackbold">
                    <td width="16%" ><div align="right"><span class="arial12BoldGrey"><?php echo get_lng($_SESSION["lng"], "L0144"); //Part Number?></span></div></td>
                    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
                    <td width="16%">
						<input type="text" id="intpartno" name="intpartno" width="200px;" />
					</td>
                    <td width="3%" align="right"><input type="submit" name="button" id="button" value="<?php echo get_lng($_SESSION["lng"], "L0105"); //Search?>" class="arial11"></td>
                    <td width="30%">&nbsp;</td>
                    <td width="33%"></td>
                </tr>
                <tr class="arial11blackbold">
                    <td >&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                
                </table>
            </fieldset>
        </form>


		

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="arial11">
                <th scope="col">&nbsp;</th>
                <th width="90" scope="col">
					<a href="supimstock.php" style='text-decoration-line: none;'>
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
					<a id="new" style='text-decoration-line: none;'>
                        <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;height:22px;'>
                             <img src="../images/new.png" width="18" height="18" style='float:left;margin-left:4px;margin-top:1px;'>
                             <font style='margin-right:18px;line-height:22px;'><?php echo get_lng($_SESSION["lng"], "L0006"); ?></font>
                        </div>
                    </a>
                </th>
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
            </table>


        <table width="100%"  class="tbl1" cellspacing="0" cellpadding="0" >
        <tr class="arial11whitebold" bgcolor="#AD1D36" >
            <th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0464"); //Item Number?></th>
            <th width="10%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0519");//QTY ?></th>
            <th width="10%" height="30" scope="col"class="lasttd"><?php echo get_lng($_SESSION["lng"], "L0467"); ?></th>
        </tr>
    
		<?
		
			require('../db/conn.inc');
				
			$per_page=20;
			$num=5;
			//select for count
			$query="select * from supstock  ";

			$criteria="  where Owner_Comp='$comp' and supno = '$supno' ";

			if(isset($_GET["intpartno"])){
				$partno=trim($_GET["intpartno"]);
				if($partno!=''){
					$criteria .= ' and partno="'.$partno.'"';
							
				}
			}

			$query .= "$criteria order by partno asc";
			//echo  $query . "<br/>";
			$sql=mysqli_query($msqlcon,$query);
			$count = mysqli_num_rows($sql);
			//echo $count . "<br/>";
			$pages = ceil($count/$per_page);
			$page = $_GET['page'];
			if($page){ 
				$start = ($page - 1) * $per_page; 			
			}else{
				$start = 0;	
				$page=1;
			}


			$query1="select * from supstock ";
			$criteria =  $criteria. " order by partno asc LIMIT $start, $per_page"; // edit by CTC
			$query1= $query1 . $criteria;
			//echo $query1 . "<br/>";
			$sql=mysqli_query($msqlcon,$query1);	
			
			if( ! mysqli_num_rows($sql) ) {
				echo "<tr height=\"30\"><td colspan=\"7\" align=\"center\" class=\"arial12BoldGrey\">" . get_lng($_SESSION["lng"], "E0060") /*No Data Found.... */ . "</td></tr>";
			}
					while($hasil = mysqli_fetch_array ($sql)){
						$vowner=$hasil['Owner_Comp'];
						$vsupno=$hasil['supno'];
						$vpartno=$hasil['partno'];
						$vqty=$hasil['StockQty'];
						
						echo "<tr class=\"arial11black\" align=\"center\" height=\"30\"><td>".$vpartno."</td>";
						echo "<td>".$vqty."</td>";
						echo "<td class=\"lasttd\">";
						echo "<a href='getsupstock.php?action=delete&supno=$vsupno&itnbr=$vpartno&owner=$vowner' onclick=\"return confirm('Are you sure you want to delete?')\"> <img src=\"../images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
						echo "<a class='edit' id='".$vowner."||".$vsupno."||".$vpartno."||".$vqty."'> <img src=\"../images/edit.png\" width=\"20\" height=\"20\" border=\"0\"  ></a> ";
						echo "</td></tr>";
					
					}
					
					require('pager.php');
					if($count>$per_page){		
						echo "<tr height=\"30\"><td colspan=\"7\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
						//echo $query;
						$fld="page";
						paging($query,$per_page,$num,$page);
						echo "</div></td></tr>";
					}

		?>

		<tr>
			<td colspan="8"  align="right" class="lasttd" >
				<img src="../images/delete.png" width="20" height="20"><span class="arial11redbold"> = delete</span>
				<img src="../images/edit.png" width="20" height="20"><span class="arial11redbold"> = edit</span>
			</td>
		</tr> 
		</table>

	</div>

</div>


<div id="dialog-form" title="Add Order Detail" >
<p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0548");//All form fields are required.?></p>
<form>
  <table width="500" border="0">
  <tr>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0464");//Item Number?></span></td>
    <td><input type="text" size="18" maxlength="15" name="itnbr" id="itnbr"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0519");//Stock Qty?></span></td>
    <td><input type="text" size="5" maxlength="5" name="stockqty" id="stockqty"  /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>        
</form>
</div>


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
</body>
</html>
