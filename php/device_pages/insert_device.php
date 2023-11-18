<?php
    session_start();
    include "../../library/Device.php";
    if(empty($_SESSION)){
        toUrl('../../html/login.html');
    }
    $device = new Device(69,$_POST['os'],$_POST['code'],$_POST['description'],$_POST['room'],$_POST['ip']);
    $device->insert($_SESSION['role']);
    echo "<script>
          alert(\"Equip insertat correctament!\")
          window.location.replace(\"./devices_page.php\");
          </script>";
?>

