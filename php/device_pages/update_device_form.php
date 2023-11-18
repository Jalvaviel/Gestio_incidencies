<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Actualitza Dispositiu</title>
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
        <a href="show_incidents.php" class="mainmenu">Incidències</a>
        <a href="./devices_page.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="../login.php" id="profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i>Perfil</a>
        <a href="../../html/login.html" id="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>Surt</a>
    </nav>
</header>

<form name="inset" method="post" action="./update_device.php" enctype="multipart/form-data">
    <table>
        <tr>
            <th>Sistema Operatiu</th>
            <th>Codi</th>
            <th>Descripció</th>
            <th>Sala</th>
            <th>IP</th>
        </tr>
        <tr>
            <?php
            session_start();
            $id_device = $_POST['id_devicei'];
            $os = $_POST['osi'];
            $code = $_POST['codei'];
            $description = $_POST['descriptioni'];
            $room = $_POST['roomi'];
            $ip = $_POST['ipi'];
            echo "<td><input name='os' type='text' required='required' value='$os' /></td>";
            echo "<td><input name='code' type='text' required='required' value='$code' /></td>";
            echo "<td><input name='description' type='text' required='required' value='$description' /></td>";
            echo "<td><input name='room' type='text' required='required' value='$ip' /></td>"; // NIGGERLICIOUS TODO
            echo "<td><input name='ip' type='text' required='required' value='$room' /></td>";
            ?>
        </tr>
    </table>
    <?php
        echo "<input name='id_device' type='hidden' value='$id_device'/>"
    ?>
    <input id="update_device" class="button" type="submit" name="submit" value="Actualitza"/>
</form>
</body>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>

