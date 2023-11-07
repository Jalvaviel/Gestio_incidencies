<?php
include "../library/helpers.php";
SESSION_START();
if(!isset($_SESSION["role"]) || EMPTY($_SESSION["role"]))
{
    to_url("/gestio_incidencies/html/login.html");
}
else
{
    $rol = $_SESSION["role"];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Menu</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <header>
            <h1>Menu</h1>
        </header>
        <nav>
            <?php     
                if(strcmp($rol,"treballador") == 0 || strcmp($rol,"tecnic") == 0 || strcmp($rol,"admin") == 0)
                {
            ?>
                    <div>
                        <a href="">Mostrar incidencies</a>
                    </div>
                    <div>
                        <a href="">Reportar incidència</a>
                    </div>
            <?php
                }
                if(strcmp($rol,"tecnic") == 0 || strcmp($rol,"admin") == 0)
                {
            ?>
                    <div>
                        <a href="">Modificar incidències</a>
                    </div>
                    <div>
                        <a href="">Borrar incidències</a>
                    </div>
            <?php
                }
                if(strcmp($rol,"admin") == 0)
                {
            ?>
                    <div>
                        <a href="">Crear usuari</a>
                    </div>
                    <div>
                        <a href="">Modificar usuari</a>
                    </div>
                    <div>
                        <a href="">Borrar Usuari</a>
                    </div>
                    <div>
                        <a href="">Crear dispositius</a>
                    </div>
                    <div>
                        <a href="">Modificar dispositius</a>
                    </div>
                    <div>
                        <a href="">Borrar dispositius</a>
                    </div>
            <?php
                }
            ?>

        </nav>
        <body>

        </body>
        <footer>

        </footer>
    </body>
</html>
<?php 
}
?>