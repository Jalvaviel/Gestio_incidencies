<?php
session_start();
include "../../library/helpers.php";
include "../../library/User.php";
if(empty($_SESSION))
{
toUrl('../../html/login.html');
}
$user = new User(69,$_POST['name'],$_POST['surname'],$_POST['email'],hashPasswords($_POST['password']),$_POST['role']);
$user->insert($_SESSION['role']);
echo "<script>
      alert(\"Usuari insertat correctament!\")
      window.location.replace(\"./users_page.php\");
      </script>";
?>

