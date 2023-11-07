<?php
include "library/helpers.php";
session_start();
// Mira si hi ha una sessió creada ja
if(!ISSET($_SESSION["user_id"]) || EMPTY($_SESSION["user_id"]) || $_SESSION["email"] || EMPTY($_SESSION["email"]) || !ISSET($_SESSION["role"]) || EMPTY($_SESSION["role"]))
{
    toUrl("/Gestio_incidencies/html/login.html");
}
else
{
    toUrl("/Gestio_incidencies/php/login.php");
}

?>