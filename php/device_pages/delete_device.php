<?php
include "../../library/helpers.php";
include "../../library/Device.php";

session_start();
if($_SESSION['role']=='admin') {
    $device = new Device($_POST['deletedevice'],"",0,"",0,"",0);
    $device->delete($_SESSION['role']);
    toUrl("./devices_page");
}