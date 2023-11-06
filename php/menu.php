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
                        <a href="">Incidencies</a>
                    </div>
                    <div>
                        <a href="">Reportar incidència</a>
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