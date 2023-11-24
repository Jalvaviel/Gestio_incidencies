<?php
include "library/helpers.php";
session_start();
// Mira si hi ha una sessió creada ja
if(!ISSET($_SESSION["user_id"]) || EMPTY($_SESSION["user_id"]) || $_SESSION["email"] || EMPTY($_SESSION["email"]) || !ISSET($_SESSION["role"]) || EMPTY($_SESSION["role"]))
{
    toUrl("./html/login.html");
}
else
{
    toUrl("./php/login.php");
}

?>