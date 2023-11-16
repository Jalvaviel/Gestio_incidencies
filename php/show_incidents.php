<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incidències</title>
    <link rel="stylesheet" href="../css/style_users.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
<header>
    <img id="logo" src="../png/logo-no-background.png" alt="logo" width="200"/>
    <nav id="mainmenu">
        <a href="login.php" class="mainmenu">Usuaris</a>
        <a href="login.php" class="mainmenu">Incidències</a>
        <a href="login.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="login.php" id="profile"><img src="../png/user.png" alt="profile" width="41"/>Perfil</a>
        <a href="../html/login.html" id="logout"><img src="../png/exit.png" alt="logout" width="41"/>Surt</a>
    </nav>
</header>
<nav class="menu">
    <table>
        <tr>
            <th><strong>ID Incidència</strong></th>
            <th><strong>Descripció</strong></th>
            <th><strong>Estat</strong></th>
            <th><strong>Data</strong></th>
            <th><strong>Usuari</strong></th>
            <th><strong>Dispositiu</strong></th>
            <th><strong>Modificar</strong></th>
        </tr>
        <?php
        include "../library/Incident.php";
        if(empty($_SESSION)){
            toUrl('../html/login.html');
        }
        try {
            $all_users = loadUsers('admin', 1); // TODO change to variable in session from database
            foreach($all_users as $user) {
                $user_assoc = $user->getProperties();
                echo "<tr>";
                foreach($user_assoc as $key => $value){ // TODO don't show ids and role for normal users, or at least don't let them change it.
                    echo "<td class='llista'>$value</td>";
                }
                echo '<td><a href=\'login.php\'><img src=\'../png/setting.png\' alt=\'configura\' width=\'25\'/></a></td>';
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
function loadIncidents($type, $id_incident): array
{

    $connect = databaseConnect($type);
    $statement = false;

    switch ($type) { // Comprova el tipus d'usuari que vol fer la consulta.
        case 'admin':
        case 'technician':
            $statement_incident = $connect->prepare("SELECT * FROM gestio_incidencies.incidents");
            break;
        case 'worker':
            $statement_incident = $connect->prepare("SELECT * FROM gestio_incidencies.incidents"); // TODO change access
            break;
    }
    if (!$statement) {
        echo "<div class='error'>Error preparant consulta.</div>";
    }
    $result_incident = $statement_incident->execute();

    if (!$result) {
        echo "<div class='error'>Error obtenint resultats.</div>";
    }
    $incidents = [];
    $row_incident = $statement_incident->get_result();
    // $statement_user = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user =");

    while ($incidentData = $row_incident->fetch_assoc()) {
        $id_incident = $incidentData['id_incident'];
        $incident = new Incident(
            $id_incident,
            $incidentData['description'],
            $incidentData['status'],
            $incidentData['date'],
            $incidentData['id_user'],
            $statement_incident_device = $connect->prepare("SELECT id_device FROM gestio_incidencies.incidents_devices WHERE id_incident = $id_incident");
            $result_incident_device = $statement_incident_device->execute();
            $row_incident_device = $statement_incident_device->get_result();
            while($incident_device_data = $row_incident_device->fetch_assoc());

        );
        $incidents[] = $incident;
    }
    return $incident;
}
?>