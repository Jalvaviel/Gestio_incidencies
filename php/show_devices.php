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
        <a href="show_users.php" class="mainmenu">Usuaris</a>
        <a href="show_incidents.php" class="mainmenu">Incidències</a>
        <a href="show_devices.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="login.php" id="profile"><img src="../png/user.png" alt="profile" width="41"/>Perfil</a>
        <a href="../html/login.html" id="logout"><img src="../png/exit.png" alt="logout" width="41"/>Surt</a>
    </nav>
</header>
<nav class="menu">
    <table>
        <tr>
            <th><strong>ID Equip</strong></th>
            <th><strong>Sistema Operatiu</strong></th>
            <th><strong>Codi</strong></th>
            <th><strong>Descripció</strong></th>
            <th><strong>Sala</strong></th>
            <th><strong>IP</strong></th>
            <th><strong>Modificar</strong></th>
        </tr>
        <?php
        include "../library/Device.php";
        if(empty($_SESSION)){
            toUrl('../html/login.html');
        }
        try {
            $all_users = loadDevices('admin', 1); // TODO change to variable in session from database
            foreach($all_users as $user) {
                $user_assoc = $user->getProperties();
                echo "<tr>";
                foreach($user_assoc as $key => $value){ // TODO show data of the device depending on the type of user
                    echo "<td class='llista'>$value</td>";
                }
                echo '/></a></td>';
                echo "</tr>";
            }
        }catch (Exception $e) {
            echo "<div class='error'>Error obtenint resultats.</div>";
        }
        ?>
    </table>
</nav>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>

<?php
function loadDevices($type, $id_device): array
{

    $connect = databaseConnect($type);
    $statement = false;

    switch ($type) { // Comprova el tipus d'usuari que vol fer la consulta.
        case 'admin':
        case 'technician':
            $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices");
            break;
        case 'worker':
            $statement = $connect->prepare("SELECT os,code,description,room FROM gestio_incidencies.devices");
            break;
    }
    if (!$statement) {
        echo "<div class='error'>Error preparant consulta.</div>";
    }
    $result = $statement->execute();
    if (!$result) {
        echo "<div class='error'>Error obtenint resultats.</div>";
    }
    $devices = [];
    $row = $statement->get_result();

    while ($deviceData = $row->fetch_assoc()) {
        $device = new Device(
            $deviceData['id_device'],
            $deviceData['os'],
            $deviceData['code'],
            $deviceData['description'],
            $deviceData['room'],
            $deviceData['ip']
        );
        $devices[] = $device;
    }
    return $devices;
}
?>