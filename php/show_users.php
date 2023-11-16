<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuaris</title>
    <link rel="stylesheet" href="../css/style_users.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
<header>
    <img id="logo" src="../png/logo-no-background.png" alt="logo" width="200"/>
    <nav id="mainmenu">
        <a href="show_users.php" class="mainmenu">Usuaris</a>
        <a href="show_incidents.php" class="mainmenu">Incidències</a>
        <a href="show_devices.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="login.php" id="profile"><img src="../png/user.png" alt="profile" width="41"/>Perfil</a>
        <a href="../html/login.html" id="logout"><img src="../png/exit.png" alt="logout" width="41"/>Surt</a>
    </nav>
</header>
<nav class="menu">
    <table>
        <tr>
            <th><strong>ID Usuari</strong></th>
            <th><strong>Nom</strong></th>
            <th><strong>Cognom</strong></th>
            <th><strong>Correu</strong></th>
            <th><strong>Contrasenya (Hash)</strong></th>
            <th><strong>Rol</strong></th>
            <th><strong>Modificar</strong></th>
        </tr>
<?php
        session_start();
        include "../library/User.php";
        if(empty($_SESSION)){
            toUrl('../html/login.html');
        }
        try {
            $all_users = loadUsers($_SESSION["role"], $_SESSION["id_user"]);
            foreach($all_users as $user) {
                $user_assoc = $user->getProperties();
                echo "<tr>";
                foreach($user_assoc as $key => $value){ // TODO don't show ids and role for normal users, or at least don't let them change it.
                    echo "<td class='llista'>$value</td>";
                }
                echo '/></a></td>';
                echo "</tr>";
            }
        }catch (Exception $e) {
            echo "<div class='error'>Error obtenint resultats.</div>";
        }
        ?>
    </table>
</nav>
<footer>
    Copyright lolololololol - Javier Castelblanque Iñigo - Gerard Puig Ortega.
</footer>
</body>
</html>

<?php
function loadUsers($type, $id_user): array
{

    $connect = databaseConnect($type);
    $statement = false;

    switch ($type) { // Comprova el tipus d'usuari que vol fer la consulta.
        case 'admin':
            $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users");
            break;
        case 'technician':
            $statement = $connect->prepare("SELECT id_user, name, surname FROM gestio_incidencies.users");
            break;
        case 'worker':
            break;
    }
    if (!$statement) {
        echo "<div class='error'>Error preparant consulta.</div>";
    }
    $result = $statement->execute();
    if (!$result) {
        echo "<div class='error'>Error obtenint resultats.</div>";
    }
    $users = [];
    $row = $statement->get_result();

    while ($userData = $row->fetch_assoc()) {
        $user = new User();
        switch ($type) { // Comprova el tipus d'usuari que vol fer la consulta.
            case 'admin':
                $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users");
                break;
            case 'technician':
                $statement = $connect->prepare("SELECT id_user, name, surname FROM gestio_incidencies.users");
                break;
            case 'worker':
                break;
        }
        $users[] = $user;
    }
    return $users;
}
?>