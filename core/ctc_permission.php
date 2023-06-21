<?php

require_once('ctc_session.php');

function ctc_checkuser_permission($loginpath) {
    if (isset($_SESSION['cusno'])) {
        if ($_SESSION['redir'] == 'Order-SG') {
            
        } else {
            echo "<script> document.location.href='../" . redir . "'; </script>";
        }
    } else {
        header("Location:" . $loginpath);
    }
}

function ctc_checkadmin_permission($loginpath) {
    if (isset($_SESSION['cusno'])) {
        if ($_SESSION['redir'] == 'Order-SG') {
//            header("Location:../main.php");
        } else {
            echo "<script> document.location.href='../../" . redir . "'; </script>";
        }
    } else {
        header("Location:" . $loginpath);
    }
}
