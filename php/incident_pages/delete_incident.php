<?php
session_start();
include "../../library/helpers.php";
include "../../library/Incident.php";
if($_SESSION['role']=='admin' || $_SESSION['role']=='technician') {
    $incident = new Incident($_POST['deleteincident'],"","","",0);
    $incident->delete($_SESSION['role']);
    toUrl("/gestio_incidencies/php/incident_pages/incidents_page.php");
}