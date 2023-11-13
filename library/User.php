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

    private function checkErrors($connect, string $condition, int $mode=1) // Funció que comproba si existeix algun usuari com a minim amb un id o email especific.
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

    public function insert(string $type) // Comprobar que l'usuari té els permisos necesaris.
    {
        if(strcmp($type,'admin') == 0)
        {
            $connect = databaseConnect($type);
            if($this->checkErrors($connect, $this->email, 2) && $this->checkErrors($connect, $this->id_user, 1))
            {
                echo "Ja existeix un usuari amb el mateix Email";
                return false;
            }
            else
            {
                $statement = $connect->prepare("INSERT INTO gestio_incidencies.users VALUES (?,?,?,?,?,?)"); // Prepara i executa el insert per prevenir SQL injections.
                $statement->bind_param("isssss", $this->id_user, $this->name, $this->surname, $this->email, $this->password, $this->role);
                if($statement->execute())
                {
                    echo "S'ha inserit l'usuari correctament.";
                    mysqli_close($connect);
                    return true;
                }
                else
                {
                    echo "Eror, no s'ha inserit l'usuari.";
                    mysqli_close($connect);
                    return false;
                }
            }
        }
        else
        {
            echo "No tens permisos suficients";
            return false;
        }
    }

    public function select(string $type) // Funció dedicada a buscar usuaris per ID únicament.
    {
        if(strcmp($type,'technician') == 0 || strcmp($type,'admin') == 0) // Comprobar que l'usuari té els permisos necesaris.
        {
            $connect = databaseConnect($type); // Fa la conexió a la base de dades
            $check = $this->checkErrors($connect, $this->id_user, 1);
            if($check)
            {
                $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections. 
                $statement->bind_param("i", $this->id_user);
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
        else
        {
            echo "No tens permisos suficients";
        }
    }

    public function login(string $type) // Funció que faig servir per al login.
    {
        if(strcmp($type,'technician') == 0 || strcmp($type,'admin') == 0) // Comprobar que l'usuari té els permisos necesaris.
        {
            $connect = databaseConnect($type); // Fa la conexió a la base de dades
            $check = $this->checkErrors($connect, $this->email, 2);
            if($check)
            {
                $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE email = ? AND password = ?"); // Prepara i executa el insert per prevenir SQL injections. 
                $statement->bind_param("ss", $this->email, $this->password);
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
        else
        {
            echo "No tens permisos suficients";
            return false;
        }
    }

    public function delete(string $type)
    {
        if(strcmp($type, 'admin') == 0)
        {
            $connect = databaseConnect($type);
            $statement = $connect->prepare("DELETE FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections.
            $statement->bind_param("i", $this->id_user);
            $statement->execute();
            $user = $statement->get_result()->fetch_assoc();
            mysqli_close($connect);
            return true;
        }
        else
        {
            return false;
        }
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