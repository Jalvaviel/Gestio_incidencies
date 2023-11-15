<?php
include 'helpers.php';

class Device
{
    private int $id_device = 0;
    private string $os = "";
    private string $code = "";
    private string $description = "";
    private string $ip = "";
    private string $room = "";

    public function __construct(int $id_device, string $os, string $code, string $description, string $ip, string $room)
    {
        $this->id_device = $id_device;
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->ip = $ip;
        $this->room = $room;
    }

    /** Funció checkErrors
     * És una funció que s'encarrega de buscar només un dispositiu.
     * Té dos modes, el mode 1 busca per id i el 2 per codi.
     * No tanca la conexió, ja que és una funció auxiliar.
     * Retorna bool.
    */
    private function checkErrors($connect, string $condition, int $mode=1) : bool
    {
        $column = ($mode == 1) ? "id_device" : "code";
        $varType = ($mode == 1) ? "i" : "s";

        $sql = "SELECT COUNT($column) AS 'count' FROM gestio_incidencies.devices WHERE $column = ?";
        $statement = $connect->prepare($sql);
        $statement->bind_param($varType, $condition);
        
        if (!$statement->execute())
        {
            return false;
        }

        $rowsSelected = $statement->get_result()->fetch_assoc();
        $count = $rowsSelected["count"];

        if ($rowsSelected["count"] <= 0 || $count > 1)
        {
            return false;
        }
        return true;
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
            if($this->checkErrors($connect, $this->code, 2) && $this->checkErrors($connect, $this->id_device, 1))
            {
                return false;
            }
            else
            {
                $sql = "INSERT INTO gestio_incidencies.devices VALUES (DEFAULT,?,?,?,?,?)";
                $statement = $connect->prepare($sql);
                $statement->bind_param("sssss", $this->os, $this->code, $this->description, $this->ip, $this->room);
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
     * Funció que actualitza tots el valors del dispositiu,
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
            $sql = "UPDATE gestio_incidencies.devices SET os = ?, code = ?, description = ?, ip = ?, room = ? WHERE id_device = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param("sssssi", $this->os, $this->code, $this->description, $this->ip, $this->room, $this->id_device);
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
     * Aquest funció serveix més per a buscar algún device en concret
       o actualitzar els valors de la classe, fent servir el id.
     * Crida a la funció checkErrors per veure que no hi han colisions
       al id o codi i que només troba 1 dispositiu, i guarda els valors
       a la classe.
     * Retorna bool.
     */
    public function select(string $type) : bool
    {
        if(strcmp($type,'technician') == 0 || strcmp($type,'admin') == 0)
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
                $this->__construct($device['id_device'], $device['os'], $device['code'], $device['description'], $device['ip'], $device['room']);
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
        if(strcmp($type, 'admin') == 0 && $check)
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
            'id_device' => $this->id_device,
            'os' => $this->os,
            'code' => $this->code,
            'description' => $this->description,
            'ip' => $this->ip,
            'room' => $this->room
        ];
    }
}