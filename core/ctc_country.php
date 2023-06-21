<?php

// ctc_counrty

function ctc_get_counrty(){

    $ctcdb = new ctcdb();
    $sql = 'SELECT * FROM tm_country';

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();

    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_counrty_comp($comp){

    $ctcdb = new ctcdb();
    $sql = "SELECT * FROM tm_country where Owner_Comp='$comp'";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_ASSOC);

    return $result;
}
