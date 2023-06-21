<?php

function ctc_get_customer($cusno){
    $comp = ctc_get_session_comp();
    $ctcdb = new ctcdb();
    $sql = "SELECT * FROM cusmas where Owner_Comp='$comp' and Cusno='$cusno' ";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_ASSOC);

    return $result;
}