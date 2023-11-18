<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuaris</title>
    <link rel="stylesheet" href="../css/style_users.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
<header>
    <img id="logo" src="../png/logo-no-background.png" alt="logo" width="200"/>
    <nav id="mainmenu">
        <a href="user_pages/users_page.php" class="mainmenu">Usuaris</a>
        <a href="show_incidents.php" class="mainmenu">Incidències</a>
        <a href="show_devices.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="login.php" id="profile"><img src="../png/user.png" alt="profile" width="41"/>Perfil</a>
        <a href="../html/login.html" id="logout"><img src="../png/exit.png" alt="logout" width="41"/>Surt</a>
    </nav>
</header>
<?php
    session_start();
    if(empty($_SESSION)){
        toUrl('../html/login.html');
    }
    $nom = $_SESSION["name"];
    $cognom = $_SESSION["surname"];
    echo "<h1>Hola $nom $cognom!</h1>";
?>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>
