<?php
include "../../library/Device.php";
session_start();
$device = new Device($_POST['id_device'],$_POST['os'],$_POST['code'],$_POST['description'],$_POST['room'],$_POST['ip']);
if (!empty($_POST['password'])){
    $device->update($_SESSION['role']);
}
else{
    $device->update($_SESSION['role']);
}
echo "<script>
          alert(\"Equip actualitzat correctament!\")
          window.location.replace(\"./devices_page.php\");
          </script>";