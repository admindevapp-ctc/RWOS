<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('../../core/ctc_init.php'); // add by CTC

$code = strtolower(ctc_get_session_comp());
$property = $code.'.sup.php.Property';

include $property;

function get_sup_config($param){
    global $config;
    if(isset($config[$param])){
        return $config[$param];
    }else{
         return "";
    }
}
?>
