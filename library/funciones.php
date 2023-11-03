<?php
function send_to($url)  // Función que sirve para redireccionar a otra página
{    header("Location: $url");
    die();
}

function database_connect() // Función que crea la conexion con la base de datos gestio_incidencies.
{
    return mysqli_connect("127.0.0.1", "jalvabot", "Xf4,5iB8£9q3%", "gestio_incidencies");
}

function test_database_connexion()
{
    $connect = database_connect(); // Función que hace una prueba para saber si hace bien la conexión y debugging.
    if (!$connect) 
    {
        echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
        echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    echo "Éxito: Se realizó una conexión apropiada a MySQL! La base de datos mi_bd es genial." . PHP_EOL;
    echo "Información del host: " . mysqli_get_host_info($connect) . PHP_EOL;

    mysqli_close($connect);
}
?>