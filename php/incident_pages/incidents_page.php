<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incidents</title>
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
        <a href="../login.php" id="profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i>Perfil</a>
        <a href="../../html/login.html" id="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>Surt</a>
    </nav>
</header>
<nav class="menu">
<?php
session_start();
include "../../library/Incident.php";
include "../../library/helpers.php";
if(empty($_SESSION)){
    toUrl('../../html/login.html');
}
switch($_SESSION['role']){
    case 'admin':
    case 'technician':
        show_all_incidents($_SESSION['role']);
        break;
    case 'worker':
        show_all_incidents($_SESSION['role']);
}
function show_all_incidents($role) : void
{
    $connect = databaseConnect($role);
    $statement = $connect->prepare("SELECT * FROM gestio_incidencies.incidents");
    // Control de consultes
    if (!$statement) {
        echo "Error preparant consulta.";
    }
    $result = $statement->execute();
    if (!$result) {
        echo "Error obtenint resultats.";
    }
    $incidents = get_all_incidents($statement);
    if($role == 'admin' || $role == 'technician'){
        print_admin_table($incidents);
    }
    // Resultat i llista d'usuaris
    else{
        print_table($incidents);
    }
    $connect->close();
}
function print_admin_table($incidents) : void
{
    echo "<table>";
    echo "<tr><th><strong>ID Incident</strong></th>
        <th><strong>Descripció</strong></th>
        <th><strong>Estat</strong></th>
        <th><strong>Data</strong></th>
        <th><strong>ID Usuari</strong></th></tr>";

    foreach ($incidents as $incident) {
        $incident_assoc = $incident->getProperties();
        echo "<tr>";
        foreach ($incident_assoc as $key => $value) {
            echo "<td class='llista'>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<a href='insert_incident.html' id='insert'>Inserta un nou incident</a>";
}
function get_all_incidents($statement) : array
{
    $incidents = [];
    $row = $statement->get_result();
    while ($incidentData = $row->fetch_assoc()) {
        $incident = new Incident(
            $incidentData['id_incident'],
            $incidentData['description'],
            $incidentData['status'],
            $incidentData['date'],
            $incidentData['id_user'],
        );
        $incidents[] = $incident;
    }
    return $incidents;
}




