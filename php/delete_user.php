<?php
    include "../library/User.php";
    session_start();
    if($_SESSION['role']=='admin') {
        $user = new User($_GET['id_useri'],"","","","","");
        $user->delete($_SESSION['role']);
        toUrl("./users_page");
    }