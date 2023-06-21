<?php

function ctc_get_session_timezone(){
    if(isset($_SESSION['timezone'])){
        return $_SESSION['timezone'];
    }
}

function ctc_get_session_county(){
    if(isset($_SESSION['county'])){
        return $_SESSION['county'];
    }
}

function ctc_get_session_comp(){
    if(isset($_SESSION['comp'])){
        return $_SESSION['comp'];
    }
}

function ctc_get_session_erp(){
    if(isset($_SESSION['erp'])){
        return $_SESSION['erp'];
    }
}

function ctc_get_session_cusno(){
    if(isset($_SESSION['cusno'])){
        return $_SESSION['cusno'];
    }
}

function ctc_get_session_cusnm(){
    if(isset($_SESSION['cusnm'])){
        return $_SESSION['cusnm'];
    }
}

function ctc_get_session_tablename(){
    if(isset($_SESSION['tablename'])){
        return $_SESSION['tablename'];
    }
}

function ctc_get_session_tablenamesup(){
    if(isset($_SESSION['tablenamesup'])){
        return $_SESSION['tablenamesup'];
    }
}

function ctc_get_session_awstable(){
    if(isset($_SESSION['awstable'])){
        return $_SESSION['awstable'];
    }
}

function ctc_get_session_awstablenamesup(){
    if(isset($_SESSION['awstablenamesup'])){
        return $_SESSION['awstablenamesup'];
    }
}