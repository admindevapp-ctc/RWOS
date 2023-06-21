<?php

session_start();
require_once('core/ctc_init.php'); // add by CTC
require_once('language/Lang_Lib.php'); /*Page : mylogon.php*/

if (trim($_POST['UserName']) == '') {
    $error[] = get_lng($_SESSION['lng'], "W0002")/* '* User Name should be filled' */;
} else {
    if (strlen(trim($_POST['UserName'])) < 3 or strlen(trim($_POST['UserName'])) > 45) {
        $error[] = get_lng($_SESSION['lng'], "W0004")/* '* Invalid user Name, please check again!' */;
    }
}
if ($_POST['Password'] == '') {
    $error[] = get_lng($_SESSION['lng'], "W0006")/* '* Please fill the password field!' */;
}
//dan seterusnya

if ($error) {
    if ($_SESSION['lng'] == "th") {
        echo '<div style="color: #d90000; font-size: 14px;"><br /><b>' .get_lng($_SESSION['lng'], "W0022").'</b><br />' . implode('<br />', $error).'</div>';
    } else {
        echo '<div style="color: #d90000; font-size: 14px; "><br /><b>Error: </b><br />' . implode('<br/>', $error).'</div>';
    }
} else {
    include('db/conn.inc');
    $userName = htmlentities((trim($_POST['UserName'])));
    $password = htmlentities(trim($_POST['Password']));
    $comp = htmlentities(trim($_POST['County']));
    $query = "select * from userid  where trim(userid.UserName) = '$userName' and trim(password)='$password'";
    $result = mysqli_query($msqlcon,$query);

    $query2 = "select * from userid  where trim(userid.UserName) = '$userName' and trim(password)='$password' and trim(Owner_Comp)='$comp'";
   
    $result2 = mysqli_query($msqlcon,$query2);
		
    $no = mysqli_num_rows($result);
    $no2 = mysqli_num_rows($result2);
    if ($no == 0) {
        echo '<div style="color: #d90000;font-size: 14px;"><br />'.get_lng($_SESSION['lng'], "W0001").'</div>'/*"wrong user name or password!"*/;
    } else if($no2 == 0){
        echo '<div style="color: #d90000;font-size: 14px;"><br />'.get_lng($_SESSION['lng'], "W0036").'</div>'; // add by CTC
    } else {
        $rec = mysqli_fetch_array($result2);
		
        $cusno = trim($rec['Cusno']);
        $type = $rec['Type'];
        $com = trim($rec['COM']);
        $redir = trim($rec['Redir']);
	
        $_SESSION['cusno'] = $cusno;
        $_SESSION['type'] = $type;
        $_SESSION['com'] = $com;
        $_SESSION['user'] = $userName;
        $_SESSION['redir'] = $redir;
       //echo "<script type='text/javascript'>alert(\"$redir\");</script>";
       
       // start add session by CTC
       $country = ctc_get_counrty_comp($comp);
       $_SESSION['county'] = $country['Country']; 
       $_SESSION['timezone'] = $country['Time_Zone'];
       $_SESSION['comp'] = $country['Owner_Comp'];
       $_SESSION['erp'] = $country['ERP'];
       // end add session by CTC
    
       echo "<script>document.cookie = 'ownerComp=$comp;expires=Fri, 1 Jan 2100 23:59:59 GMT'</script>";

        echo "<script> document.location.href='" . $redir . "'; </script>";

        //echo $redir;
    }
}


die();
?>