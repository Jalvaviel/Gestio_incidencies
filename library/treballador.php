<?php
include "helpers.php";
function insert_into_incidents($connect, $id, $description, $status, $date)
{
    mysqli_query($connect, "insert $id, $description, $status, $date into gestio_incidencies.incidents");
    mysqli_close($connect);
}
function select_from_incidents($connect)
{
    
}
?>