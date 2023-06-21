<?php 
session_start();

require_once('../../core/ctc_init.php'); // add by CTC
require_once('../../language/Lang_Lib.php');
require_once('sup_config/sup_Web_Lip.php');//CTC P.Pawan 04/03/19 Add email config DSTH
//echo "BCC" .get_config("bcc");
if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
        $cusno = $_SESSION['cusno'];
        $cusnm = $_SESSION['cusnm'];
        $password = $_SESSION['password'];
        $alias = $_SESSION['alias'];
        $table = $_SESSION['tablename'];
        $type = $_SESSION['type'];
        $custype = $_SESSION['custype'];
        $user = $_SESSION['user'];
        $dealer = $_SESSION['dealer'];
        $group = $_SESSION['group'];
		    $owner_comp = ctc_get_session_comp(); // add by CTC
    } else {
      echo "<script> document.location.href='../../" . redir . "'; </script>";
    }
} else {
  header("Location:../../login.php");
}

include('chklogin.php');
$query_dnm="SELECT * FROM `cusmas` WHERE `Owner_Comp` LIKE '$comp' AND `Cusno` LIKE '$dealer'";
	$sql_dnm=mysqli_query($msqlcon,$query_dnm);
	if($tmphsl=mysqli_fetch_array($sql_dnm)){
		$dealer_nm=$tmphsl['Cusnm'];
	}
?>
<!-- <script type="text/javascript" language="javascript" src="lib/jquery-1.4.2.js"></script> -->

<html>
    <head>
        <title>Denso Ordering System</title>
		    <meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />  <!--02/04/2018 P.Pawan CTC-->
        <link rel="stylesheet" type="text/css" href="../css/dnia.css">
        <link rel="stylesheet" href="../css/ui/jquery.ui.base.css">
        <link rel="stylesheet" href="../themes/ui-lightness/jquery-ui.css">
        <script src="../lib/jquery-1.4.2.min.js"></script>
        <script src="../lib/jquery.ui.core.js"></script>
        <script src="../lib/jquery.ui.widget.js"></script>
        <script src="../lib/jquery.ui.mouse.js"></script>
        <script src="../lib/jquery.ui.button.js"></script>
        <script src="../lib/jquery.ui.draggable.js"></script>
        <script src="../lib/jquery.ui.position.js"></script>
        <script src="../lib/jquery.ui.resizable.js"></script>
        <script src="../lib/jquery.ui.button.js"></script>
        <script src="../lib/jquery.ui.dialog.js"></script>
        <link rel="stylesheet" href="../css/ui/demos.css">
        <script>
            $(function () {

              if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
                //alert( "This page is reloaded" );
                window.location = window.location.href.split("?")[0];
              } 

                $("#searchsupno").change(function () {
                    var supnoseleted = $("#searchsupno").val();
                    var edata = "supno=" + supnoseleted;
                    //alert(edata);
                    document.location = "suphistory.php?supno=" + supnoseleted;

                });

                $("#searchponumber").change(function () {
                    var ponumseleted = $("#searchponumber").val();
                    var edata = "ponum=" + ponumseleted;
                    //alert(edata);
                    document.location = "suphistory.php?ponum=" + ponumseleted;

                });

                
                $("#progress-bar").click(function(){
            			$(this).hide();
            		});

                    
                $( "#dialog-sendMail" ).dialog({
            			autoOpen: false,
            			width: 450,
            			height: 'auto',
            			modal: true,
                  open: function(event, ui) {
                      $("#errMsg").hide();
                      $(".ui-dialog-titlebar-close", ui.dialog | ui).click(function(){
                        var url = "suphistory.php";
              					$(location).attr('href',url);
                      });
                  },
            			position: {
            				my: "center",
            				at: "center",
            				of: $("body"),
            				within: $("body")
            			},buttons: {
            				'<?php echo get_lng($_SESSION["lng"], "L0324");/*Send*/ ?>': function() {
                                document.getElementById("progress-bar").style.display = "block";
                                var axEmail =[];
                                var axBcc = [];
                                var axCc = [];
                                $(".axEmail").each(function(){
                                    if(this.value!=="" && jQuery.inArray(this.value,axEmail)===-1){
                                    axEmail.push(this.value);
                                    }
                                });
                                var orderNo = document.getElementById('hiddenOrderNo').value;
                                var corno = document.getElementById('hiddenPONo').value;
                                var supno = document.getElementById('hiddenSupno').value;
                                var supnm = document.getElementById('hiddenSupnm').value;
                                var cusmail = document.getElementById('hiddenMailcus').value; 
                                var cusno = '<?php echo $cusno;?>';
                                var cusnm = '<?php echo $cusnm;?>';
                                var bcc = '<?php echo get_sup_config("bcc");?>';
                                axBcc = bcc.split(";");
                                axBcc.forEach(function(entry){
                                    if(entry!="" && axBcc.indexOf(entry)===-1){
                                    axBcc.push(entry);
                                    }
                                });
                                axCc = cusmail.split(";");
                                axCc.forEach(function(entry){
                                    if(entry!="" && axCc.indexOf(entry)===-1){
                                    axCc.push(entry);
                                    }
                                });
                                $.ajax({
                                    type: 'POST',
                                    url: 'supsendemail.php',
                                    data: {
                                        axEmail:axEmail,
                                        orderNo:orderNo,
                                        corno:corno,
                                        cusno:cusno,
                                        cusnm:cusnm,
                                        bcc:axBcc,
                                        supno:supno,
                                        supnm:supnm,
                                        cc:axCc,
										dealer_no: '<?php echo $dealer ?>',
										dealer_nm: '<?php echo $dealer_nm ?>',
                                    },
                                    success: function(data) {
                                        console.log(data);
                                        $("#progress-bar").hide();
                                        if(data.indexOf("failPDF")!==-1){
                                        $("span#ui-dialog-title-dialog-message").text('<?php echo get_lng($_SESSION["lng"], "L0316")/*Email send failed*/?>');
                                        $("label[id=notification]").html('');
                                        $("label[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "E0039");/*No PDF found. Please contact to DSTH or go to History Menu to request to send PO manually*/ ?></b><br>');
                                        }else{
                                        $("span#ui-dialog-title-dialog-message").text('<?php echo get_lng($_SESSION["lng"], "L0321"); ?>');//Send email
                                        $("label[id=notification]").html('');
                                        $("label[id=notification]").append('<b><?php echo get_lng($_SESSION["lng"], "L0322");/*Email has been sent successfully*/ ?></b><br>');
                                        }
                                        $('#dialog-message').dialog('open');
                                    
                                    }
                                });
                                $( this ).dialog( "close" );
                            }
            			}
            		});
                    
                $( "#dialog-message" ).dialog({
            			autoOpen: false,
            			width: 450,
            			height: 'auto',
            			modal: true,
            			position: {
            				my: "center",
            				at: "center",
            				of: $("body"),
            				within: $("body")
            			},
                  buttons: {
            				'<?php echo get_lng($_SESSION["lng"], "L0317");/*OK*/ ?>': function() {
                      $( this ).dialog( "close" );
            				}
            			},
                  close: function(){
                    var url = "suphistory.php";
                    $(location).attr('href',url);
                  }
            		});
            });

            function validateEmail(obj){
              var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              if(obj.value!=="" && !reg.test(String(obj.value).toLowerCase())){
                $(obj).addClass('ui-state-error');
                $("#errMsg").show();
              }else{
                $("#errMsg").hide();
                $(obj).removeClass('ui-state-error');
              }
              var axElem = document.getElementsByClassName('axEmail');
              var iEmpty = 0;
              for(var i=0;i<axElem.length;i++){
                if(axElem[i].classList.contains('ui-state-error')){
                  $("span.ui-button-text:contains('Send')").addClass('disableBtn');
                    $(".ui-button:contains('Send')").attr("disabled", true);
                  break;
                }else if(!axElem[i].classList.contains('ui-state-error') && axElem[i].value!==""){
                  $(".ui-button:contains('Send')").attr("disabled", false);
                  $("span.ui-button-text:contains('Send')").removeClass('disableBtn');
                }
                if(axElem[i].value===""){
                  iEmpty++;
                }
              }
              if(iEmpty===4){
                $("span.ui-button-text:contains('Send')").addClass('disableBtn');
                $(".ui-button:contains('Send')").attr("disabled", true);
              }
            }

            function sendEmail(orderNo,shipToCd,corNo,supNo,supNM){
              $(".axEmail").each(function(){
                this.value = "";
              });
              //alert(supNM);
              document.getElementById('hiddenOrderNo').value = orderNo;
              document.getElementById('hiddenPONo').value = corNo;
              document.getElementById('hiddenSupno').value = supNo;
              document.getElementById('hiddenSupnm').value = supNM
              var orderNo = orderNo;
              var cusNo = '<?php echo $cusno;?>';
              var shipToCd = shipToCd;
              var Asupno = supNo;
              $(".ui-button:contains('Send')").attr("disabled", true);
              $("span.ui-button-text:contains('Send')").addClass('disableBtn');
              $.ajax({
                type: 'POST',
                url: 'sup_getEmailHistoryShiptoAjax.php',
                async : false,
                data: {cusNo:cusNo,
                       shipToCd:shipToCd,
                       orderNo:orderNo,
                       corno:corNo,
                       supno:Asupno
                      },
                dataType: 'json',
                success: function(data) {
                    if(data){
                      var axMail = [];
                      for(var keys in data[0]){
                        if(data[0][keys] && jQuery.inArray(data[0][keys],axMail)===-1){
                          axMail.push(data[0][keys]);
                        }
                      }
                      if(axMail){
                        for(var i=0;i<axMail.length;i++){
                          $("#email"+parseInt(i+1)).val(axMail[i]);
                          $(".ui-button:contains('Send')").attr("disabled", false);
                          $("span.ui-button-text:contains('Send')").removeClass('disableBtn');
                        }
                      }else{
                        $(".ui-button:contains('Send')").attr("disabled", true);
                        $("span.ui-button-text:contains('Send')").addClass('disableBtn');
                      }
                    }
                  }
              });
              $("span#ui-dialog-title-dialog-sendMail").text('<?php echo get_lng($_SESSION["lng"], "L0320"); ?>');//Send email
              $('#dialog-sendMail').dialog('open');
            }

        </script>
    <!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->
</head>
<body>

    <?php ctc_get_logo() ?> <!-- add by CTC -->

    <div id="mainNav">
        <?php
        require("crypt.php");
        $_GET['selection'] = "main";
        include("navhoriz.php");

        $supno = $_GET['supno'] != null ? $_GET['supno'] : '';
        $ponum = $_GET['ponum'] != null ? $_GET['ponum'] : '';
        ?>

    </div>
    <div id="isi">

        <div id="twocolLeft">
            <?php
            $_GET['current'] = "suphistory";
            include("navUser.php");
            ?>
        </div>
        <div id="twocolRight">
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
                    <td><?php echo get_lng($_SESSION["lng"], "L0443"); ?><!--Supplier--></td>
                    <td>:</td>
                    <td>
                    <?
                        require('../db/conn.inc');
                      
                        $inpsupmas= '<select name="searchsupno" id="searchsupno" class="arial11blue">';
                        $inpsupmas= $inpsupmas .  ' <option value=""selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
                        $query="SELECT distinct supmas.supno, supmas.supnm
                        FROM `supawsorderhdr` JOIN supmas on supawsorderhdr.supno = supmas.supno and supawsorderhdr.Owner_Comp = supmas.Owner_Comp
                        WHERE supawsorderhdr.Owner_Comp='$owner_comp' and supawsorderhdr.Cust3 ='" . $cusno . "' group by supawsorderhdr.Corno order by supmas.supno asc";
                        //echo $query;
                        $sql=mysqli_query($msqlcon,$query);	
                        while($hasil = mysqli_fetch_array ($sql)){
                          $supmasno=$_GET['supno'];
                          $vsupno=$hasil['supno'];
                          $vsupnm=$hasil['supnm'];
                          $selected = ($vsupno == $supmasno) ? "selected" : "";
                          $inpsupmas= $inpsupmas .  ' <option '.$selected.'  value="'.$vsupno.'">'.$vsupno. ' >' .$vsupnm.'</option>';		
                          
                        }
                        $inpsupmas= $inpsupmas . ' </select>';
                        echo $inpsupmas; // Show dropdownlist
                      ?>
                    </td>
                    <td width="4%"></td>
                    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0240"); ?><!-- PO Number --></td>
                    <td width="2%">:</td>
                    <td width="25%">
                    <?
                        require('../db/conn.inc');
                      
                        $inpponum= '<select name="searchponumber" id="searchponumber" class="arial11blue">';
                        $inpponum= $inpponum .  ' <option value=""selected="selected">'.get_lng($_SESSION["lng"], "L0449").'</option>';
                        
                        if ($supno != '' && $supno != null) {
                          $query = "select distinct Corno as ponumber from supawsorderhdr  where Cust3 ='" . $cusno . "' and supno='$supno' and Owner_Comp='$owner_comp' group by Corno order by orderdate desc";
                        } else {
                          $query = "select distinct Corno as ponumber from supawsorderhdr  where Cust3 ='" . $cusno . "' and Owner_Comp='$owner_comp'  group by Corno order by orderdate desc";
                        }


                        //echo $query;
                        $sql=mysqli_query($msqlcon,$query);	
                        while($hasil = mysqli_fetch_array ($sql)){
                          $selectedpo=$_GET['ponum'];
                          $vponumber=$hasil['ponumber'];
                          $selected = ($selectedpo == $vponumber) ? "selected" : "";
                          $inpponum= $inpponum .  ' <option '.$selected.'  value="'.$vponumber.'">'.$vponumber.'</option>';		
                          
                        }
                        $inpponum= $inpponum . ' </select>';
                        echo $inpponum; // Show dropdownlist
                      ?>
                    </td>
                </tr>
                <tr class="arial11blackbold">
                    <td colspan='7'>&nbsp;</td>
                </tr>
                <tr class="arial11blackbold">
                    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0128"); ?><!--Customer Number--></td>
                    <td width="2%">:</td>
                    <td width="26%"><? echo $cusno ?></td>
                    <td width="4%"></td>
                    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0129"); ?><!--Customer Name--></td>
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

            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                    <th width="20%" scope="col">&nbsp;</th>
                    <th width="20%" scope="col">&nbsp;</th>
                    <th width="20%" valign="middle" scope="col"></th>
                    <th width="20%" scope="col"></th>
                    <th width="20%" scope="col" align="right"></a></th>
                </tr>
            </table>

            <table width="100%" class="tbl1" cellspacing="0" cellpadding="0" style="table-layout: fixed;">
                <tr class="arial11white" bgcolor="#AD1D36" >
                    <th width="8%" height="30" scope="col"><?php echo get_lng($_SESSION["lng"], "L0130"); ?><!--Order Date--></th>
                    <th width="14%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0451"); ?><!--Supplier Number--></th>
                    <th width="14%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0131"); ?><!--Po Number--></th>
                    <th width="15%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0132"); ?><!--Denso Order Number--></th>
                    <th width="5%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0133"); ?><!--Ship To--></th>
                    <th width="14%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0134"); ?><!--Type--></th>
                    <th width="12%" scope="col"><?php echo get_lng($_SESSION["lng"], "L0516"); ?><!--Remark--></th>
                    <th width="18%" class="lastth"><?php echo get_lng($_SESSION["lng"], "L0135"); ?><!--action--></th>
                </tr>


                <?php
                require('../db/conn.inc');
                $page = trim($_GET['page']);
                $per_page = 10;
                $num = 5;

                if ($page) {
                    $start = ($page - 1) * $per_page;
                } else {
                    $start = 0;
                    $page = 1;
                }

                if ($supno != '' && $supno != null) {
                    $query = "select *, (select supnm from supmas "
                      . " where supmas.Owner_Comp = supawsorderhdr.Owner_Comp AND supawsorderhdr.supno = supmas.supno ) as supname , supawsordernts.remark"
                      . " from supawsorderhdr "
                      . " inner join supawsordernts on supawsorderhdr.orderno=supawsordernts.orderno and supawsorderhdr.Owner_Comp=supawsordernts.Owner_Comp  and supawsorderhdr.Corno = supawsordernts.Corno  and supawsorderhdr.supno=supawsordernts.supno "
                      . " where supawsorderhdr.Cust3 ='" . $cusno . "' and supawsorderhdr.supno='" .$supno. "' and supawsorderhdr.Owner_Comp='". $owner_comp ."' " 
                      . " group by supawsorderhdr.orderno,supawsorderhdr.supno order by supawsorderhdr.orderdate, supawsorderhdr.orderno, supawsorderhdr.supno desc";
                } else if ($ponum != '' && $ponum != null) {
                  $query = "select *, (select supnm from supmas "
                    . " where supmas.Owner_Comp = supawsorderhdr.Owner_Comp AND supawsorderhdr.supno = supmas.supno ) as supname , supawsordernts.remark "
                    . " from supawsorderhdr  "
                    . " inner join supawsordernts on supawsorderhdr.orderno=supawsordernts.orderno and supawsorderhdr.Owner_Comp=supawsordernts.Owner_Comp  and supawsorderhdr.Corno = supawsordernts.Corno  and supawsorderhdr.supno=supawsordernts.supno " 
                    . " where supawsorderhdr.Cust3 ='" . $cusno . "' and supawsorderhdr.Owner_Comp='$owner_comp' and supawsorderhdr.Corno = '$ponum' group by supawsorderhdr.orderno,supawsorderhdr.supno order by  supawsorderhdr.orderdate, supawsorderhdr.orderno, supawsorderhdr.supno desc";
                }else {
                    $query = "select * , (select supnm from supmas where supmas.Owner_Comp = supawsorderhdr.Owner_Comp AND supawsorderhdr.supno = supmas.supno ) as supname , supawsordernts.remark "
                    ." from supawsorderhdr  "
                    . " inner join supawsordernts on supawsorderhdr.orderno=supawsordernts.orderno and supawsorderhdr.Owner_Comp=supawsordernts.Owner_Comp  and supawsorderhdr.Corno = supawsordernts.Corno  and supawsorderhdr.supno=supawsordernts.supno "
                    . " where supawsorderhdr.Cust3 ='" . $cusno . "' and supawsorderhdr.Owner_Comp='$owner_comp' group by supawsorderhdr.orderno,supawsorderhdr.supno order by  supawsorderhdr.orderdate, supawsorderhdr.orderno, supawsorderhdr.supno desc";
                }
				// Delete trnflg !='1' 25/05/2023 Pasakorn
                // echo $query;
                
                $sql = mysqli_query($msqlcon,$query);
                $mcount = mysqli_num_rows($sql);
                $query1 = $query . " LIMIT $start, $per_page";
                $sql = mysqli_query($msqlcon,$query1);
                while ($hasil = mysqli_fetch_array($sql)) {
                    $ordno = $hasil['orderno'];
                    $corno = $hasil['Corno'];
                    if ($corno == "")
                        $corno = "-";
                    $orderstatus = $hasil['ordtype'];
                    $orderdate = $hasil['orderdate'];
                    $shpno = $hasil['cusno'];
                    $shpCd = $hasil['shipto'];
                    $remark = $hasil['remark'];
                    $supno = $hasil['supno'];
                    $supnm = $hasil['supname'];
                    $orddate = substr($orderdate, -2) . "/" . substr($orderdate, 4, 2) . "/" . substr($orderdate, 0, 4);
                    switch ($orderstatus) {
                        case "U":
                            $ordsts = "Urgent";
                            break;
                        case "R":
                            $ordsts = "Request Due Date";
                            break;
                        case "N":
                            $ordsts = "Normal";
                            break;
                        case "A":
                            $ordsts = "Advance";
                            break;
                        case "C":
                            $ordsts = "Campaign";
                            break;
                    }
                    if ($orderstatus == "R") {
                        $urlprint = "<a href='sup_prtorderpdf.php?ordno=" . $ordno . "&corno=" . $corno . "&supno=".$supno."' target=\"new\"> <img src=\"../images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                    } else {
                        $urlprint = "Not Request";
                    }

                    if ($periode == "")
                        $periode = "-";
                    echo "<tr class=\"arial11blackbold\" align=\"center\"><td>" . $orddate . "</td>";
                    echo "<td>" . $supno . "</td>";
                    echo "<td>" . $corno . "</td>";
                    echo "<td>" . $ordno . "</td>";
                    echo "<td>" . $shpCd . "</td>";
                    echo "<td>" . $ordsts . "</td>";

                    
                    echo "<td style='word-wrap: break-word'>" . $remark . "</td>";
                    echo "<td class=\"lasttd\" >";
                    echo $urlprint;
                    echo "&nbsp;&nbsp;<img src=\"../images/mail.png\" onclick=\"sendEmail('".$ordno."','".$shpCd."','".$corno."','".$supno."','".$supnm."')\" width=\"15\" height=\"15\" border=\"0\" hspace=\"2\" style=\"cursor:pointer\">";
                  
                    echo "</td >";

                      //cc email for customer
                      $emailcus=[];
                      $tmpEmail=[];
                      $emailcc = "";
                      $getemail = "select distinct st.* from `shiptoma` st,`supawsorderhdr` od 
                        where trim(st.Cusno)=trim('".$cusno."') and trim(st.ship_to_cd)=trim(od.shipto) 
                        and st.Owner_Comp=od.Owner_Comp and st.Owner_Comp='$comp' and trim(od.orderno)='".$ordno."'";
                      //echo $query ;
                      $dtemail=mysqli_query($msqlcon,$getemail);
                      while($axData=mysqli_fetch_array($dtemail)){
                        $compMail = $axData['comp_mail_add'];
                        $prsnMail1= $axData['prsn_mail_add1'];
                        $prsnMail2= $axData['prsn_mail_add2'];
                        $prsnMail3= $axData['prsn_mail_add3'];
                      }
                      array_push($tmpEmail,$compMail,$prsnMail1,$prsnMail2,$prsnMail3);
                      for($index=0;$index<count($tmpEmail);$index++){
                        if(!in_array($tmpEmail[$index],$emailcus) && !empty($tmpEmail[$index]) ){
                          array_push($emailcus,$tmpEmail[$index]);
                        }
                      }
                      $emailcc = implode (";", $emailcus);


                    echo " <input type='hidden' id='hiddenMailcus' value='". $emailcc."' />";
                    echo "</tr>";



                }

                if ($mcount > $per_page) {
                    echo "<tr height=\"30\"><td colspan=\"9\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
                    require('pager.php');
                    paging($query, $per_page, $num, $page);
                    echo "</div></td></tr>";
                }
                ?>

                <tr>
                    <td colspan="9" class="lasttd" align="right">
                    <img src="../images/print.png" width="20" height="20"><span class="arial21redbold"> = <?php echo get_lng($_SESSION["lng"], "L0136"); ?><!--=Print--></span>
                    <img src="../images/mail.png" width="15" height="15"><span class="arial21redbold"> = <?php echo get_lng($_SESSION["lng"], "L0323"); ?><!--=Email--></span>
                    </td>
                </tr>
            </table>

        
        <div id="footerMain1">
            <ul>
                <!-- Disabled by zia
                
                     
                -->

            </ul>

            <div id="footerDesc">

                <p>
                    Copyright &copy; 2023 DENSO . All rights reserved

            </div>
        </div>
</div>
</div>
</body>
</html>

<style>
  .axEmail {
    margin-left: 25px;
    margin-bottom: 2px;
    margin-top: 2px;
    width: 95%;
    border-radius: 4px;
  }

  .disableBtn {
    background-color: lightgray;
    color: #a1a1a1;
  }
</style>
<!-- CTC P.Pawan 04/03/19 Add dialog for send email -->
<div id="dialog-sendMail" style="white-space:nowrap">
  <label style="margin-left:25px;font-size:10px;"><?php echo get_lng($_SESSION["lng"], "L0318"); ?><!--Please input email address--> </label>
  <div>
    <div><span style="position:absolute;margin-top:5px;"><?php echo get_lng($_SESSION["lng"], "L0319"); ?><!--To :--></span><input type="text" id="email1" class="axEmail ui-widget-content ui-corner-all" onkeyup="validateEmail(this);"/></div>
    <div><input type="text" class="axEmail ui-widget-content ui-corner-all" onkeyup="validateEmail(this);" id="email2"/></div>
    <div><input type="text" class="axEmail ui-widget-content ui-corner-all" onkeyup="validateEmail(this);" id="email3"/></div>
    <div><input type="text" class="axEmail ui-widget-content ui-corner-all" onkeyup="validateEmail(this);" id="email4"/></div>
    <div><label id="errMsg" style="margin-left:25px;display:none;color:red;"><?php echo get_lng($_SESSION["lng"], "E0038"); ?><!--Email is incorrect, please input correct email address--></label></div>
    <input type="hidden" id="hiddenOrderNo" readonly />
    <input type="hidden" id="hiddenPONo" readonly />
    <input type="hidden" id="hiddenSupno" readonly />
    <input type="hidden" id="hiddenSupnm" readonly />




  </div>
</div>
<!-- CTC P.Pawan 04/03/19 Add dialog messege -->
<div id="dialog-message" style="overflow-x : hidden;">
  <div style="margin:20px;text-align:center;"><label id="notification"></label></div>
</div>
<div id="progress-bar"
style="position: fixed;
  display: none;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0,0,0,0.5);
  z-index: 2;
  cursor: pointer;">
	<div
	style="position: absolute;
  top: 50%;
  left: 50%;
  font-size: 50px;
  color: white;
  transform: translate(-50%,-50%);
  -ms-transform: translate(-50%,-50%);">
		<img src="../images/loading.gif" width="50" height="50" />
	</div>
</div>
