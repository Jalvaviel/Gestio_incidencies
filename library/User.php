<?php
include 'helpers.php';

class User
{
    private int $id_user = 0;
    private string $name = "";
    private string $surname = "";
    private string $email = "";
    private string $password = "";
    private string $role = "";

    public function __construct($id_user, $name, $surname, $email, $password, $role)
    {
        $this->id_user = $id_user;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    /** Funció checkErrors
     * És una funció que s'encarrega de buscar només un usuari.
     * Té dos modes, el mode 1 busca per id i el 2 per email.
     * No tanca la conexió, ja que és una funció auxiliar.
     * Retorna bool.
    */
    private function checkErrors($connect, string $condition, int $mode=1) : bool
    {
        $check = true;

        $column = ($mode == 1) ? "id_user" : "email";
        $varType = ($mode == 1) ? "i" : "s";

        $sql = "SELECT COUNT(id_user) AS 'count' FROM gestio_incidencies.users WHERE $column = ?";
        $statement = $connect->prepare($sql);
        $statement->bind_param($varType, $condition);
        
        if (!$statement->execute())
        {
            $check = false;
        }

        $rowsSelected = $statement->get_result()->fetch_assoc();
        $count = $rowsSelected["count"];

        if ($rowsSelected["count"] <= 0 || $count > 1)
        {
            $check = false;
        }
        return $check;
    }

    /**Funció insert
     * Serveix per insertar un usuari amb les variables de la classe,
       pots fer servir el constructor per omplir els valors a la classe
       i seguidament, fer un insert, per insertar-ho a la base de dades.
     * Crida a la funció checkErrors per comprobar que no hi hagi cap 
       usuari amb el mateix email o id.
     * Retorna bool.
     */
    public function insert(string $type) : bool
    {
        if(strcmp($type,'admin') == 0)
        {
            $connect = databaseConnect($type);
            if($this->checkErrors($connect, $this->email, 2) && $this->checkErrors($connect, $this->id_user, 1))
            {
                return false;
            }
            else
            {
                $sql = "INSERT INTO gestio_incidencies.users VALUES (DEFAULT,?,?,?,?,?)";
                $statement = $connect->prepare($sql);
                $statement->bind_param("sssss", $this->name, $this->surname, $this->email, $this->password, $this->role);
                if($statement->execute())
                {
                    echo "S'ha inserit l'usuari correctament." . PHP_EOL;
                    $connect->close();
                    return true;
                }
                else
                {
                    $connect->close();
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }

    /**Funció update
     * Funció que actualitza tots el valors del usuari,
       i fa servir el id per buscar-lo.
     * Retorna bool.
     */
    public function update(string $type) : bool
    {
        if(strcmp($type,'admin') != 0)
        {
            return false;
        }
        else
        {
            $connect = databaseConnect($type);
            $sql = "UPDATE gestio_incidencies.users SET name = ?, surname = ?, email = ?, password = ?, role = ? WHERE id_user = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param("sssssi", $this->name, $this->surname, $this->email, $this->password, $this->role, $this->id_user);
            if($statement->execute())
            {
                $connect->close();
                return true;
            }
            else
            {
                $connect->close();
                return false;
            }
        }
    }

    /**Funció select
     * Aquest funció serveix més per a buscar algún usuari en concret
       o actualitzar els valors de la classe.
     * Crida a la funció checkErrors per veure que no hi han colisions
       al id o email i que només troba 1 usuari, i guarda els valors
       a la classe.
     * Retorna bool.
     */
    public function select(string $type) : bool
    {
        if(strcmp($type,'technician') == 0 || strcmp($type,'admin') == 0)
        {
            $connect = databaseConnect($type);
            $check = $this->checkErrors($connect, $this->id_user, 1);
            if($check)
            {
                $sql = "SELECT * FROM gestio_incidencies.users WHERE id_user = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("i", $this->id_user);
                $statement->execute();
                $user = $statement->get_result()->fetch_assoc();
                $this->__construct($user['id_user'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
                $connect->close();
                return true;
            }
            else
            {
                $connect->close();
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**Funció Login.
     * Aquesta funció és molt similar a la select, però aquesta
       li paso el el password sense encriptar a la classe, i 
       ho comproba amb el password_verify() i si és el mateix,
       guarda a la classe el usuari amb totes les dades i el 
       password encriptat.
     * Retorna bool.
     */
    public function login(string $type) : bool
    {
        if(strcmp($type,'technician') == 0 || strcmp($type,'admin') == 0)
        {
            $connect = databaseConnect($type);
            $check = $this->checkErrors($connect, $this->email, 2);
            if($check)
            {
                $sql = "SELECT * FROM gestio_incidencies.users WHERE email = ? AND password = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("ss", $this->email, $this->password);
                $statement->execute();
                $user = $statement->get_result()->fetch_assoc();
                if(password_verify($this->password, $user['password']))
                {
                    $this->__construct($user['id_user'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
                    $connect->close();
                    return true;
                }
                else
                {
                    $connect->close();
                    return false;
                }
            }
            else
            {
                $connect->close();
                return false;
            }
        }
        else
        {
            return false;
        }
    }

/**Funció delete
 * Agafa el id de la classe i l'esborra.
 * El type, serà del $_SESSION[], per així
 * fer servir la classe per agafar la info,
 * i executar el delete.
 * Retorna bool.
 */
    public function delete(string $type) : bool
    {
        $connect = databaseConnect($type);
        $check = $this->checkErrors($connect, $this->id_user);
        if(strcmp($type, 'admin') == 0 && $check)
        {
            $sql = "DELETE FROM gestio_incidencies.users WHERE id_user = ?";
            $statement = $connect->prepare($sql); // Prepara i executa el insert per prevenir SQL injections.
            $statement->bind_param("i", $this->id_user);
            $statement->execute();
            $user = $statement->get_result()->fetch_assoc();
            $connect->close();
            return true;
        }
        elseif(strcmp($type, 'admin') != 0)
        {
            $connect->close();
            return false;
        }
        else
        {
            $connect->close();
            return false;
        }
    }

    /**Funció getuserProperties
     * És un getter, retorna un array associatiu
       amb els valors de la classe.
     * Retorna un array.
     */
    public function getUserProperties() : array
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