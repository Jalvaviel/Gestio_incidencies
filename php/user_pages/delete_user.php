<?php
session_start();
include "../../library/helpers.php";
include "../../library/User.php";

if($_SESSION['role']=='admin') {
    $user = new User($_POST['deleteuser'],"","","","","");
    $user->delete($_SESSION['role']);
    toUrl("/gestio_incidencies/php/user_pages/users_page.php");
}