<?php
include "../library/User.php";
echo "<h1>hola</h1>";
$_SESSION = array();
session_start();
if (isset($_POST["submit"])){
    $input_password = $_POST["password"];
    $input_email = $_POST["email"];

    $user = new User(0,"null","null",$input_email,$input_password,"null");

    if($user->login('login'))
    {
        $result = $user->getProperties();
        $_SESSION["id_user"] = $result["id_user"];
        $_SESSION["name"] = $result["name"];
        $_SESSION["surname"] = $result["surname"];
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
else{
    echo "<h1>hola</h1>";
}
?>