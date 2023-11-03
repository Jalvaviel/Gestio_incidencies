<?php
function send_to($url)  // Funció que serveix per redireccionar a una altra pàgina.
{    header("Location: $url");
    die();
}

function database_connect()  // Funció que crea la connexió amb la base de dades gestio_incidencies.
{
    return mysqli_connect("127.0.0.1", "jalvabot", "Xf4,5iB8£9q3%", "gestio_incidencies");
}

function test_database_connexion()  // Funció que fa una prova per saber si fa bé la connexió i debugging.
{
    $connect = database_connect(); 
    if (!$connect) 
    {
        echo "Error: No s'ha pogut connectar a MySQL." . PHP_EOL;
        echo "error de depuració: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuració: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    echo "Èxit: S'ha establert una connexió correcta a MySQL! La base de dades a gestio_incidencies és excelent." . PHP_EOL;
    echo "Informació del host: " . mysqli_get_host_info($connect) . PHP_EOL;

    mysqli_close($connect);
}
?>