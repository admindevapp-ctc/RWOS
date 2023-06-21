<style>

#cookiePolicyDialog {
	position: fixed; 
	bottom: 0;
	margin: 4% auto; 
	left: 0;
	right:0;
	z-index: 10;
	width: 80%;
	position: fixed;
	background-color: #FFF;
	padding: 15px 15px;
	box-sizing: border-box;
	font-size: 14px;
	line-height: 1.5em;
	-ms-text-size-adjust: 100%;
	-webkit-text-size-adjust: 100%;
    font-family: Arial, Helvetica, sans-serif;
    border: red;
    border-width: thin;
    border-style: double;
}


#cookiePolicyDialog .cookiePolicyText {
    float: left;
    width: calc(100% - 105px);
    color: #161515;
}

#cookiePolicyDialog .btnGroup {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
}

#cookiePolicyDialog .cookiePolicyBtn {
    color: #FFF;
    border-radius: 3px;
    text-decoration: none;
    box-sizing: border-box;
    font-weight: bold;
    padding: 0;
    height: 32px;
    width: 140px;
    line-height: 32px;
    display: inline-block;
    text-align: center;
}

#cookiePolicyDialog .acceptBtn {
    background-color: #e60028;
}


</style>

<?php
    require_once(__DIR__.'/../language/Lang_Lib.php');

    $url = $_SERVER['REQUEST_URI'];
    if (strpos($url, 'login.php') !== false) {
        if(!isset($_SESSION['lng'])) $_SESSION['lng'] = 'th'; /* Edit by CTC Sippavit 01/09/2020 */
        if(isset($_POST["lg"])) $_SESSION['lng'] = $_POST["lg"];
    }

   
?>


<div id="cookiePolicyDialog" style="display: none;"><storng class="cookiePolicyText"><?php echo get_lng($_SESSION["lng"], "L0438"); ?></strong><span class="btnGroup"><a href="#" class="cookiePolicyBtn acceptBtn" onclick="acceptPolicyCookie(event);"><?php echo get_lng($_SESSION["lng"], "L0439"); ?></a></span></div>

<script>
    var cookiePolicyDialog = document.getElementById('cookiePolicyDialog');
    function checkCookiePolicy(){
        if (document.cookie.split(';').some(function(item) {
            return item.trim().indexOf('cookiePolicy=') == 0
        })) {
            return true;
        }
    }

    function getCookieOwnerComp(){
        var res;
        if (document.cookie.split(';').some(function(item) {
            if(item.trim().indexOf('ownerComp=') == 0){
                res = item.trim().substring(10);
                return true;
            }
        })) {
            return res;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function(event) { 
        
        if(!checkCookiePolicy()){
            cookiePolicyDialog.style.display = 'block';
        }

    });

    function acceptPolicyCookie(){
        document.cookie = 'cookiePolicy=1;expires=Fri, 1 Jan 2100 23:59:59 GMT; path=/';
        cookiePolicyDialog.style.display = 'none';
    }

</script>
