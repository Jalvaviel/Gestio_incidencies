<?php
session_start();
include "../../library/helpers.php";
include "../../library/Device.php";
include "../../library/Incident.php";
if(empty($_SESSION['role']))
{
    toUrl('../../html/login.html');
}
$incident = new Incident(0, $_POST['description'], 'unresolved', date('Y-m-d'), $_SESSION['id_user']);
if($incident->insert($_SESSION['role']))
{
    if($incident->updateDevice($_SESSION['role'],$_POST['code']))
    {
        echo "<script>
        alert(\"Incident inserit correctament!\")
        window.location.replace(\"./incidents_page.php\");
        </script>";
    }
    else
    {
        echo "<script>
        alert(\"No s'ha inserit, ja te una incidencia sense resoldre.\")
        window.location.replace(\"./incidents_page.php\");
        </script>";
    }
}
else
{
    /*
    echo "<script>
            alert(\"Error a l'inserir l'incident\") 
            window.location.replace(\"./incidents_page.php\");
            </script>";
    */
}

?>

