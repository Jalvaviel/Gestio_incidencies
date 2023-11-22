<?php
include "../../library/helpers.php";
include "../../library/User.php";
session_start();
if($_SESSION['role']=='admin') {
    $user = new User($_POST['id_user'], $_POST['name'], $_POST['surname'], $_POST['email'], hashPasswords($_POST['password']), $_POST['role']);
}
else{
    $user = new User($_POST['id_user'], $_POST['name'], $_POST['surname'], $_POST['email'], hashPasswords($_POST['password']), $_SESSION['role']);
    $_SESSION = $user->getProperties();
}
if (!empty($_POST['password'])){
    $user->update('admin',false); //$_SESSION['role']
}
else{
    $user->update('admin',true);
}
echo "<script>
          alert(\"Usuari actualitzat correctament!\")
          window.location.replace(\"./users_page.php\");
          </script>";