<?php
session_start();
include "../../library/helpers.php";
include "../../library/Device.php";
include "../../library/Incident.php";
if(empty($_SESSION['role'])){
    toUrl('../../html/login.html');
}
$incident = new Incident(0, $_POST['description'], 'unresolved', date('Y-m-d'), $_SESSION['id_user']);
$incident->updateDevice($_SESSION['role'],$_POST['code']);
if($incident->insert($_SESSION['role']))
{
    echo "<script>
            alert(\"Incident inserit correctament!\")
            window.location.replace(\"./incidents_page.php\");
            </script>";
}
else
{
    /*
    foreach($incident->getProperties() as $key => $value){
        echo "KEY: $key, VALUE: $value";
        echo "<br>";
    }
    */

    echo "<script>
            alert(\"Error a l'inserir l'incident\") // TODO FALLA
            window.location.replace(\"./incidents_page.php\");
            </script>";

}

?>

