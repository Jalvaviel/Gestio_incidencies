<?php
class User
{
    private int $id_user;
    private string $name;
    private string $surname;
    private string $email;
    private string $password;
    private string $role;

    public function __construct($id_user=0, $name=null, $surname=null, $email=null, $password=null, $role=null)
    {
        $this->id_user = $id_user;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    private function checkErrors($connect, string $condition, int $mode=1)
    {
        $check = true;
        if($mode == 1)
        {
            $statement = $connect->prepare("SELECT COUNT(id_user) AS 'num' FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections.
            $statement->bind_param("i", $condition);
            if(!$statement->execute()) // Executa i comproba si s'executa bé el codi
            {
                echo("Error: " . $statement->error);
                $check = false;
            }
            else
            {
                $rows_selected = $statement->get_result()->fetch_assoc();
                if($rows_selected["num"] <= 0) // Comproba si troba algún usuari
                {
                    echo("Error: no s'ha trobat cap usuari."); 
                    $check = false;
                }
            }
        }
        elseif($mode == 2)
        {
            $statement = $connect->prepare("SELECT COUNT(id_user) AS 'num' FROM gestio_incidencies.users WHERE email = ?"); // Prepara i executa el insert per prevenir SQL injections.
            $statement->bind_param("s", $condition);
            if(!$statement->execute()) // Executa i comproba si s'executa bé el codi
            {
                echo("Error: " . $statement->error);
                $check = false;
            }
            else
            {
                $rows_selected = $statement->get_result()->fetch_assoc();
                if($rows_selected["num"] <= 0 || $rows_selected["num"] > 1) // Comproba si només troba un usuari
                {
                    echo("Error: no s'ha trobat cap usuari."); 
                    $check = false;
                }
            }
        }
        else
        {
            echo "<br/>Has introduit un mode invalid a la funcio checkErrors<br/>";
            $check = false;
        }
        return $check;
    }

    public function insert(string $type, int $id)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->bind_param("i", $id);
        $statement->execute();
        $user = $statement->get_result()->fetch_assoc();
        $this->__construct($user['user_id'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
        mysqli_close($connect);
    }

    public function select(string $type, $id) // Funció dedicada a buscar usuaris per ID únicament
    {
        $connect = databaseConnect($type); // Fa la conexió a la base de dades
        $check = $this->checkErrors($connect, $id, 1);
        if($check)
        {
            $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections. 
            $statement->bind_param("i", $id);
            $statement->execute();
            $user = $statement->get_result()->fetch_assoc(); // Guarda la informació als atributs de la clase
            $this->__construct($user['id_user'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
            mysqli_close($connect); // Tanca la conexió
            return true;
        }
        else
        {
            mysqli_close($connect); // Tanca la conexió
            return false;
        }
    }

    public function login(string $type, $email, $password)
    {
        $connect = databaseConnect($type); // Fa la conexió a la base de dades
        $check = $this->checkErrors($connect, $email, 2);
        if($check)
        {
            $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE email = ? AND password = ?"); // Prepara i executa el insert per prevenir SQL injections. 
            $statement->bind_param("ss", $email, $password);
            $statement->execute();
            $user = $statement->get_result()->fetch_assoc(); // Guarda la informació als atributs de la clase
            $this->__construct($user['id_user'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
            mysqli_close($connect); // Tanca la conexió
            return true;
        }
        else
        {
            mysqli_close($connect); // Tanca la conexió
            return false;
        }
    }

    public function delete(string $type, $id)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->bind_param("i", $id);
        $statement->execute();
        $user = $statement->get_result()->fetch_assoc();
        mysqli_close($connect);
    }

    public function getUserProperties()
    {
        return 
        [
            'id_user' => $this->id_user,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role
        ];
    }

}
?>