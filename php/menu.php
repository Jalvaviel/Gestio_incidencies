<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
        <header>
            <h1>Menu</h1>
        </header>
        <nav>
            <?php 
                SESSION_START();
                $rol = $_SESSION["role"];
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