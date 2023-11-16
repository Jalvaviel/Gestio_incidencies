<?php
    include "../library/User.php";
    session_start();
    if($_SESSION['role']=='admin') {
        $user = new User($_SESSION['id_user'],"","","","","");
        $user->delete('admin');
        toUrl("./users_page");
    }