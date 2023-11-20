<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update</title>
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
        <a href="users_page.php" class="mainmenu">Usuaris</a>
        <a href="../incident_pages/incidents_page.php" class="mainmenu">Incidències</a>
        <a href="show_devices.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="../login.php" id="profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i>Perfil</a>
        <a href="../../html/login.html" id="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>Surt</a>
    </nav>
</header>

<form name="inset" method="post" action="update_user.php" enctype="multipart/form-data">
    <table>
        <tr>
            <th>Nom</th>
            <th>Cognom</th>
            <th>Correu</th>
            <th>Contrasenya</th>
            <th>Rol</th>
        </tr>
        <tr>
            <?php
            session_start();
            $id_user = $_POST['id_useri'];
            $name = $_POST['namei'];
            $surname = $_POST['surnamei'];
            $email = $_POST['emaili'];
            $role = $_POST['rolei'];
            echo "<td><input name='name' type='text' required='required' value='$name' /></td>";
            echo "<td><input name='surname' type='text' required='required' value='$surname' /></td>";
            echo "<td><input name='email' type='email' required='required' value='$email' /></td>";
            echo "<td><input name='password' type='password'/></td>";
            echo "<td><input name='role' type='text' required='required' value='$role' /></td>";
            ?>
        </tr>
    </table>
    <?php
        echo "<input name='id_user' type='hidden' value='$id_user'/>"
    ?>
    <input id="insert_user" class="button" type="submit" name="submit" value="Actualitza"/>
</form>
</body>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>

