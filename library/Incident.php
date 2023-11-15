<?php

class Incident
{
    private int $id_incident = 0;
    private string $description = "";
    private string $status = "";
    private string $date = "";
    private int $id_user = 0;

    public function __construct($id_incident, $description, $status, $date, $id_user)
    {
        $this->id_incident = $id_incident;
        $this->description = $description;
        $this->status = $status;
        $this->date = $date;
        $this->id_user = $id_user;
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

        $column = ($mode == 1) ? "id_incident" : "id_user";
        $table = ($mode == 1) ? "incidents" : "users";
        
        $sql = "SELECT COUNT(?) AS 'count' FROM gestio_incidencies.$table WHERE ? = ?";
        $statement = $connect->prepare($sql);
        $statement->bind_param('ssi', $column, $column,$condition);
        
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
            if(!$this->checkErrors($connect, $this->id_incident, 1) || $this->checkErrors($connect, $this->id_user, 2))
            {
                return false;
            }
            else
            {
                $sql = "INSERT INTO gestio_incidencies.users VALUES (DEFAULT,?,?,?,?,?)";
                $statement = $connect->prepare($sql);
                $statement->bind_param("sssi", $this->description, $this->status, $this->date, $this->id_user);
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
            $sql = "UPDATE gestio_incidencies.users SET description = ?, status = ?, date = ?, id_user = ? WHERE id_incident = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param("sssii", $this->description, $this->status, $this->date, $this->id_user, $this->id_incident);
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
            $check = $this->checkErrors($connect, $this->id_incident, 1);
            if($check)
            {
                $sql = "SELECT * FROM gestio_incidencies.incidents WHERE id_incident = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("i", $this->id_incident);
                $statement->execute();
                $incident = $statement->get_result()->fetch_assoc();
                $this->__construct($incident['id_incident'], $incident['description'], $incident['status'], $incident['date'], $incident['id_user']);
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
        $check = $this->checkErrors($connect, $this->id_incident);
        if(strcmp($type, 'admin') == 0 && $check)
        {
            $sql = "DELETE FROM gestio_incidencies.incidents WHERE id_incident = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param("i", $this->id_incident);
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

    /**Funció getProperties
     * És un getter, retorna un array associatiu
       amb els valors de la classe.
     * Retorna un array.
     */
    public function getProperties() : array
    {
        return 
        [
            'id_incident' => $this->id_incident,
            'description' => $this->description,
            'surname' => $this->status,
            'email' => $this->date,
            'password' => $this->id_user
        ];
    }
}