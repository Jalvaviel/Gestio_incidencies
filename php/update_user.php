<?php
include "../library/User.php";
session_start();
$user = new User($_POST['id_user'],$_POST['name'],$_POST['surname'],$_POST['email'],hashPasswords($_POST['password']),$_POST['role']);
if (!empty($_POST['password'])){
    $user->update($_SESSION['role'],false);
}
else{
    $user->update($_SESSION['role'],true);
}
echo "<script>
          alert(\"Usuari actualitzat correctament!\")
          window.location.replace(\"./users_page.php\");
          </script>";