<?php
session_start();
include "../../library/Incident.php";
include "../../library/helpers.php";
if($_SESSION['role']=='admin' || $_SESSION['role']=='technician') {
    $incident = new Incident($_POST['id_incident'], $_POST['description'], $_POST['stat'], "", 0);
    $incident->update($_SESSION['role']);
}

echo "<script>
          alert(\"Incident actualitzat correctament!\")
          window.location.replace(\"./incidents_page.php\");
          </script>";