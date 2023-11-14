<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equips</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <h1>Equips</h1>
</header>
<nav class="menu">
    <ul>
        <?php
        include "Device.php";
        try {
            $all_devices = loadAllDevices('admin');
            foreach($all_devices as $device) {
                $device->printDeviceProperties();
            }
        }catch (Exception $e) {
            echo 'Error al mostrar els dispositius';
        }
        ?>
    </ul>
</nav>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>
<?php

/**
 * @throws Exception
 */
function loadAllDevices($type): array
{
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices");

        checkStatement($statement, $connect);

        $result = $statement->execute();

        if ($result) {
            $devices = [];
            $resultSet = $statement->get_result();

            while ($deviceData = $resultSet->fetch_assoc()) {
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
        }
        return $devices;
    }

?>