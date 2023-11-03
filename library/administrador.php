<?php
include "helpers.php";
include "treballador.php";
include "tecnic.php";

function insert_into_users($id, $name, $surname, $email, $password, $role)
{
    $env = parse_ini_file('.env');
    $db_user = $env['admin'];
    $db_password = $env['admin_password'];
    $enc_passwd = hash("sha256", $password);
    $connect = database_connect($db_user, $db_password);
    mysqli_query($connect, "insert $id, $name, $surname, $email, $enc_passwd, $role into gestio_incidencies.users");
    mysqli_close($connect);
}

function insert_into_devices($id,$os, $code, $description, $room, $ip)
{
    $env = parse_ini_file('.env');
    $db_user = $env['admin'];
    $db_password = $env['admin_password'];
    $connect = database_connect($db_user, $db_password);
    mysqli_query($connect, "insert $id, $os, $code, $description, $room, $ip into gestio_incidencies.incidents");
    mysqli_close($connect);
}
?>