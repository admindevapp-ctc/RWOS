<?php

session_start();
require_once '../../core/ctc_init.php'; // add by CTC

if (isset($_SESSION['cusno'])) {
    if ($_SESSION['redir'] == 'Order-SG') {
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
        $comp = ctc_get_session_comp(); // add by CTC
        if ($type != 'a') {
            header('Location: ../main.php');
        }
    } else {
        echo "<script> document.location.href='../../" .
            $redir .
            "'; </script>";
    }
} else {
    header('Location:../../login.php');
}
?>

<html>

<head>
    <title>Denso Ordering System</title>
    <meta http-equiv="X-UA-Compatible" content="IE=9, IE=10, IE=11, IE=EDGE" />
    <!--02/04/2018 P.Pawan CTC-->
    <link rel="stylesheet" type="text/css" href="../css/dnia.css">
    </style>
    <!--[if IE]>
<style type="text/css"> 
#twocolLeft{ padding-top: 0px; }
#twocolRight { zoom: 1; padding-top:10px; }
</style>	
<![endif]-->
    <style type="text/css">
        <!--
        #pagination a {
            list-style: none;
            margin-right: 5px;
            padding: 5px;
            color: #333;
            text-decoration: none;
            background-color: #F3F3F3;
            font-family: Verdana, Geneva, sans-serif;
            font-size: 10px;
        }

        #pagination a:hover {
            color: #FF0084;
            cursor: pointer;
        }

        #pagination a.current {
            list-style: none;
            margin-right: 5px;
            padding: 5px;
            color: #FFF;
            background-color: #000;
        }

        #pagination1 a {
            list-style: none;
            margin-right: 5px;
            padding: 5px;
            color: #333;
            text-decoration: none;
            background-color: #F3F3F3;
            font-family: Verdana, Geneva, sans-serif;
            font-size: 10px;
        }

        #pagination1 a:hover {
            color: #FF0084;
            cursor: pointer;
        }

        #pagination1 a.current {
            list-style: none;
            margin-right: 5px;
            padding: 5px;
            color: #FFF;
            background-color: #000;
        }
        -->
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

		
		

    </style>


    <script type="text/javascript" language="javascript" src="../lib/jquery-1.4.2.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#ConvExcel').click(function() {
                url = 'gettblcusmasXLS.php',
                    window.open(url);

            })

			$('.yes_btn').click(function() {
				$('body').prepend('<div class="loading-screen"><div class="spinner"></div><span class="loading-text">Data validation ongoing. Please wait...</span></div>');
				// $(this).attr('disabled', 'disabled');
			});
			
        });
		

    </script>



</head>

<body>

    <?php ctc_get_logo(); ?>
    <!-- add by CTC -->

    <div id="mainNav">


        <ul>
            <li id="current"><a href="maincusadm.php" target="_self">Administration</a></li>
            <li><a href="Profile.php" target="_self">User Profile</a></li>

            <li><a href="../logout.php" target="_self">Log out</a></li>

        </ul>
    </div>
    <div id="isi">

        <div id="twocolLeft">
            <div class="hmenu">
                <div class="headerbar">Administration</div>
                <?
                $MYROOT = $_SERVER['DOCUMENT_ROOT'];
                $_GET['current'] = "partcatalogue";
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
                        <td width="3%"><img src="../images/calendar.gif" width="16" height="15"></td>
                        <td colspan="5" class="arial21redbold"><?php echo get_lng(
                                                                    $_SESSION['lng'],
                                                                    'M007'
                                                                );
                                                                // //Catalogue Maintenance
                                                                ?> </td>


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

                <form method="POST" enctype="multipart/form-data" name="uploadForm" action='uploadcatalogue.php' id='frmimport'>
                    <table class='arial11'>
                        <tr>
                            <td colspan="3">
                                <?php echo get_lng($_SESSION['lng'], 'L0468');
                                // Download format csv here :
                                ?> <a href="prototype/Catalogue.csv" target="_blank" download><img src="../images/csv.jpg" width="16" height="16" border="0"></a>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo get_lng($_SESSION['lng'], 'L0450');
                                // Upload
                                ?></td>
                            <td>:</td>
                            <td><input type="file" size="45" name="userfile" id="userfile"></td>
                        </tr>
                        <tr>
                            <td><?php echo get_lng($_SESSION['lng'], 'L0470');
                                // File type
                                ?> </td>
                            <td>:</td>
                            <td>
                                <b><?php echo get_lng(
                                        $_SESSION['lng'],
                                        'L0469'
                                    );
                                    // Only CSV file Allowed!
                                    ?></b>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo get_lng($_SESSION['lng'], 'L0471');
                                // First row for header
                                ?></td>
                            <td>:</td>
                            <td>
                                <input type="radio" name="rdfirstrow" value="yesrow"> <?php echo get_lng(
                                                                                            $_SESSION['lng'],
                                                                                            'L0473'
                                                                                        );
                                                                                        // Yes
                                                                                        ?>
                                <input type="radio" name="rdfirstrow" value="norow"> <?php echo get_lng(
                                                                                            $_SESSION['lng'],
                                                                                            'L0474'
                                                                                        );
                                                                                        // No
                                                                                        ?>
                            </td>
                        </tr>
                        <tr style="display:none">
                            <td></td>
                            <td></td>
                            <td>
                                <input type="radio" name="rdreplace" value="add"> <?php echo get_lng(
                                                                                        $_SESSION['lng'],
                                                                                        'L0475'
                                                                                    );
                                                                                    // Add and Replace Partial
                                                                                    ?>
                                <input type="radio" name="rdreplace" value="editall" checked> <?php echo get_lng(
                                                                                                    $_SESSION['lng'],
                                                                                                    'L0476'
                                                                                                );
                                                                                                // Replace All (Delete All first)
                                                                                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <input name="submit"  class="yes_btn" type="submit" value="<?php echo get_lng(
                                                                                $_SESSION['lng'],
                                                                                'L0477'
                                                                            );
                                                                            // Submit
                                                                            ?>">
                                <input type="reset" value=<?php echo get_lng(
                                                                $_SESSION['lng'],
                                                                'L0478'
                                                            );
                                                            // Reset
                                                            ?>>
                            </td>
                        </tr>
                    </table><br />
                    <span class="arial21redbold"><?php echo get_lng(
                                                        $_SESSION['lng'],
                                                        'L0479'
                                                    ); ?></span><br>
                    <span class="arial21redbold"><?php echo get_lng(
                                                        $_SESSION['lng'],
                                                        'L0560'
                                                    ); ?></span>
                </form>

                <?
                include "../db/conn.inc";
                $msg = $_GET['msg'];
                $result = $_GET['result'];
				$temp_table_name = 'cataloguetemp_'.$comp;

				$query = "delete from `$temp_table_name`";
				mysqli_query($msqlcon, $query);
                ?>



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