<?php
include "../library/funciones.php";
    if (isset($_POST["submit"])){
        $user = $_POST["email"];
        $password = $_POST["password"];
        test_database_connection($user, $password);
    }
?>