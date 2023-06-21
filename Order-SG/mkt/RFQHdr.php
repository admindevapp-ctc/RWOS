<?php session_start() ?>
<?php require_once('./../../core/ctc_init.php'); ?> <!-- add by CTC -->
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
		$dealer=$_SESSION['dealer'];
		$group=$_SESSION['group'];
		$comp= ctc_get_session_comp();  // add by CTC
	 }else{
		   echo "<script> document.location.href='../".redir."'; </script>";
	 }
}else{	
header("Location:../login.php");
}



?>

<html>
	<head>
    <title>Denso Ordering System</title>
   	<link rel="stylesheet" type="text/css" href="../css/dnia.css">
    <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">
    </style><!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->

<script src="../lib/jquery-1.4.2.min.js"></script>
<script type="text/javascript">

$(function() {
	$( ".submit_button" ).click(function() {
				
			var btnclick=$(this).val();
			switch (btnclick)
			{
				case "Save":
					$('#frmMaster')[0].submit();
					break;
				case "Reset":
					$('#frmMaster')[0].reset();
					break;
			
				default :
					 document.location.href='mainRfq.php';
			}
			//-------------------------------------------------------
			
			
			
			});

		   })
</script>	
	</head>
	<body>

	<?php require_once('../../core/ctc_cookie.php');?>
    
<?
	   include "../crypt.php";
	   require('../db/conn.inc');
	   $var = decode($_SERVER['REQUEST_URI']);
	   $xrfqno=trim($var['rfqno']);
	   $xprtno=trim($var['prtno']);
	   $xcust3=trim($var['custi3']);
	   $xrfqdt=trim($var['rfqdt']);
	   $mpage=trim($var['mpage']);
	   $sortby=trim($var['sortby']);
	   $srcstatus=trim($var['status']);
	   $srccust3=trim($var['srccust3']);
	   $srcpart=trim($var['srcpart']);
	  
	   $fld='sortby='.$sortby.'&status='.$srcstatus.'&srccust3='.$srccust3.'&srcpart='.$srcpart;

 	//echo $fld;
	   $inqdate=substr($xrfqdt,-2)."/".substr($xrfqdt,4,2)."/".substr($xrfqdt,0,4);
	   $query="select * from cusmas where Cust3='$xcust3'" ;
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);
		if($hasil = mysqli_fetch_array ($sql)){
			$cusname=$hasil['Cusnm'];
		}
		
		
		
		$query="select * from rfqdtl where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt'  and PRTNO='$xprtno' and Owner_Comp='$comp'" ; // edit by CTC
		//echo $query;
		$sql=mysqli_query($msqlcon,$query);
		if($hasil = mysqli_fetch_array ($sql)){
			$vdesc=$hasil['ITDSC'];
			$vrpldt=$hasil['RPLDT'];
			$vdiasrmk=$hasil['DIASRMK'];
			$vdiasans=$hasil['DIASANS'];
			$vsubtitute=$hasil['SUBTITUTE'];
			$vinremark=$hasil['IntRemark'];
			$vremark=$hasil['Remark'];
			$vrmkby=$hasil['RmkBy'];
			$vrmkdate=$hasil['RmkDate'];
			$vshpno=$hasil['CUSNO'];
		}else{
			echo '<center><h2>Permission Denied</h2></center>';
			die();
		}
	
		if($vrpldt==''){
			$rpldate=date("d-m-Y");
		}else{
			$rpldate=substr($vrpldt,-2)."/".substr($vrpldt,4,2)."/".substr($vrpldt,0,4);
		}
		
	   $inputrfq="<input name=\"rfqno\" type=\"text\"  id=\"rfqno\" class=\"arial11blackbold\" readonly=\"true\" value='".$xrfqno."'>"; 
	   $inputprtno="<input name=\"prtno\" type=\"text\"  id=\"prtno\" class=\"arial11blackbold\" readonly=\"true\" value='".$xprtno."'>";			       $inputdiasrmk="<input name=\"diasrmk\" type=\"text\"  id=\"diasrmk\" class=\"arial11blackbold\" size=\"100\"  maxlength=\"100\" value='".$vdiasrmk."'>";
	   $inputsubtitute="<input name=\"subtitute\" type=\"text\"  id=\"subtitute\" class=\"arial11blackbold\" size=\"30\"  maxlength=\"15\" value='".$vsubtitute."'>";
       //$inputprtno="<input name=\"prtno\" type=\"text\"  id=\"prtno\" class=\"arial11blackbold\" readonly=\"true\" value=".$xprtno.">";	
	
		$inputdiasans="<select name='diasans' class='arial11blackbold'>";
 		$inputdiasans=$inputdiasans .  "<option value=''> </option>";
		if($vdiasans=='RESTRICTED ITEM'){
			$inputdiasans=$inputdiasans .  "<option value='RESTRICTED ITEM' selected>RESTRICTED ITEM</option>";	
		}else{
				$inputdiasans=$inputdiasans .  "<option value='RESTRICTED ITEM'>RESTRICTED ITEM</option>";
		}
		if($vdiasans=='NON-EXISTENT'){
			$inputdiasans=$inputdiasans .  "<option value='NON-EXISTENT' selected>NON-EXISTENT</option>";	
		}else{
				$inputdiasans=$inputdiasans .  "<option value='NON-EXISTENT'>NON-EXISTENT</option>";
		}
		
		if($vdiasans=='PHASE OUT NO INTERCHANGE'){
			$inputdiasans=$inputdiasans .  "<option value='PHASE OUT NO INTERCHANGE' selected>PHASE OUT NO INTERCHANGE</option>";
		}else{
				$inputdiasans=$inputdiasans .  "<option value='PHASE OUT NO INTERCHANGE' >PHASE OUT NO INTERCHANGE</option>";
		}
		if($vdiasans=='QUOTATION WILL BE PROVIDED'){
			$inputdiasans=$inputdiasans .  "<option value='QUOTATION WILL BE PROVIDED' selected>QUOTATION WILL BE PROVIDED</option>";
		}else{
				$inputdiasans=$inputdiasans .  "<option value='QUOTATION WILL BE PROVIDED'>QUOTATION WILL BE PROVIDED</option>";
		
		}
		if($vdiasans=='DISCONTINUED, PLS ORDER SUBTITUTION PN'){
			$inputdiasans=$inputdiasans .  "<option value='DISCONTINUED, PLS ORDER SUBTITUTION PN' selected>DISCONTINUED, PLS ORDER SUBTITUTION PN</option>";
		}else{
				$inputdiasans=$inputdiasans .  "<option value='DISCONTINUED, PLS ORDER SUBTITUTION PN' >DISCONTINUED, PLS ORDER SUBTITUTION PN</option>";
		}
		$inputdiasans=$inputdiasans ."</select>";

	
	$inputremark="<input name=\"remark\" type=\"text\"  id=\"remark\" class=\"arial11blackbold\" size=\"100\"  maxlength=\"100\" value='".$vremark."'>";
	  echo "<input type=\"hidden\" name=\"rfqdt\" id=\"rfqdt\" value=".$xrfqdt.">";
	  $flag="";
	   $query="select * from rfqrmk " ;
	   $sql=mysqli_query($msqlcon,$query);
	   while($hasil = mysqli_fetch_array ($sql)){
			if($flag==""){
				$cmbinrmk="<select name='intrmk' class='arial11blackbold'>";
 				$cmbinrmk=$cmbinrmk .  "<option value=''> </option>";
				$flag="1";
			}
			if($hasil["RmkKey"]==$vinremark){
				$cmbinrmk=$cmbinrmk .  "<option value=" . $hasil["RmkKey"]. " selected>" . $hasil["RmkDes"]. "</option>";	
			}else{
				$cmbinrmk=$cmbinrmk .  "<option value=" . $hasil["RmkKey"]. ">" . $hasil["RmkDes"]. "</option>";	
			}
				
		}	
		if($flag=="1"){
			$cmbinrmk=$cmbinrmk ."</select>";
		}
			
	?>
   		
		<?php ctc_get_logo(); ?> <!-- add by CTC -->

		<div id="mainNav">
       		<ul>  
  				<li id="current"><a href="#" onClick="alert('Please use Close Order Entry button to move from transaction menu! ')">Administation</a></li>
				<li><a href="#" onClick="alert('Please use Close Order Entry button to move from transaction menu! ')">User Profile</a></li>
  				<li><a href="#" onClick="alert('Please use Close Order Entry button to move from transaction menu! ')">Table Part</a></li>
  				<li ><a href="#" onClick="alert('Please use Close button to move from transaction menu! ')">Log out</a></li>
  				  				
			</ul>
        
			
		</div> 
    	<div id="isi">
        <div id="twocolRight1">
           <form method="post" action="" id="frmMaster">

       
        
         <?
	
	if (isset($_POST['prtno']) && isset($_POST['rfqno'])) {
		$diasrmk=$_POST['diasrmk'];
		$diasans=$_POST['diasans'];
		$inremark=$_POST['intrmk'];
		$remark=$_POST['remark'];
		$subtitute=$_POST['subtitute'];
		$qrpldt=date('Ymd');
		$error='';
		//echo "dias answer adalah :". $diasans;
		//echo $subtitute;
		if($diasrmk!='' || $diasans!=''){
			if($diasans=='DISCONTINUED, PLS ORDER SUBTITUTION PN'){
				if($subtitute==""){
					echo '<table width="90%" border="0" valign="top"><tr height="20px">';
					echo '<td width="90%"><div class="ui-state-error" width="90%"><ul>';
					echo '<li>Please Input the Subtitution Part Number!</li>';
  					echo '</ul></div></td></tr></table>';
					$error='1';
				}
			}
			
			if($error!='1'){
				$qrpldt=date('Ymd');
				$query="update rfqdtl set RPLDT='$qrpldt', DIASRMK='$diasrmk', DIASANS='$diasans', STS='C', UpdBy='$cusno', IntRemark='$inremark', Remark='$remark', RmkDate='$qrpldt', SUBTITUTE='$subtitute'   where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt'  and PRTNO='$xprtno' and Owner_Comp='$comp'" ; // edit by CTC
				//echo $query;
				mysqli_query($msqlcon,$query);
				$query="Update RFQHDR set RFQUPDDT=$qrpldt where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt' and Owner_Comp='$comp'";
				mysqli_query($msqlcon,$query);
					
				$query="Update RFQHDR set STS='C', RFQDT=$qrpldt where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt' and not exists(select * from rfqdtl  where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt' and STS='P') and Owner_Comp='$comp'"; // edit by CTC
				//echo $query;
				mysqli_query($msqlcon,$query);
				$urllink='mainRFQ.php?'.$fld. '&mpage='.$mpage;
				echo "<script> document.location.href='". $urllink ."' </script>";
				//echo $urllink;
			}
		}else{
			if($inremark!=""){
				$query="update rfqdtl set RPLDT='$qrpldt', DIASRMK='$diasrmk', DIASANS='$diasans', STS='P', UpdBy='$cusno', IntRemark='$inremark', Remark='$remark', Rmkby='$cusno', RmkDate='$qrpldt' , SUBTITUTE='$subtitute'  where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt'  and PRTNO='$xprtno' and Owner_Comp='$comp'" ; // edit by CTC
				//echo $query;
				mysqli_query($msqlcon,$query);
				
				$query="Update RFQHDR set RFQUPDDT='$qrpldt' where Cust3='$xcust3' and RFQNO='$xrfqno' and RFQDT= '$xrfqdt' and Owner_Comp='$comp'";  // edit by CTC
				mysqli_query($msqlcon,$query);
				//echo $query;
				//echo "<br>";
				//echo 'mainRFQ.php?mpage='.$mpage;
				$urllink='mainRFQ.php?'.$fld. '&mpage='.$mpage;
				echo "<script> document.location.href='". $urllink."'</script>";
				//echo $urllink;
				
			}else{
			
				echo '<table width="90%" border="0" valign="top"><tr height="20px">';
				echo '<td width="90%"><div class="ui-state-error" width="90%"><ul>';
				echo '<li>DIAS Remark and DIAS Answer should not be blanked!</li>';
  				echo '</ul></div></td></tr></table>';
			}
		}
	}
	
		
	
	
	?>
<fieldset style="width:90%">
<legend> &nbsp;RFQ Request Information</legend>
 <table width="97%" border="0" cellspacing="0" cellpadding="0">
  <tr class="arial11blackbold">
    <td ><div align="right"><span class="arial12BoldGrey">RFQ No</span></div></td>
    <td><div align="center"><span class="arial12Bold">:</span></div></td>
    <td><span class="arial12Bold"><? echo $inputrfq ?></span></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="tbl1">
    <td class="arial12BoldGrey"><div align="right"></div></td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td width="16%" ><div align="right"><span class="arial12BoldGrey">Customer Number</span></div></td>
    <td width="2%"><div align="center"><span class="arial12Bold">:</span></div></td>
    <td width="29%"><span class="arial12Bold"><? echo $xcust3 ?></span></td>
    <td width="3%"></td>
    <td width="19%"><span class="arial12BoldGrey">Customer Name</span></td>
    <td width="1%"><span class="arial12Bold">:</span></td>
    <td width="30%"><span class="arial12Bold"><? echo $cusname ?></span></td>
  </tr>
  <tr class="arial11blackbold">
    <td ><div align="right"></div></td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td  align="right"><span class="arial12BoldGrey" >Ship To</span></td>
    <td><div align="center"><span class="arial12Bold">:</span></div></td>
    <td>
	<? echo $vshpno ?>
	
	</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
  <tr class="arial11blackbold">
    <td ><div align="right"><span class="arial12BoldGrey">Inquiry Date</span></div></td>
    <td><div align="center"><span class="arial12Bold">:</span></div></td>
    <td><span class="arial12Bold"><? echo $inqdate ?></span></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td class="arial12BoldGrey"><div align="right"></div></td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td ><div align="right"><span class="arial12BoldGrey">Part Number</span></div></td>
    <td><div align="center">:</div></td>
    <td><span class="arial12Bold"><? echo $inputprtno ?></span></td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td class="arial12BoldGrey"><div align="right"></div></td>
    <td><div align="center"></div></td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td ><div align="right"><span class="arial12BoldGrey">Description</span></div></td>
    <td><div align="center">:</div></td>
    <td><span class="arial12Bold"><? echo $vdesc ?></span></td>
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
   </table>
   </fieldset>
   <br>
<fieldset style="width:90%">
<legend> DIAS Answer RFQ</legend>
<table width="97%" border="0" cellspacing="0" cellpadding="0">
	<td width="16%"><div align="right"><span class="arial12BoldGrey">Reply Date</span></div></td>
    <td width="2%"><div align="center">
      <div align="center"><strong>:</strong></div>
    </div></td>
    <td width="82%"><label><span class="arial12Bold"><? echo $rpldate?></span>
      
    </label></td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"><span class="arial12BoldGrey">DIAS Remark</span></div></td>
    <td><div align="center">:</div></td>
    <td><span class="arial12Bold"><? echo $inputdiasrmk ?></span></td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"><span class="arial12BoldGrey">DIAS Answer</span></div></td>
    <td><div align="center">:</div></td>
    <td><span class="arial12Bold"><? echo $inputdiasans ?></span></td>
    </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"><span class="arial12BoldGrey">Subtitution Part No</span></div></td>
    <td><div align="center">:</div></td>
    <td><? echo $inputsubtitute ?></td>
  </tr>
        </table>
       
        
     </fieldset>
	 
	 <fieldset style="width:90%">
<legend> DIAS Internal Remark</legend>
<table width="97%" border="0" cellspacing="0" cellpadding="0">
	<td width="16%"><div align="right"><span class="arial12BoldGrey">Dias Internal Remark</span></div></td>
    <td width="2%"><div align="center">
      
    </div></td>
    <td width="82%"><label><span class="arial12Bold"><? echo $cmbinrmk ?></span>
      
    </label></td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"><span class="arial12BoldGrey">Note</span></div></td>
    <td><div align="center">:</div></td>
    <td><span class="arial12Bold"><? echo $inputremark ?></span></td>
    </tr>
  <tr class="arial11blackbold">
    <td><div align="right"></div></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td><div align="right"><span class="arial12BoldGrey">Last Updated</span></div></td>
    <td><div align="center"><strong>:</strong></div></td>
    <td><? 
	$rmkdate1=substr($vrmkdate,-2)."/".substr($vrmkdate,4,2)."/".substr($vrmkdate,0,4);
	
	echo $rmkdate1 ?></td>
  </tr>
  <tr class="arial11blackbold">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="arial11blackbold">
    <td><div align="right"><span class="arial12BoldGrey">Updated By</span></div></td>
    <td><div align="center"><strong>:</strong></div></td>
    <td><? echo  $vrmkby ?></td>
  </tr>
        </table>
<p>
 <table width="97%" border="0" cellspacing="0" cellpadding="0">
          <tr align="right">
            <td width="10%">&nbsp;</td>
            <td width="4%">&nbsp;</td>
            <td width="29%"></td>
            <td width="37%">
             <input type="button" value="Save"  id="cmdSave" class="submit_button" />
  <input type="button" value="Return"  class="submit_button" /></td>
          </tr>
       </table>

     </fieldset>
	 
	
	 
	 </form>
        
         
          
          
          </tbody>
        </table>
        <p><div id="result"></div></p>
        
        </div>
    
  
    
              
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
