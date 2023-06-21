<?php


function ctc_get_root_path(){
    $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

    $base_dir  = __DIR__; // /opt/lampp/htdocs/WebOrder/core/  C:\xampp\htdocs\Order-test\core
    $doc_root  = preg_replace("!${_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']); # ex: C:/xampp/htdocs/Order-test/Order-SG/admin/maincusadm.php
    $base_url  = preg_replace("!^${doc_root}!", '', $base_dir); # ex: /WebOrder/core C:\xampp\htdocs\Order-test\core
    $protocol  = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $dir = new myUrl( );
    $flodername = $dir->real_directories[0];
    $port      = $_SERVER['SERVER_PORT'];
    $disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
    $domain    = $_SERVER['SERVER_NAME'];# Ex: 192.168.64.3
    
    $full_url  = "${protocol}://${domain}${disp_port}/${flodername}"; 
    return $full_url;
    //$fixpath = 'https://10.10.60.104/order-test/Order-SG';
    //return $fixpath;
}

function ctc_get_logo(){
    echo '<div id="header">';
    echo '<img src="'.ctc_get_root_path().'/images/denso.jpg" width="206" height="54" />';
    echo '<strong style="font-size: x-large;vertical-align: 18;">&nbsp;'.ctc_get_session_county().'</strong>';
    echo '</div>';
}


function ctc_get_logo_new(){
    echo '<div id="header">';
    echo '<img src="'.ctc_get_root_path().'/images/denso.jpg" width="206" height="54" />';
    echo '<strong style="font-size: x-large">&nbsp;'.ctc_get_session_county().'</strong>';
    echo '</div>';
}

class myUrl
{
    var $protocol;
    var $site;
    var $thisfile;
    var $real_directories;
    var $num_of_real_directories;
    var $virtual_directories = array();
    var $num_of_virtual_directories = array();
    var $baseurl;
    var $thisurl;
    
    function myUrl()
    {
        $this->protocol = ( isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" ) ? 'https' : 'http';
        $this->site = $this->protocol . '://' . $_SERVER['HTTP_HOST'];
        $this->thisfile = basename($_SERVER['SCRIPT_FILENAME']);
        $this->real_directories = $this->cleanUp(explode("/", str_replace($this->thisfile, "", $_SERVER['PHP_SELF'])));
        $this->num_of_real_directories = count($this->real_directories);
        $this->virtual_directories = array_diff($this->cleanUp(explode("/", str_replace($this->thisfile, "", $_SERVER['REQUEST_URI']))),$this->real_directories);
        $this->num_of_virtual_directories = count($this->virtual_directories);
        $this->baseurl = $this->site . "/" . implode("/", $this->real_directories) ;
        $this->thisurl = $this->baseurl . implode("/", $this->virtual_directories) ;
        if( strcmp ( substr( $this->thisurl, -1 ), "/" ) != 0 )
        $this->thisurl = $this->thisurl."/";
        if( strcmp ( substr( $this->baseurl, -1 ), "/" ) != 0 )
        $this->baseurl = $this->baseurl."/";
    }
    function cleanUp($array)
    {
        $cleaned_array = array();
        foreach($array as $key => $value)
        {
            $qpos = strpos($value, "?");
            if($qpos !== false){
                break;
            }
            if($key != "" && $value != ""){
                $cleaned_array[] = $value;
            }
        }
        return $cleaned_array;
    }
}