<?php
session_start();
include "../../library/helpers.php";
include "../../library/Device.php";
if($_SESSION['role']=='admin') {
    $device = new Device($_POST['deletedevice'],"",0,"",0,"",0);
    $device->delete($_SESSION['role']);
    toUrl("/gestio_incidencies/php/device_pages/devices_page.php");
}