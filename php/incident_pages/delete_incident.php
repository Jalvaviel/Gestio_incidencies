<?php
include "../../library/helpers.php";
include "../../library/Incident.php";

session_start();
if($_SESSION['role']=='admin') {
    $incident = new Incident($_POST['deleteincident'],"","","",0);
    $incident->delete($_SESSION['role']);
    toUrl("./incidents_page");
}