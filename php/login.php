<?php
include "../library/User.php";
include "../library/helpers.php";

$_SESSION = array(0);
session_destroy();
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
        toUrl("./user_pages/users_page.php");
    }
    else
    {
        $_SESSION = array();
        SESSION_DESTROY();
       toUrl("../html/login.html");
    }

}
else{
    echo "<h1>hola</h1>";
}
?>