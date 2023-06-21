<?php
include "lang/Thai.php.Property";
include "lang/English.php.Property";
include "lang/Vietnamese.php.Property";
//include "lang/Chinese.php.Property";
// include "lang/Default.php.Property";

function get_lng($lng,$param){
	
    global $lang;
	
    if(isset($lang[$lng][$param])){
//        echo 'y';
        return $lang[$lng][$param];
    }else{
//        echo 'n';
        if(isset($lang["en"][$param])){
            return $lang["en"][$param];
        }else{
            return "";
        }
    }
}
?>
