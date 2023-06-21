<?php session_start() ?>
<?php
require_once('../language/Lang_Lib.php');
//if (session_is_registered('cusno'))
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
    } else {
        echo "<script> document.location.href='../" . redir . "'; </script>";
    }
} else {
    header("Location:../login.php");
}


include('chklogin.php');
?>

<html>
    <head>
        <title>Denso Ordering System</title>
    </style><!--[if IE]>
<style type="text/css">
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>
<![endif]-->

    <link rel="stylesheet" type="text/css" href="css/dnia.css">
    <link rel="stylesheet" href="themes/ui-lightness/jquery-ui.css">

    <script src="lib/jquery-1.4.2.min.js"></script>
    <script src="lib/jquery.ui.core.js"></script>
    <script src="lib/jquery.ui.widget.js"></script>
    <script src="lib/jquery.ui.mouse.js"></script>
    <script src="lib/jquery.ui.button.js"></script>
    <script src="lib/jquery.ui.draggable.js"></script>
    <script src="lib/jquery.ui.position.js"></script>
    <script src="lib/jquery.ui.resizable.js"></script>
    <script src="lib/jquery.ui.button.js"></script>
    <script src="lib/jquery.ui.dialog.js"></script>
    <script src="lib/jquery.ui.effect.js"></script>
    <link rel="stylesheet" href="css/demos.css">
    <script type="text/javascript">

        $(function () {
            $("#result_tr1").hide()
            //dialog message
            var vaction = "";
            //$( "#dialog:ui-dialog" ).dialog( "destroy" );
            var res = "";
            var corno = $("#txtCorno"),
                    allFields = $([]).add(corno),
                    tips = $(".validateTips");

            function updateTips(t) {
                tips
                        .text(t)
                        .addClass("ui-state-highlight");
                setTimeout(function () {
                    tips.removeClass("ui-state-highlight", 1500);
                }, 500);
            }

            function checkLength(o, n, min, max) {
                if (o.val().length > max || o.val().length < min) {
                    o.addClass("ui-state-error");
                    if (max != min) {
                        updateTips("Length of " + n + " must be between " +
                                min + " and " + max + ".");
                        return false;
                    } else {
                        updateTips("invalid Due date");
                        return false;
                    }
                } else {
                    return true;
                }
            }

            /** check order type and Transport **/

            function checkVal(o, n) {
                if (o.val().length == 0) {
                    o.addClass("ui-state-error");
                    if (max != min) {
                        updateTips(n + " should be selected!");
                        return false;
                    }
                } else {
                    return true;
                }
            }




            function checkRegexp(o, regexp, n) {
                if (!(regexp.test(o.val()))) {
                    o.addClass("ui-state-error");
                    updateTips(n);
                    return false;
                } else {
                    return true;
                }
            }


            $("#dialog-form").dialog({
                autoOpen: false,

                width: 700,
                modal: true,

                position: {
                    my: "center",
                    at: "center",
                    of: $("body"),
                    within: $("body")
                },
                buttons: {
                    "<?php echo get_lng($_SESSION["lng"], "L0044"); ?>": function () {
                        var bValid = true;
                        allFields.removeClass("ui-state-error");
                        bValid = bValid && checkLength(corno, "Order Number", 2, 10);


                        var edata;
                        var vcorno = corno.val();
                        var vorderno = $('#orderno').val();
                        var vorddate = $('#orddate').val();
                        var rcv = $("#txtShpNo").val().split("|");
                        //var rcv=data.split("||");
                        var oecus = rcv[1].toUpperCase();
                        var vshpno = rcv[0];
                        var vshipment = $('#shipment').val();
                        if (vshpno.length == 0) {
                            $('#txtShpNo').addClass("ui-state-error");
                            updateTips('Ship To Should be filled!');
                            return false;
                        }
                        var vaction = "new";
                        edata = "ordno=" + vorderno + "&corno=" + vcorno + "&orddate=" + vorddate + "&shpno=" + vshpno + "&shipment=" + vshipment + "&action=" + vaction + "&ordertype=Advance";


                        if (bValid) {

                            $.ajax({
                                type: 'GET',
                                url: 'Orderadd.php',
                                data: edata,
                                success: function (data) {
                                    //alert(data);
                                    if (data.substr(0, 5) == 'Error') {

                                        corno.addClass("ui-state-error");
                                        $(".validateTips").text(data).addClass("ui-state-highlight");
                                        return false;
                                    } else {
                                        //alert(data);
                                        //$('#result').html(data);
                                        window.location.href = data;
                                    }


                                    $("#dialog-form").dialog("close");
                                }

                            });



                        }
                    },
                    "<?php echo get_lng($_SESSION["lng"], "L0045"); ?>": function () {

                        $(this).dialog("close");
                    }
                },
                close: function () {

                    allFields.val("").removeClass("ui-state-error");
                }
            });



            $("#new").click(function () {
                $.ajax({
                    type: 'GET',
                    url: 'getordnoprd.php',
                    async: false,
                    success: function (data) {
                        if (data.substr(0, 5) == 'Error') {
                        } else {
                            var rcv = data.split("||");
                            var prdyear = rcv[0];
                            var prdmonth = rcv[1];
                            var ord = rcv[2];
                            $('#orderno').val(ord);
                            $('#prdmonth').val(prdmonth);
                            $('#prdyear').val(prdyear);

                        }
                    }
                });

                $(".validateTips").text('All form fields are required.').removeClass("ui-state-highlight");
                $("#dialog-form").dialog("open");

            });


            $("#txtShpNo").change(function () {
                //alert($("#txtShpNo" ).val());
                var rcv = $("#txtShpNo").val().split("|");
                //var rcv=data.split("||");
                var oecus = rcv[1].toUpperCase();
                if (oecus == 'Y') {
                    $("#result_tr1").show();
                } else {
                    $("#result_tr1").hide()
                }
            });

        });
    </script>




</head>
<body >
    <div id="header">
        <img src="images/denso.jpg" width="206" height="54" />
    </div>
    <div id="mainNav">


        <?php
        include "crypt.php";
        $_GET['selection'] = "advancedOrder";
        include("navhoriz.php");
        ?>
    </div>
    <div id="isi">

        <div id="twocolLeft">
            <?php
            $_GET['current'] = "advancedOrder";
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
                    <td width="22%"><?php echo get_lng($_SESSION["lng"], "L0003"); ?><!--Customer Number--></td>
                    <td width="2%">:</td>
                    <td width="26%"><?php echo $cusno ?></td>
                    <td width="4%"></td>
                    <td width="20%"><?php echo get_lng($_SESSION["lng"], "L0004"); ?><!--Customer Name--></td>
                    <td width="2%">:</td>
                    <td width="25%"><?php echo $cusnm ?></td>
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



                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr valign="middle" class="arial11">
                        <th  scope="col">&nbsp;</th>
                        <th width="90" scope="col"></th>
                        <th width="90" scope="col" align="right"></th>

                    </tr>
                    <tr height="5"><td colspan="5"></td><tr>
                </table>


            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr valign="middle" class="arial11">
                    <th  scope="col">&nbsp;</th>
                    <th width="90" scope="col">
                        <?php
                        if ($type != "v")
                            // echo "<a href=\"imregorder.php\"><img src=\"images/importxls.gif\" width=\"80\" height=\"20\"></a>";
                            echo "<a href=\"imregorder.php\" style='text-decoration-line: none;'>
                                    <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 80px;height:22px;'>
                                        <img src=\"images/excel.jpg\" width=\"18\" height=\"18\" style='margin-right:3px;' style='float:left;margin-left:4px;margin-top:3px;'>
                                        <font style='margin-right:18px;line-height:22px;'>". get_lng($_SESSION["lng"], "L0005")."</font>
                                    </div>
                                </a>";
                        ?>
                    </th>
                    <th width="90" scope="col" align="right">
                        <?php
                        if ($type != "v")
                            // echo "<a href=\"#\" id=\"new\"><img src=\"images/newtran.png\" width=\"80\" height=\"20\"></a>";
                            echo "<a href=\"#\" id=\"new\" style='text-decoration-line: none;'>
                                    <div style='background-color: #AD1D36;font-size:9pt;color: #FFFFFF;width: 80px;height:22px;'>
                                        <img src=\"images/new.png\" width=\"18\" height=\"18\" style='margin-right:3px;' style='float:left;margin-left:4px;margin-top:1px;'>
                                        <font style='margin-right:18px;line-height:22px;'>". get_lng($_SESSION["lng"], "L0006")."</font>
                                    </div>
                                </a>";
                        ?>
                    </th>
                </tr>
                <tr height="5"><td colspan="5"></td><tr>
            </table>


            <table width="100%" class="tbl1" cellspacing="0" cellpadding="0">
                <tr class="arial11grey" bgcolor="#AD1D36" >
                    <?php
                    if ($custype == "A") {
                        $colnum = 7;
                        echo '<th width="12%" height="30">' . get_lng($_SESSION["lng"], "L0007") /* Status */ . '</th>';
                        echo '<th width="9%" height="30" scope="col">' . get_lng($_SESSION["lng"], "L0008") /* Order Date */ . '</th>';
                        echo '<th width="20%" >' . get_lng($_SESSION["lng"], "L0009") /* Po Number */ . '</th>';
                        echo '<th width="20%" >' . get_lng($_SESSION["lng"], "L0010") /* Denso Order Number */ . '</th>';
                        echo ' <th width="13%" >' . get_lng($_SESSION["lng"], "L0011")/* Ship To */ . '</th>';
                        echo '<th width="9%" >' . get_lng($_SESSION["lng"], "L0012")/* Type */ . '</th>';
                        echo '<th width="18%" class="lastth">' . get_lng($_SESSION["lng"], "L0013") /* action */ . '</th>';
                    } else {
                        echo '<th width="9%" height="30" scope="col">' . get_lng($_SESSION["lng"], "L0008") /* Order Date */ . '</th>';
                        echo '<th width="23%" >' . get_lng($_SESSION["lng"], "L0009") /* Po Number */ . '</th>';
                        echo '<th width="23%" >' . get_lng($_SESSION["lng"], "L0010") /* Denso Order Number */ . '</th>';
                        echo ' <th width="17%" >' . get_lng($_SESSION["lng"], "L0011") /* Ship To */ . '</th>';
                        echo '<th width="10%" >' . get_lng($_SESSION["lng"], "L0012") /* Type */ . '</th>';
                        echo '<th width="18%" class="lastth">' . get_lng($_SESSION["lng"], "L0013") /* action */ . '</th>';
                        $colnum = 6;
                    }

                    echo "</tr>";
                    require('db/conn.inc');
                    //include "crypt.php";

                    $mpage = trim($_GET['mpage']);
                    $mper_page = 10;
                    $num = 10;

                    if ($mpage) {
                        $start = ($mpage - 1) * $mper_page;
                    } else {
                        $start = 0;
                        $mpage = 1;
                    }


                    $query = "select * from orderhdr  where ordtype='R' and trim(CUST3) ='" . $cusno . "' and Trflg!='1'";
                    $query = $query . " order by orderno, orderdate";
                    $sql = mysqli_query($msqlcon,$query);
                    $mcount = mysqli_num_rows($sql);
                    $query1 = $query . " LIMIT $start, $mper_page";

                    $sql = mysqli_query($msqlcon,$query1);
                    while ($hasil = mysqli_fetch_array($sql)) {
                        $ordno = $hasil['orderno'];
                        $corno = $hasil['Corno'];
                        $shpno = $hasil['cusno'];
                        if ($corno == "")
                            $corno = "-";
                        $orderstatus = $hasil['ordtype'];
                        $ordflg = $hasil['ordflg'];
                        $orderdate = $hasil['orderdate'];
                        $periode = $hasil['orderprd'];
                        $orddate = substr($orderdate, -2) . "/" . substr($orderdate, 4, 2) . "/" . substr($orderdate, 0, 4);
                        if ($orderstatus == 'R') {
                            $ordsts = 'Regular';
                        }
                        $oecus = $hasil['OECus'];
                        $shipment = $hasil['Shipment'];
                        $trflg = $hasil['Trflg'];
                        $querycus = "select route from cusmas where cusno='$shpno'";
                        $sqlcus = mysqli_query($msqlcon,$querycus);
                        if ($hasilcus = mysqli_fetch_array($sqlcus)) {
                            $route = $hasilcus['route'];
                        }

                        $urlprint = "<a href='prtorderpdf.php?ordno=" . $ordno . "' target=\"new\"> <img src=\"images/print.png\" width=\"20\" height=\"20\" border=\"0\"></a>";

                        echo "<tr class=\"arial11black\" align=\"center\" height=\"25\">";

                        if ($custype == "A") {
                            switch (trim($ordflg)) {
                                case "":
                                    $sts = "Pending";
                                    break;
                                case "1":
                                    $sts = "Completed";
                                    break;
                                case "U":
                                    $sts = "Uncompleted";
                                    break;
                            }
                            echo "<td class=\"arial11redbold\">" . $sts . "</td>";
                        }

                        echo "<td>" . $orddate . "</td><td>" . $corno . "</td>";

                        echo "<td>" . $ordno . "</td>";
                        echo "<td>" . $shpno . "</td><td>" . $ordsts . "</td>";
                        echo "<td class=\"lasttd\">";
                        if ($type != "v") {

                            if (($custype == "A" & $ordflg == "") || $custype != "A" || ($custype == "A" & $ordflg != "" & $route == 'D')) {
                                $edit = paramEncrypt("action=edit&ordno=$ordno&shpno=$shpno&corno=$corno");
                                $delete = paramEncrypt("action=delete&ordno=$ordno&shpno=$shpno&corno=$corno");
                                if ($ordflg != "R") {
                                    echo "<a href='orderreg.php?" . $edit . "' > <img src=\"images/edit.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                                }
                                echo "<a href='orderreg.php?" . $delete . "' onclick=\"return confirm('" . get_lng($_SESSION["lng"], "W0007") . "')\"> <img src=\"images/delete.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                            }
                        }

                        if ($custype == "A" & $ordflg != "") {
                            if ($route != 'D') {
                                $view = paramEncrypt("action=View&ordno=$ordno&shpno=$shpno&corno=$corno&orddate=$orderdate");
                                echo "<a href='orderreg.php?" . $view . "' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                            }
                        }

                        if ($type == "v" && $custype != "A") {
                            $view = paramEncrypt("action=ViewDlr&ordno=$ordno&shpno=$shpno&periode=$periode&corno=$corno&orddate=$orderdate");
                            echo "<a href='orderreg.php?" . $view . "' > <img src=\"images/view.png\" width=\"20\" height=\"20\" border=\"0\"></a>";
                        }
                        echo $urlprint;
                        echo "<td >";
                    }
                    include("pager.php");
                    if ($mcount > $mper_page) {
                        echo "<tr height=\"30\"><td colspan=\"" . $colnum . "\" align=\"right\" class=\"lasttd\"><div id=\"pagination\" >";
                        $fld = "mpage";
                        pagingfld($query, $mper_page, $num, $mpage, $fld);
                        echo "</div></td></tr>";
                    }

                    echo "<tr>";echo '<td colspan="' . $colnum . '" class="lasttd" align="right"><img src="images/print.png" width="20" height="20"> <span class="arial11redbold">= ' . get_lng($_SESSION["lng"], "L0014") . '</span>,<img src="images/edit.png" width="20" height="20"><span class="arial11redbold"> = ' . get_lng($_SESSION["lng"], "L0015") . '</span>,  <img src="images/delete.png" width="20" height="20"><span class="arial11redbold">= ' . get_lng($_SESSION["lng"], "L0016") . '</span></td>';
                    ?>
                </tr>
            </table>


            <div id="dialog-form" title="<?php echo get_lng($_SESSION["lng"], "L0035");/*New Regular Order*/ ?>"  style="display: none; >
                 <p class="validateTips"><?php echo get_lng($_SESSION["lng"], "L0036"); ?><!--All form fields are required.--></p>


                <form>
                    <table width="97%" border="0"   cellspacing="0" cellpadding="0">
                        <tr class="arial11blackbold">
                            <td width="3%">&nbsp;</td>
                            <td width="27%" class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0003"); ?><!--Customer Number--></td>
                            <td width="3%">:</td>
                            <td width="23%"><? echo $cusno ?></td>
                            <td width="3%"></td>
                            <td width="16%" class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0004"); ?><!--Customer Name--></td>
                            <td width="1%">:</td>
                            <td width="24%"><? echo $cusnm ?></td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
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
                            <td class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0040"); ?><!--Order Date--></td>
                            <td>:</td>
                            <td><?php
                                $orddt = date("d-m-Y");
                                echo "<input name=\"orddate\" type=\"text\"  id=\"orddate\" class=\"arial11blackbold\" readonly=\"true\"  maxlength=\"10\" size=\"10\" value=" . $orddt . ">";
                                ?></td>
                            <td></td>
                            <td><span class="arial11greybold"><?php echo get_lng($_SESSION["lng"], "L0039"); ?><!--Denso Order Number--></span></td>
                            <td>&nbsp;</td>
                            <td><input type="text" name="orderno" id="orderno" readonly="true" class="arial11grey"></td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0042"); ?><!--Po Number--></span></td>
                            <td>:</td>
                            <td  class="arial11blackbold"><input type="text" name="txtCorno" id="txtCorno" class="arial11blackbold" maxlength="20" size="20"></td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                            <td  class="arial11blackbold">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold">
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold"><?php echo get_lng($_SESSION["lng"], "L0043"); ?><!--Ship To--></span></td>
                            <td>:</td>
                            <td colspan="5"  class="arial11blackbold">
                                <?php
                                $qryshp = "SELECT cusmas.cusno, cusmas.ESCA1, cusmas.ESCA2, cusmas.ESCA3,cusmas.OECus,  cusrem.curcd, cusrem.remark FROM `cusmas` LEFT JOIN cusrem ON cusmas.cusno = cusrem.cusno  where  trim(cusmas.cust3) ='" . $cusno . "' order by cusmas.cusno";
                                $sqlshp = mysqli_query($msqlcon,$qryshp);
                                echo '<select name="txtShpNo" id="txtShpNo" " style="width: 300px">';
                                echo '<option value="" ></option>';
                                while ($hasil = mysqli_fetch_array($sqlshp)) {
                                    $vcusno = $hasil['cusno'];
                                    $vremark = $hasil['remark'];
                                    $vcurcd = $hasil['curcd'];
                                    $voecus = $hasil['OECus'];
                                    if (strtoupper($voecus) != 'Y') {
                                        $voecus = 'N';
                                    }
                                    $gabung = $vcusno . ' - ' . $vremark . '  (' . $vcurcd . ')';
                                    echo '<option value=' . $vcusno . '|' . $voecus . '>' . $gabung . '</option>';
                                }
                                echo '</select>';
                                ?>
                            </td>
                        </tr>
                        <tr class="arial11blackbold" id="result_tr" ">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td colspan="5"  class="arial11blackbold">&nbsp;</td>
                        </tr>
                        <tr class="arial11blackbold" id="result_tr1" >
                            <td>&nbsp;</td>
                            <td><span class="arial11redbold">Shipment Mode</span></td>
                            <td>:</td>
                            <td colspan="5"  class="arial11blackbold"><label>
                                    <select name="shipment" id="shipment">
                                        <option value="S" selected>Sea</option>
                                        <option value="A">Air</option>
                                    </select>
                                </label></td>
                        </tr>


                    </table>
                </form>


            </div>



        </div>

        <div id="footerMain1">
            <ul>
                <!-- Disable by Zia
                     
                          
                -->
            </ul>

            <div id="footerDesc">

                <p>
                    Copyright &copy; 2023 DENSO . All rights reserved

            </div>
        </div>

</body>
</html>
