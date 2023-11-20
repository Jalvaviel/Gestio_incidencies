<?php
    session_start();
    include "../../library/helpers.php";
    include "../../library/Device.php";
    if(empty($_SESSION['role'])){
        toUrl('../../html/login.html');
    }
    $device = new Device(0, $_POST['os'], $_POST['code'], $_POST['description'], $_POST['ip'], $_POST['room'], 0);
    if($device->insert($_SESSION['role']))
    {
        echo "<script>
            alert(\"Equip insertat correctament!\")
            window.location.replace(\"./devices_page.php\");
            </script>";
    }
    else
    {
        echo "<script>
            alert(\"Error al inserir l'equip\")
            window.location.replace(\"./devices_page.php\");
            </script>";
    }

?>

