<?php
include "../../library/Incident.php";
include "../../library/helpers.php";
session_start();
if($_SESSION['role']=='admin' || $_SESSION['role']=='technician') {
    $incident = new Incident($_POST['id_incident'], "", "", "", 0);
    $incident->select($_SESSION['role']);
    $values = $incident->getProperties();
    $incident->__construct($values['id_incident'], $_POST['description'], $_POST['stat'], $values['date'], $values['id_user']);
    $incident->update($_SESSION['role']);
}

echo "<script>
          alert(\"Incident actualitzat correctament!\")
          window.location.replace(\"./incidents_page.php\");
          </script>";