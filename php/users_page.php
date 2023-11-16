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
<?php
    session_start();
    include "../library/User.php";
    if(empty($_SESSION)){
        toUrl('../html/login.html');
    }
    switch($_SESSION['role']){
        case 'admin':
            show_all_users('admin');
            break;
        case 'technician':
        case 'worker':
            print_self();
            break;
    }

    function show_all_users($role) : void
    {
        $connect = databaseConnect($role);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users");

        // Control de consultes
        if (!$statement) {
            echo "Error preparant consulta.";
        }
        $result = $statement->execute();
        if (!$result) {
            echo "Error obtenint resultats.";
        }
        // Resultat i llista d'usuaris
        $users = get_all_users($statement);
        print_admin_table($users);
        $connect->close();
    }
    function print_admin_table($users) : void
    {
        echo "<table>";
        echo "<tr><th><strong>ID Usuari</strong></th>
        <th><strong>Nom</strong></th>
        <th><strong>Cognom</strong></th>
        <th><strong>Correu</strong></th>
        <th><strong>Contrasenya (Hash)</strong></th>
        <th><strong>Rol</strong></th>
        <th><strong>Modificar</strong></th></tr>";

        foreach ($users as $user) {
            $user_assoc = $user->getProperties();
            echo "<tr>";
            foreach ($user_assoc as $key => $value) {
                echo "<td class='llista'>$value</td>";
            }
            echo '<td><button><img src=\'../png/setting.png\' alt=\'configura\' width=\'25\'/></button>';
            $current_user_id = $user_assoc['id_user'];
            echo "<button onclick=deleteFunc('$current_user_id') id=\"deletebutton\"><img src='../png/trash.png' alt='elimina' width='25'/></button></td>";
            echo "</tr>";
        }
    }

    function get_all_users($statement) : array
    {
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

    function print_self(){
        echo "<table>";
        echo "<tr>
        <th><strong>Nom</strong></th>
        <th><strong>Cognom</strong></th>
        <th><strong>Correu</strong></th>
        <th><strong>Contrasenya (Hash)</strong></th>
        <th><strong>Rol</strong></th>
        <th><strong>Modificar</strong></th></tr>";
        $name = $_SESSION['name'];
        $surname = $_SESSION['surname'];
        $email = $_SESSION['email'];
        $password_hash = $_SESSION['password'];
        $role = $_SESSION['role'];
        echo "<tr><td class=\'llista\'> $name </td>";
        echo "<td class=\'llista\'> $surname </td>";
        echo "<td class=\'llista\'> $email </td>";
        echo "<td class=\'llista\'> $password_hash </td>";
        echo "<td class=\'llista\'> $role </td>";
        echo "<td><a href='login.php'><img src='../png/setting.png' alt='configura' width='25'/></a></td></tr>";
    }

    function delete_user($id_user){
        $user = new User($id_user);
        $user->delete('admin');
    }
    ?>
</nav>
</body>
<script>
    function deleteFunc(id_user){
        let form_del;
        let input;
        if (confirm("Estàs segur d'esborrar aquest usuari?")) {
            form_del = document.createElement('form');
            form_del.setAttribute('method', 'POST');
            form_del.setAttribute('action', './delete_user.php');
            input = document.createElement('input');
            input.setAttribute('type','hidden');
            input.setAttribute('value',id_user);
            form_del.appendChild(input);
            document.body.appendChild(form_del);
            form_del.submit();
        }
    }
</script>
</html>

