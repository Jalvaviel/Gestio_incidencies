<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dispositius</title>
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
<nav class="menu">
<?php
    session_start();
    include "../../library/Device.php";
    if(empty($_SESSION)){
        toUrl('../../html/login.html');
    }
    switch($_SESSION['role']){
        case 'admin':
            break;
        case 'technician':
            show_all_devices($_SESSION['role']);
            break;
        case 'worker':
            show_all_devices($_SESSION['role']);
            break;
    }

    function show_all_devices($role) : void
    {
        $connect = databaseConnect($role);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices");
        // Control de consultes
        if (!$statement) {
            echo "Error preparant consulta.";
        }
        $result = $statement->execute();
        if (!$result) {
            echo "Error obtenint resultats.";
        }
        $devices = get_all_devices($statement);
        if($role == 'admin' || $role == 'technician'){
            print_admin_table($devices);
        }
        // Resultat i llista d'usuaris
        else{
            print_table($devices);
        }
        $connect->close();
    }

    function print_admin_table($devices) : void
    {
        echo "<table>";
        echo "<tr><th><strong>ID Equip</strong></th>
        <th><strong>Sistema Operatiu</strong></th>
        <th><strong>Codi</strong></th>
        <th><strong>Descripció</strong></th>
        <th><strong>Sala</strong></th>
        <th><strong>IP</strong></th>
        <th><strong>Modificar</strong></th></tr>";

        foreach ($devices as $device) {
            $device_assoc = $device->getProperties();
            echo "<tr>";
            foreach ($device_assoc as $key => $value) {
                echo "<td class='llista'>$value</td>";
            }
            $current_device_id = $device_assoc['id_device'];
            $current_device_os = $device_assoc['os'];
            $current_device_code = $device_assoc['code'];
            $current_device_description = $device_assoc['description'];
            $current_device_room = $device_assoc['room'];
            $current_device_ip = $device_assoc['ip'];

            echo "<td>";
            echo "<button onclick=deleteFunc('$current_device_id') id=\"deletebuttona\"><i class=\"fa-solid fa-trash\"></i></button>";
            echo "<button onclick=updateFunc('$current_device_id','$current_device_os','$current_device_code','$current_device_description','$current_device_room','$current_device_ip') id=\"updatebuttona\"><i class=\"fa-solid fa-gear\"></i></button>";
            echo "<button onclick=showIncidentsFunc('$current_device_id') id=\"showincidentbuttona\"><i class=\"fa-solid fa-circle-exclamation\"></i></button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href='insert_device.html' id='insert'>Inserta un nou equip</a>";
    }

    function get_all_devices($statement) : array
    {
        $devices = [];
        $row = $statement->get_result();
        while ($deviceData = $row->fetch_assoc()) {
            $device = new Device(
                $deviceData['id_device'],
                $deviceData['os'],
                $deviceData['code'],
                $deviceData['description'],
                $deviceData['ip'],
                $deviceData['room'],
                $deviceData['id_incident']
                
            );
            $devices[] = $device;
        }
        return $devices;
    }

    function print_table($devices)
    {
        echo "<table>";
        echo "<tr>
            <th><strong>Sistema Operatiu</strong></th>
            <th><strong>Codi</strong></th>
            <th><strong>Descripció</strong></th>
            <th><strong>Sala</strong></th>
            </tr>";

        foreach ($devices as $device) {
            $device_assoc = $device->getProperties();
            echo "<tr>";
            foreach ($device_assoc as $key => $value) {
                if ($key == 'os' || $key == 'code' || $key == 'description' || $key == 'room'){
                    echo "<td class='llista'>$value</td>";
                }
            }
            echo "</tr>";
        }
    }

    ?>
</nav>
</body>
<script>
    function deleteFunc(id_devicei){
        let form_del;
        let input;
        if (confirm("Estàs segur d'esborrar aquest dispositiu?")) {
            form_del = document.createElement('form');
            form_del.setAttribute('method', 'POST');
            form_del.setAttribute('action', './delete_device.php');
            input = document.createElement('input');
            input.setAttribute('name','deletedevice')
            input.setAttribute('type','hidden');
            input.setAttribute('value',id_devicei);
            form_del.appendChild(input);
            document.body.appendChild(form_del);
            form_del.submit();
        }
    }
    function updateFunc(id_devicei,osi,codei,descriptioni,roomi,ipi){
        const form_upd = document.createElement('form');
        form_upd.setAttribute('method', 'POST');
        form_upd.setAttribute('action', './update_device_form.php');

        const inputs = {
            id_devicei,osi,codei,descriptioni,roomi,ipi
        };

        for (const [key, value] of Object.entries(inputs)) {
            const input = document.createElement('input');
            input.setAttribute('name', key);
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', value);
            form_upd.appendChild(input);
        }

        document.body.appendChild(form_upd);
        form_upd.submit();
    }
    function showIncidentsFunc(id_devicei){
        let form_del;
        let input;
        form_del = document.createElement('form');
        form_del.setAttribute('method', 'POST');
        form_del.setAttribute('action', './show_incidents_device.php');
        input = document.createElement('input');
        input.setAttribute('name','show_incidents_device')
        input.setAttribute('type','hidden');
        input.setAttribute('value',id_devicei);
        form_del.appendChild(input);
        document.body.appendChild(form_del);
        form_del.submit();
    }

</script>
</html>
