<?php
/**Funció toUrl
 * És una funció bàsica que s'encarrega de redireccionar a 
   altres pàgines.
 */
function toUrl($url)
{    
    echo "<script> location.href = '$url'</script>";
    die();
}

/**Funció databaseConnect
 * Estableix la conexió a la base de dades amb el usuari $type,
   que és un usuari que hem creat amb els permisos adients al rol.
 * El parse_ini_file agafa els passwords del arxiu
   .env i els usernames.
 */
function databaseConnect($type)
{
    assert($type == 'login' or $type == 'worker' or $type=='technician' or $type=='admin','$Error insertant dispositiu, potser no hi tens permissos?');
    $env = parse_ini_file('.env');
    $host = "mysql";
    $db_user = $env[$type];
    $db_password = $env[$type . '_password'];
    $db_name = "gestio_incidencies";
    $db_port = 3306;
    return mysqli_connect($host, "root", "javierCastelblanqueGerardPuigOrtega", $db_name, $db_port);
}   

/**Funció testDatabaseConnection
 * Serveix principalment per veure si dona algún
   error al crear la sessió a la base de dades.
 */
function testDatabaseConnection()
{
    $env = parse_ini_file('.env');
    $db_user = $env['admin'];
    $db_password = $env['admin_password'];
    echo $db_user . " " . $db_password;
    $connect = mysqli_connect("172.18.0.2", "root", "javierCastelblanqueGerardPuigOrtega");    
    if (!$connect) 
    { 
        echo "Error: No s'ha pogut connectar a MySQL" . PHP_EOL;
        echo "errno de depuració: " . mysqli_connect_errno() . PHP_EOL;
        echo "error de depuració: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    echo "Èxit: S'ha realitzat una connexió apropiada a MySQL! La base de dades és genial." . PHP_EOL;
    echo "Informació del host: " . mysqli_get_host_info($connect) . PHP_EOL;

    mysqli_close($connect);
}

/**Funció hashPasswords
 * Serveix per fer un hash amb el CRYPT_BLOWFISH .
 */
function hashPasswords($password)
{
    $env = parse_ini_file('.env');
    return password_hash($password,PASSWORD_BCRYPT);
}

?>