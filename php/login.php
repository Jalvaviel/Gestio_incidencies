<?php
include "../library/User.php";
include "../library/helpers.php";

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
        echo "<script>
        if(confirm(\"Credencials incorrectes!\")){
            window.location.replace(\"../html/login.html\")
        }
        </script>";
    }
}
else
{
    testDatabaseConnection();
    echo "<h1>hola</h1>";
}
?>