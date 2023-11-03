<?php
include "helpers.php";
function insert_into_incidents($conexio, $id, $description, $status, $date)
{
    $connect = database_connect();
    mysqli_query($connect, "insert $id, $description, $status, $date into gestio_incidencies.incidents");
    mysqli_close($connect);
}

function select_from_incidents()
{
    $connexio = database_connect();
    
    mysqli_close($connexio);
}
?>