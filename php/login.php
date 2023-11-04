<?php
include "../library/tecnic.php";
session_start();
    if (isset($_POST["submit"])){
        // test_database_connection();
        $env = parse_ini_file('../library/.env');
        $db_user = $env['technician']; // Faig servir el usuari tecnic perque nomes interesa fer un select
        $db_password = $env['technician_password'];

        $connect = database_connect($db_user, $db_password); // Conexió a la base de dades

        $valors = ["id_user","email","password","role"];
        
        $input_email = $_POST["email"];
        $input_password = $_POST["password"];

        if($resultat = select_from_users($connect, $valors, 'email', $input_email))
        {
            echo $resultat["id_user"] . "<br/>" . $resultat["email"] . "<br/>" . $resultat["password"] . "<br/>";

            $encrypted_password = hash_passwords($input_password);
            
            echo $encrypted_password . " ";
    
            if(strcmp($encrypted_password,$resultat["password"]) == 0 && $input_email == $resultat["email"])
            {
                $_SESSION["id_user"] = $resultat["id_user"];
                $_SESSION["email"] = $resultat["email"];
                $_SESSION["role"] = $resultat["role"];
                if(SESSION_DESTROY())
                {
                    to_url("/Gestio_incidencies/html/menu/");
                }
            }
            else
            {
                $_SESSION = array();
                SESSION_DESTROY();
                to_url("/Gestio_incidencies/html/login.html");
            }
        }
        else
        {
            $_SESSION = array();
            SESSION_DESTROY();
            to_url("/Gestio_incidencies/html/login.html");
        }
        mysqli_close($connect);
    }
?>