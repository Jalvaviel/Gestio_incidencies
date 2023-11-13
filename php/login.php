<?php
include "../library/tecnic.php";
session_start();
    if (isset($_POST["submit"])){
        $user = new User(0,"null","null","null","null","null"); // Crear clase User.

        // test_database_connection();

        $input_email = $_POST["email"];
        $encrypted_password = $_POST["password"];
        $encrypted_password = hashPasswords($encrypted_password);
        

        $env = parse_ini_file('../library/.env');
        $db_user = $env['technician']; // Faig servir el usuari tecnic perque nomes interesa fer un select.
        $db_password = $env['technician_password'];
        
        $user->$email = $_POST["email"];
        $user->$password = $_POST["password"];
        
        if($user->login("technician", $user->$email, $user->$password)) // Executa la consulta a la base de dades, i retorna true si funciona o false si falla.
        {
            $_SESSION["id_user"] = $resultat["id_user"];
            $_SESSION["email"] = $resultat["email"];
            $_SESSION["role"] = $resultat["role"];
            // toUrl("/Gestio_incidencies/php/menu.php");
        }
        else
        {
            $_SESSION = array();
            SESSION_DESTROY();
            toUrl("/Gestio_incidencies/html/login.html");
        }

        mysqli_close($connect);
    }
?>