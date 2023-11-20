<?php
    include "../../library/helpers.php";
    include "../../library/User.php";
    session_start();
    if($_SESSION['role']=='admin') {
        $user = new User($_POST['deleteuser'],"","","","","");
        $user->delete($_SESSION['role']);
        toUrl("./users_page");
    }