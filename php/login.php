<?php
include "../library/User.php";
include "../library/helpers.php";
session_start();
    if (isset($_POST["submit"])){

        // test_database_connection();

        $encrypted_password = $_POST["password"];
        $encrypted_password = hashPasswords($encrypted_password);
        $input_email = $_POST["email"];
        

        $env = parse_ini_file('../library/.env');
        $db_user = $env['technician']; // Faig servir el usuari tecnic perque nomes interesa fer un select.
        $db_password = $env['technician_password'];
        
        $user = new User(0,"null","null",$input_email,$encrypted_password,"null"); // Faig una classe user amb la informació que tinc, email i password.
        
        if($user->login("technician", $input_email, $encrypted_password)) // Executa la consulta a la base de dades, i retorna true si funciona o false si falla.
        {
            $result = $user->getUserProperties();
            $_SESSION["id_user"] = $result["id_user"];
            $_SESSION["email"] = $result["email"];
            $_SESSION["password"] = $result["password"];
            $_SESSION["role"] = $result["role"];
            // toUrl("/Gestio_incidencies/php/menu.php");
        }
        else
        {
            $_SESSION = array();
            SESSION_DESTROY();
            toUrl("/Gestio_incidencies/html/login.html");
        }

        echo $_SESSION["id_user"] . " " . $_SESSION["email"] . " " . $_SESSION["password"] . " " . $_SESSION["role"];

    }
?>