<?php
include "../../library/Device.php";
include "../../library/helpers.php";
session_start();
$device = new Device($_POST['id_device'],$_POST['os'],$_POST['code'],$_POST['description'],$_POST['room'],$_POST['ip'],$_POST['id_incident']);
$device->update($_SESSION['role']);
/*
echo $device->getProperties()['ip'];
echo 'patata';
*/
echo "<script>
          alert(\"Equip actualitzat correctament!\")
          window.location.replace(\"./devices_page.php\");
          </script>";