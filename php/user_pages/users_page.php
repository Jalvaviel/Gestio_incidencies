<!DOCTYPE html>
<?php
    include "../../library/helpers.php";
    include "../../library/User.php";
    session_start();
    if(empty($_SESSION['email']) || empty($_SESSION['id_user']) || !isset($_SESSION['id_user']) || !isset($_SESSION['email'])){
        toUrl('../../html/login.html');
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuaris</title>
    <link rel="stylesheet" href="../../css/style_users.css">

    <script src="https://kit.fontawesome.com/8faa35dc4d.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>
<body>
<header>
    <img id="logo" src="../../png/logo-no-background.png" alt="logo" width="200"/>
    <nav id="mainmenu">
        <a href="./users_page.php" class="mainmenu">Usuaris</a>
        <a href="../incident_pages/incidents_page.php" class="mainmenu">Incidències</a>
        <a href="../device_pages/devices_page.php" class="mainmenu">Equips</a>
    </nav>
    <nav id="mainoptions">
        <a href="../login.php" id="profile"><i class="fa-solid fa-user" style="color: #ffffff;"></i>Perfil</a>
        <a href="../../html/login.html" id="logout"><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>Surt</a>
    </nav>
</header>
<nav class="menu">
<?php
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
            $current_user_id = $user_assoc['id_user'];
            $current_user_name = $user_assoc['name'];
            $current_user_surname = $user_assoc['surname'];
            $current_user_email = $user_assoc['email'];
            $current_user_role = $user_assoc['role'];

            echo "<td>";
            echo "<button onclick=deleteFunc('$current_user_id') id=\"deletebutton\"><i class=\"fa-solid fa-trash\"></i></button>";
            echo "<button onclick=updateFunc('$current_user_id','$current_user_name','$current_user_surname','$current_user_email','$current_user_role') id=\"updatebutton\"><i class=\"fa-solid fa-gear\"></i></button>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<a href='./insert_user.html' id='insert'>Inserta un nou usuari</a>";
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
        echo "<td class=\'llista\'> $role </td>";
        echo "</i></a></td></tr>";
    }

    ?>
</nav>
</body>
<script>
    function deleteFunc(id_useri){
        let form_del;
        let input;
        if (confirm("Estàs segur d'esborrar aquest usuari?")) {
            form_del = document.createElement('form');
            form_del.setAttribute('method', 'POST');
            form_del.setAttribute('action', './delete_user.php');
            input = document.createElement('input');
            input.setAttribute('name','deleteuser')
            input.setAttribute('type','hidden');
            input.setAttribute('value',id_useri);
            form_del.appendChild(input);
            document.body.appendChild(form_del);
            form_del.submit();
        }
    }
    function updateFunc(id_useri,namei,surnamei,emaili,rolei){
        const form_upd = document.createElement('form');
        form_upd.setAttribute('method', 'POST');
        form_upd.setAttribute('action', './update_user_form.php');

        const inputs = {
            id_useri,
            namei,
            surnamei,
            emaili,
            rolei
        };

        for (const [key, value] of Object.entries(inputs)) {
            const input = document.createElement('input');
            input.setAttribute('name', key);
            input.setAttribute('type', 'hidden');
            input.setAttribute('value', value);
            form_upd.appendChild(input);
        }

        document.body.appendChild(form_upd);
        form_upd.submit();
    }

</script>
</html>

