<?php
class Device
{
    private int $id_device = 0;
    private string $os = "";
    private string $code = "";
    private string $description = "";
    private int $room = 0;
    private string $ip = "";
    private int $id_incident = 0;

    public function __construct(int $id_device, string $os, string $code, string $description, int $room, string $ip, int $id_incident)
    {
        $this->id_device = $id_device;
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
        $this->id_incident = $id_incident;
    }

    /** Funció checkErrors
     * És una funció que s'encarrega de buscar només un dispositiu.
     * Té dos modes, el mode 1 busca per id i el 2 per codi.
     * No tanca la conexió, ja que és una funció auxiliar.
     * Retorna bool.
    */
    private function checkErrors($connect, string $condition, int $mode=1) : bool
    {
        switch ($mode) {
            case 1:
                $column = "id_device";
                $varType = "i";
                break;
            
            case 2:
                $column = "code";
                $varType = "s";
                break;

            case 3:
                $column = "ip";
                $varType = "s";
                break;
            
            case 4:
                $column = "id_incident";
                $varType = "i";

            default:
                $column = "id_device";
                $varType = "i";
                break;
        }

        $sql = "SELECT COUNT($column) AS 'count' FROM gestio_incidencies.devices WHERE $column = ?";
        $statement = $connect->prepare($sql);
        $statement->bind_param($varType, $condition);
        
        if (!$statement->execute())
        {
            return false;
        }

        $rowsSelected = $statement->get_result()->fetch_assoc();
        $count = $rowsSelected["count"];

        if ($count < 1 || $count > 1)
        {
            return false;
        }
        else
        {
            return true;
        }
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
        $connect = databaseConnect($type);
        if($this->checkErrors($connect, $this->id_device, 1) || $this->checkErrors($connect, $this->code, 2) || $this->checkErrors($connect, $this->ip, 3))
        {
            return false;
        }
        else
        {
            $id = $this->max($type) + 1;
            $sql = "INSERT INTO gestio_incidencies.devices VALUES ($id, ?, ?, ?, ?, ?, 0)";
            $statement = $connect->prepare($sql);
            $statement->bind_param("sssis", $this->os, $this->code, $this->description, $this->room, $this->ip);
            $statement->execute();
            $connect->close();
            return true;
        }
    }

    /**Funció update
     * Funció que actualitza tots el valors, menys la primary
       key, i fa servir el id per buscar-lo.
     * Retorna bool.
     */
    public function update(string $type) : bool
    {
        $connect = databaseConnect($type);
        $sql = "UPDATE gestio_incidencies.devices SET os = ?, code = ?, description = ?, room = ?, ip = ?, id_incident = ? WHERE id_device = ?";
        $statement = $connect->prepare($sql);
        $statement->bind_param("sssssii", $this->os, $this->code, $this->description, $this->room, $this->ip, $this->id_incident, $this->id_device);
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
    
    /**Funció select
     * Aquest funció serveix més per a buscar algún device en concret
       o actualitzar els valors de la classe, fent servir el id.
     * Crida a la funció checkErrors per veure que no hi han colisions
       al id o codi i que només troba 1 dispositiu, i guarda els valors
       a la classe.
     * Retorna bool.
     */
    public function select(string $type) : bool
    {
        $connect = databaseConnect($type);
        $check = $this->checkErrors($connect, $this->id_device, 1);
        if($check)
        {
            $sql = "SELECT * FROM gestio_incidencies.devices WHERE id_device = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param("i", $this->id_device);
            $statement->execute();
            $device = $statement->get_result()->fetch_assoc();
            $this->__construct($device['id_device'], $device['os'], $device['code'], $device['description'], $device['room'], $device['ip'], $device['id_incident']);
            $connect->close();
            return true;
        }
        else
        {
            $connect->close();
            return false;
        }
    }

    /**Funció delete
     * Agafa el id de la classe, i esborra l'usuari
       amb la id de la classe.
     * El type, serà del $_SESSION[], per així fer 
       servir la classe per agafar la info, i 
       executar el delete.
     * Retorna bool.
     */
    public function delete(string $type) : bool
    {
        $connect = databaseConnect($type);
        $check = $this->checkErrors($connect, $this->id_device);
        if((strcmp($type, 'admin') == 0 || strcmp($type, 'technician') == 0) && $check)
        {
            $sql = "DELETE FROM gestio_incidencies.devices WHERE id_device = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param("i", $this->id_device);
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
        else
        {
            $connect->close();
            return false;
        }
    }

    /**Funció max
     * Retorna el id més gran que hi ha a la 
       base de dades, l'utilitza el insert
       i està pensat per fer-ho servir en
       un for()
     */
    public function max($type) : int
    {
        $connect = databaseConnect($type);
        $sql = "SELECT COUNT(id_device) AS 'count' FROM gestio_incidencies.devices";
        $statement = $connect->prepare($sql);
        $statement->execute();
        $count = $statement->get_result()->fetch_assoc();
        if($count['count'] > 0)
        {
            $sql = "SELECT MAX(id_device) AS 'max' FROM gestio_incidencies.devices";
            $statement = $connect->prepare($sql);
            if($statement->execute())
            {
                $result = $statement->get_result()->fetch_assoc();
                $connect->close();
                if(!isset($result['max']) || empty($result['max']))
                {
                    return false;
                }
                else
                {
                    return $result['max'];
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return 0;
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
            'id_device' => $this->id_device,
            'os' => $this->os,
            'code' => $this->code,
            'description' => $this->description,
            'room' => $this->room,
            'ip' => $this->ip,
            'id_incident' => $this->id_incident
        ];
    }
}