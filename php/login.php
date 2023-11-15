<?php
include "../library/User.php";

session_start();
    if (isset($_POST["submit"])){

        $input_password = $_POST["password"];
        $input_email = $_POST["email"];
        
        $user = new User(0,"null","null",$input_email,$input_password,"null");
        
        if($user->login("technician"))
        {
            $result = $user->getProperties();
            $_SESSION["id_user"] = $result["id_user"];
            $_SESSION["email"] = $result["email"];
            $_SESSION["password"] = $result["password"];
            $_SESSION["role"] = $result["role"];
            toUrl("/Gestio_incidencies/php/menu.php");
        }
        else
        {
            $_SESSION = array();
            SESSION_DESTROY();
            toUrl("../html/login.html");
        }

        echo $_SESSION["id_user"] . " " . $_SESSION["email"] . " " . $_SESSION["password"] . " " . $_SESSION["role"];
    }
?>