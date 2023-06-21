<?php

// Set TimeZone
if(isset($_SESSION['timezone'])){
    date_default_timezone_set(ctc_get_session_timezone()); 
}
