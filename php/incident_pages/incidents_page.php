<?php
session_start();
include "../../library/User.php";
include "../../library/Incident.php";
include "../../library/helpers.php";
if(empty($_SESSION)){
    toUrl('../../html/login.html');
}
?>
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
        <a href="../user_pages/users_page.php" id="profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i>Perfil</a>
        <a href="../../html/login.html" id="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>Surt</a>
    </nav>
</header>
<nav class="menu">
<?php
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
    if (!$statement) 
    {
        echo "Error preparant consulta.";
    }
    $result = $statement->execute();
    if (!$result) 
    {
        echo "Error obtenint resultats.";
    }
    $incidents = get_all_incidents($statement);
    if($role == 'admin' || $role == 'technician')
    {
        print_admin_table($incidents);
    }
    // Resultat i llista d'usuaris
    else
    {
        print_table($incidents);
    }
    $connect->close();
}
function print_admin_table($incidents) : void
{
    $user = new User(0, "null", "null", "null", "null", "worker");
    echo "<table>";
    echo "<tr><th><strong>ID Incident</strong></th>
        <th><strong>Descripció</strong></th>
        <th><strong>Estat</strong></th>
        <th><strong>Data</strong></th>
        <th><strong>Usuari</strong></th>
        <th><strong>Modificar</strong></th></tr>";

    foreach ($incidents as $incident) {
        $incident_assoc = $incident->getProperties();
        echo "<tr>";
        foreach ($incident_assoc as $key => $value) {
            if($key=='id_user'){
                $user->__construct($incident_assoc['id_user'], "null", "null", "null", "null", "worker");
                $user->select($_SESSION['role']);
                $email_result = $user->getProperties()['email'];
                echo "<td class='llista'>$email_result</td>";
            }else{
                echo "<td class='llista'>$value</td>";
            }
        }
        $current_incident_id = $incident_assoc['id_incident'];
        $current_incident_description = str_replace(' ','_',$incident_assoc['description']);
        $current_incident_stat = $incident_assoc['stat'];
        $current_incident_date = $incident_assoc['date'];
        $current_incident_user = $incident_assoc['id_user'];
        echo "<td>";
        if ($_SESSION['role']=='admin'){
            echo "<button onclick=deleteFunc('$current_incident_id') id=\"deletebuttona\"><i class=\"fa-solid fa-trash\"></i></button>";
        }
        echo "<button onclick=updateFunc('$current_incident_id','$current_incident_description','$current_incident_stat','$current_incident_date','$current_incident_user') id=\"updatebuttona\"><i class=\"fa-solid fa-gear\"></i></button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<a href='insert_incident.html' id='insert'>Inserta un nou incident</a>";
}

function print_table($incidents) : void
{
    echo "<table>";
    echo "<tr>
        <th><strong>Descripció</strong></th>
        <th><strong>Estat</strong></th>
        <th><strong>Data</strong></th>
        <th><strong>Usuari</strong></th></tr>";
        foreach ($incidents as $incident) {
            $incident_assoc = $incident->getProperties();
            foreach ($incident_assoc as $key => $value) {
                if($key == 'id_user' && $value == $_SESSION['id_user']){
                    $current_incident_description = $incident_assoc['description'];
                    $current_incident_stat = $incident_assoc['stat'];
                    $current_incident_date = $incident_assoc['date'];
                    $current_incident_user = $incident_assoc['id_user'];
                    echo "<tr>";
                    echo "<td>$current_incident_description</td>";
                    echo "<td>$current_incident_stat</td>";
                    echo "<td>$current_incident_date</td>";
                    echo "<td>$current_incident_user</td>";
                    echo "</tr>";
                }
            }
        }
    echo "</table>";
    echo "<a href='insert_incident.html' id='insert'>Inserta un nou incident</a>";
}
function get_all_incidents($statement) : array
{
    $incidents = [];
    $row = $statement->get_result();
    while ($incidentData = $row->fetch_assoc()) 
    {
        $incident = new Incident(
            $incidentData['id_incident'],
            $incidentData['description'],
            $incidentData['stat'],
            $incidentData['date'],
            $incidentData['id_user'],
        );
        $incidents[] = $incident;
    }
    return $incidents;
}
?>
</nav>
</body>
<script>
    function deleteFunc(id_incidenti)
    {
        let form_del;
        let input;
        if (confirm("Estàs segur d'esborrar aquest incident?"))
        {
            form_del = document.createElement('form');
            form_del.setAttribute('method', 'POST');
            form_del.setAttribute('action', './delete_incident.php');
            input = document.createElement('input');
            input.setAttribute('name','deleteincident')
            input.setAttribute('type','hidden');
            input.setAttribute('value',id_incidenti);
            form_del.appendChild(input);
            document.body.appendChild(form_del);
            form_del.submit();
        }
    }
    function updateFunc(id_incidenti,descriptioni,stati,datei,id_useri)
    {
        const form_upd = document.createElement('form');
        form_upd.setAttribute('method', 'POST');
        form_upd.setAttribute('action', './update_incident_form.php');

        const inputs =
            {
                id_incidenti,descriptioni,stati,datei,id_useri
            };

        for (const [key, value] of Object.entries(inputs))
        {
            const input = document.createElement('input');
            input.setAttribute('name', key);
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', value);
            form_upd.appendChild(input);
        }

        document.body.appendChild(form_upd);
        form_upd.submit();
    }

</script>
</html>




