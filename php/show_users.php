<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuaris</title>
    <link rel="stylesheet" href="../css/style_users.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <img id="logo" src="../png/logo-no-background.png" alt="logo" width="300"/>
    <nav id="mainmenu">
        <a href="login.php" class="mainmenu">Usuaris</a>
        <a href="login.php" class="mainmenu">Incidències</a>
        <a href="login.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="login.php" id="logout"><img src="../png/exit.png" alt="logout" width="51"/>Surt</a>
        <a href="login.php" id="profile"><img src="../png/user.png" alt="profile" width="51"/>Perfil</a>
    </nav>
</header>
<nav class="menu">
    <ul>
        <?php
        include "../library/User.php";
        try {
            $all_users = loadUsers('admin', 1); // TODO change to variable in session from database
            foreach($all_users as $user) {
                $user_assoc = $user->getProperties();
                foreach($user_assoc as $key => $value){ // TODO don't show ids and role for normal users, or at least don't let them change it.
                    echo "<li class='llista'>$key => $value</li>";
                }
            }
        }catch (Exception $e) {
            echo "<div class='error'>Error obtenint resultats.</div>";
        }
        ?>
    </ul>
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
        case 'worker':
        case 'technician':
            $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user = $id_user");
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
        $user = new User(
            $userData['id_user'],
            $userData['name'],
            $userData['surname'],
            $userData['email'],
            $userData['password'],
            $userData['role']
        );
        $users[] = $user;
    }
    return $users;
}
?>