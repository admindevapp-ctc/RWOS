
    <script type="text/javascript">
	$(document).ready(function() {
        $('input[type=checkbox][class=chk_boxes]').click(function() {
            $(this).each(function() {
                if ($('input[type=checkbox][class=chk_boxes]').is(':checked')) {
                    $("#checkall").attr("disabled", "disabled");
                    $("#p_cusall").attr("disabled", "disabled");
                    $("#p_currencyall").attr("disabled", "disabled");
                    $("#p_priceall").attr("disabled", "disabled");   
                    return true;
                }
                else{
                    $("#checkall").removeAttr('disabled');
                    $("#p_cusall").removeAttr('disabled');
                    $("#p_currencyall").removeAttr('disabled');
                    $("#p_priceall").removeAttr('disabled');  
                    return true;
                }
            });
        });
    });
</script>
<?php 

session_start();
require_once('../core/ctc_init.php'); // add by CTC

if(isset($_SESSION['cusno']))
{       
	$_SESSION['cusnm'];
	$_SESSION['password'];
	$_SESSION['alias'];
	$_SESSION['tablename'];
	$_SESSION['user'];
	$_SESSION['dealer'];
	$_SESSION['group'];
	$_SESSION['type'];
	$_SESSION['custype'];

	$cusno=	$_SESSION['cusno'];
	$cusnm=	$_SESSION['cusnm'];
	$password=$_SESSION['password'];
	$alias=$_SESSION['alias'];
	//$table=$_SESSION['tablename'];
	$type=$_SESSION['type'];
	$custype=$_SESSION['custype'];
	$user=$_SESSION['user'];
	$dealer=$_SESSION['dealer'];
	$group=$_SESSION['group'];
	$comp = ctc_get_session_comp(); 
  
}else{	
	header("Location: login.php");
}
/* Database connection information */
require('db/conn.inc');



if(isset($_POST['vCusno1']) && isset($_POST['vPartno'])){
	$Cusno1 = $_POST['vCusno1'];
	$Partno = $_POST['vPartno'];
    $Cusgrp = $_POST['vCusgrp'];
    $Supcode = $_POST['vSupno'];
    
    
    $table  .= '<table border="0" class="data" id="data">';
    $table  .= '<thead>';
    $table  .= '<tr>';
   // $table  .= '<th></th>';
    $table  .= '<th><span class=\"arial11redbold\">Customer Group</span></th>';
    $table  .= '<th><span class=\"arial11redbold\">Currency</span></th>';
    $table  .= '<th><span class=\"arial11redbold lasttd\">Price (Optional)</span></th>';
    $table  .= '<th>Condition</th>';
    $table  .= '</tr>';
    $table  .= '</thead>';
    $table  .= '<tbody>';

	$per_page=1;
	$num=5;

    $query1="SELECT supawsexc.Owner_Comp, cusno1, brand, 
    case when sell = 1 then 'Sell' else 'Not Sell' end textsell, cusgrp, price, curr,sell
    FROM supawsexc left join supcatalogue on trim(supawsexc.prtno) = supcatalogue.Prtno
    WHERE supawsexc.Owner_Comp='$comp' and cusno1 = '".$Cusno1."' and trim(supawsexc.prtno) = '".$Partno."' and supawsexc.supcode = '". $Supcode."' ";
	if(strlen($Cusgrp) == 3){
        $query1.=" and supawsexc.cusgrp in ('".$Cusgrp."')";
    }
	$sqlp=mysqli_query($msqlcon,$query1);
	$count = mysqli_num_rows($sqlp);
	
	$pages = ceil($count/$per_page);
	$page = $_GET['page'];
	if($page){ 
		$start = ($page - 1) * $per_page; 			
	}else{
		$start = 0;	
		$page=1;
	}
//echo $Cusgrp;

    $query="SELECT DISTINCT supawsexc.Owner_Comp, cusno1,   
    case when sell = 1 then 'Sell' else 'Not Sell' end textsell, cusgrp, price, curr,sell
    FROM supawsexc left join supcatalogue on trim(supawsexc.prtno) = supcatalogue.Prtno
    WHERE supawsexc.Owner_Comp='$comp' and cusno1 = '".$Cusno1."' and trim(supawsexc.prtno) = '".$Partno."' and supawsexc.supcode = '".$Supcode."' ";
    if(strlen($Cusgrp) == 3){
        $query.=" and supawsexc.cusgrp in ('".$Cusgrp."')";
    }
   // echo $query;
    $sql=mysqli_query($msqlcon,$query);
    $i=0;
    while($hasil = mysqli_fetch_array ($sql)){
        $i++;
        $table  .= '<tr>';
       // $table  .=    '<td><input type="checkbox" id="chk_boxes" class="chk_boxes" name="chk_boxes" value="'.$hasil['cusgrp'].'"/></td>';
        $table  .=    '<td align="center"><input style="text-align:center;" type="text" class="p_cusgroup" id="p_cusgroup'.$i.'" value="'.$hasil['cusgrp'].'" /></td>';
        $table  .=    '<td align="center"><input style="text-align:center;" type="text" class="p_currency" id="p_currency'.$i.'" value="'.$hasil['curr'].'" maxlength="2"/></td>';
        $table  .=    '<td align="center"><input style="text-align:right;" type="text" class="p_price" id="p_price'.$i.'" value="'.number_format($hasil['price'],2,'.',''). '"/></td>';
        $table  .=    '<td align="center"><input style="text-align:center;" type="checkbox" id="p_condotion'.$i.'"  class="p_condotion" name="p_condotion" value="'.$hasil['sell'].'"';
        if($hasil['sell'] == "1"){  
            $table  .=  ' Checked'; 
        }
        $table  .=    '/></td>';
        $table  .= '</tr>';
    }

    $table  .= '</tbody>';
    $table  .= '</table>';
    echo $table;

}

?>
