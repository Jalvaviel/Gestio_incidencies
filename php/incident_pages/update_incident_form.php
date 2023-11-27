<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualitza Incident</title>
    <link rel="stylesheet" href="../../css/style_users.css">

    <script src="https://kit.fontawesome.com/8faa35dc4d.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <img id="logo" src="../../png/logo-no-background.png" alt="logo" width="200"/>
    <nav id="mainmenu">
        <a href="../user_pages/users_page.php" class="mainmenu">Usuaris</a>
        <a href="./incidents_page.php" class="mainmenu">Incidències</a>
        <a href="../device_pages/devices_page.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="../user_pages/users_page.php" id="profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i>Perfil</a>
        <a href="../../html/login.html" id="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>Surt</a>
    </nav>
</header>

<form name="inset" method="post" action="./update_incident.php" enctype="multipart/form-data">
    <table>
        <tr>
            <th>Descripció</th>
            <th>Estat</th>
        </tr>
        <tr>
            <?php
            $id_incident = $_POST['id_incidenti'];
            $description = str_replace('_',' ',$_POST['descriptioni']);
            echo "<td><input name='description' type='text' required='required' value='$description' /></td>";
            echo "<td><select name='stat'>
                  <option value='unresolved'>Pendent</option>
                  <option value='resolved'>Resolt</option>
                  </select></td>";
            ?>
        </tr>
    </table>
    <?php
        echo "<input name='id_incident' type='hidden' value='$id_incident'/>"
    ?>
    <input id="update_device" class="button" type="submit" name="submit" value="Actualitza"/>
</form>
</body>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>

