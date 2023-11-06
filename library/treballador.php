<?php
include "helpers.php";
function insert_into_incidents($connect, $id, $description, $status, $date)
{
    mysqli_query($connect, "insert $id, $description, $status, $date into gestio_incidencies.incidents");
    mysqli_close($connect);
}
function select_from($connect,$array_a_consultar,$base_de_dades="users",$columna_a_comparar=1,$valor_a_buscar=1)
{
    /*
    Justificación de la ida de olla siguiente:
        He creado una funcion que le pasas un array con las cosas que quieres
        consultar, y también tienes que especificar con qué valor quieres
        buscar al usuario, por ejemplo:
            array_a_consultar = ['id_user','email','role']
            columna_a_consultar = 'email'
            $valor_a_buscar = 'gepuor@jviladoms.cat'
        Me devolverá un array con el id_user, email i role de los que tengan el email 'gepuor@jviladoms.cat'
    */
    if (!ISSET($array_a_consultar) || !ISSET($columna_a_comparar) || !ISSET($valor_a_buscar) || EMPTY($array_a_consultar) || EMPTY($valor_a_buscar)|| EMPTY($valor_a_buscar)) // Comprobar que no hi han dades buides
    {
        return "Error, no s'han omplert les dades necesaries a la funcio.";
    }
    else if(sizeof($array_a_consultar) == 1) // Comprobar si només hi ha 1 valor a consultar per evadir bucles innecesaris
    {
        $query = $array_a_consultar[0];
    }
    else
    {
        $query = $array_a_consultar[0];  // Agafar els valors a consultar del array i montar la query
        for ($i = 1; $i < sizeof($array_a_consultar); $i++) {
            $query = $query . ', ' . $array_a_consultar[$i]; // Afegeix el valor al final
        }
    }
    
    $sql = "SELECT COUNT(id_user) FROM gestio_incidencies.$base_de_dades WHERE $columna_a_comparar = '$valor_a_buscar';"; // Separo la consulta para los sql injection
    $check = mysqli_query($connect, $sql);

    if(mysqli_fetch_assoc($check)) // Comproba que s'han trobat al menys 1 usuari
    {
        $sql = "SELECT $query FROM gestio_incidencies.$base_de_dades WHERE $columna_a_comparar = '$valor_a_buscar'";
        $data = mysqli_query($connect, $sql); // Fa la consulta
        return mysqli_fetch_assoc($data); // Retorna la consulta
    }
    else
    {
        return "Error, no s'han trobat usuaris.";
    }
}
?>