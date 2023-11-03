<?php
include "funciones.php";
include "treballador.php";
include "tecnic.php";

function insert_into_users($conexio, $id, $name, $surname, $email, $password, $role)
{
    $enc_passwd = hash("sha256", $password);
    $connect = database_connect();
    mysqli_query($connect, "insert $id, $name, $surname, $email, $enc_passwd, $role into gestio_incidencies.users");
    mysqli_close($connect);
}

function insert_into_devices($conexio, $id,$os, $code, $description, $room, $ip)
{
    $connect = database_connect();
    mysqli_query($connect, "insert $id, $os, $code, $description, $room, $ip into gestio_incidencies.incidents");
    mysqli_close($connect);
}
?>