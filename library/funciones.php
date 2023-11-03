<?php
function send_to($url)  // Función que sirve para redireccionar a otra página
{    header("Location: $url");
    die();
}

function database_connect($username, $password) // Función que crea la conexion con la base de datos gestio_incidencies.
{
    //username "jalvabot"
    //password "Xf4,5iB8£9q3%"
    $password_hash = hash("sha256", $password);
    return mysqli_connect("127.0.0.1", $username, "$password_hash", "gestio_incidencies");
}

function test_database_connection($username, $password)
{
    $connect = database_connect($username, $password); // Función que hace una prueba para saber si hace bien la conexión y debugging.
    if (!$connect) 
    {
        echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
        echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    echo "Éxito: Se realizó una conexión apropiada a MySQL! La base de datos es genial." . PHP_EOL;
    echo "Información del host: " . mysqli_get_host_info($connect) . PHP_EOL;

    mysqli_close($connect);
}
?>