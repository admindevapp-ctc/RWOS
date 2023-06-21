<?php

// ctc_user

function ctc_get_users(){

    $ctcdb = new ctcdb();
    $sql = 'SELECT * FROM userid';

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();

    $result = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function ctc_get_user($username){

    $ctcdb = new ctcdb();
    $sql = "SELECT * FROM userid where UserName='$username'";

    $sth = $ctcdb->db->prepare($sql);
    $sth->execute();

    $result = $sth->fetch(PDO::FETCH_ASSOC);

    return $result;
}
