<?php
    include "../library/helpers.php";

    class Incidents_devices
    {
        private int $id_incidents_devices;
        private int $id_incident;
        private int $id_device;

        public function __construct($id_incidents_devices, $id_incident,  $id_device)
        {
            $this->id_incidents_devices =$id_incidents_devices;
            $this->id_incident = $id_incident;
            $this->id_device = $id_device;
        }

        /** Funció checkErrors
        * És una funció que s'encarrega de buscar només un dispositiu.
        * Té dos modes, el mode 1 busca per id_incidents_devices
        * i el 2 per id_incident i el 3 per id_device.
        * No tanca la conexió, ja que és una funció auxiliar.
        * Retorna bool.
        */
        private function checkErrors($connect, string $condition, int $mode=1) : bool
        {
            switch($mode)
            {
                case 1:
                    {
                        $column = "id_incidents_devices";
                        break;
                    }
                case 2:
                    {
                        $column = "id_incident";
                        break;
                    }
                case 3:
                    {
                        $column = "id_device";
                        break;
                    }
                default:
                {
                    $mode = 1;
                    $column = "id_incidents_devices";
                    break;
                }
            }
    
            $sql = "SELECT COUNT($column) AS 'count' FROM gestio_incidencies.incidents_devices WHERE $column = ?";
            $statement = $connect->prepare($sql);
            $statement->bind_param('i', $condition);
            
            if (!$statement->execute())
            {
                return false;
            }
    
            $rowsSelected = $statement->get_result()->fetch_assoc();
            $count = $rowsSelected["count"];
    
            if ($count < 1)
            {
                return false;
            }
            if ($mode == 1 && $count > 1)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        /**Funció insert
         * Serveix per insertar a la relació n:m incidents_devices amb les variables de la 
         classe, pots fer servir el constructor per omplir els valors a la classe
        i seguidament, fer un insert, per insertar-ho a la base de dades.
        * Crida a la funció checkErrors per comprobar que no hi hagi cap 
        amb el mateix primary key.
        * Retorna bool.
        */
        public function insert(string $type) : bool
        {
            if(strcmp($type,'admin') == 0)
            {
                $connect = databaseConnect($type);
                if($this->checkErrors($connect, $this->id_incidents_devices, 1))
                {
                    return false;
                }
                else
                {
                    $sql = "INSERT INTO gestio_incidencies.incidents_devices VALUES (DEFAULT,?,?)";
                    $statement = $connect->prepare($sql);
                    $statement->bind_param("ii", $this->id_incident, $this->id_device);
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
        * Funció que actualitza tots el valors de la
          relació, i fa servir el id de la classe
          per buscar-lo.
        * Retorna bool.
        */
        public function update(string $type) : bool
        {
            $connect = databaseConnect($type);
            $check = $this->checkErrors($connect, $this->id_incidents_devices, 1);
            if((strcmp($type, 'admin') == 0 || strcmp($type, 'admin') == 0) && $check)
            {
                $sql = "UPDATE gestio_incidencies.devices SET id_incident = ?, id_device = ? WHERE id_incidents_devices = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("iii", $this->id_incident, $this->id_device, $this->id_incidents_devices);
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
         * Aquest funció serveix més per a buscar algúna relació en concret
         o actualitzar els valors de la classe, fent servir el id.
        * Crida a la funció checkErrors per veure que no hi han colisions
        al id o codi i que només troba 1 dispositiu, i guarda els valors
        a la classe.
        * Retorna bool.
        */
        public function select(string $type) : bool
        {
            $connect = databaseConnect($type);
            if($this->checkErrors($connect, $this->id_incidents_devices, 1))
            {
                $sql = "SELECT * FROM gestio_incidencies.incidents_devices WHERE id_incidents_devices = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("i", $this->id_incidents_devices);
                $statement->execute();
                $result = $statement->get_result()->fetch_assoc();
                $this->__construct($result["id_incidents_devices"], $result["id_incidents"], $result["id_devices"]);
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
         * Agafa el id de la classe, i esborra amb la 
           id de la classe.
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
                $sql = "DELETE FROM gestio_incidencies.incidents_devices WHERE id_incidents_devices = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("i", $this->id_incidents_devices);
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

        public function findDevices(string $type) : array
        {
            $connect = databaseConnect($type);
            if($this->checkErrors($connect, $this->id_incidents_devices, 1))
            {
                $sql = "SELECT * FROM gestio_incidencies.devices WHERE id_device = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("i", $this->id_device);
                $statement->execute();
                $result = $statement->get_result()->fetch_assoc();
                $connect->close();
                return $result;
            }
            else
            {
                $connect->close();
                return false;
            }
        }

        public function findIncidents(string $type) : array
        {
            $connect = databaseConnect($type);
            if($this->checkErrors($connect, $this->id_incidents_devices, 1))
            {
                $sql = "SELECT * FROM gestio_incidencies.incidents WHERE id_device = ?";
                $statement = $connect->prepare($sql);
                $statement->bind_param("i", $this->id_incident);
                $statement->execute();
                $result = $statement->get_result()->fetch_assoc();
                $connect->close();
                return $result;
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
        $sql = "SELECT MAX(id_incidents_devices) AS 'max' FROM gestio_incidencies.incidents_devices";
        $statement = $connect->prepare($sql);
        if($statement->execute())
        {
            $result = $statement->get_result()->fetch_assoc();
            $connect->close();
            return $result['max'];
        }
        else
        {
            return -1;
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
                'id_incidents_devices' => $this->id_incidents_devices,
                'id_incident' => $this->id_incident, 
                'id_device' => $this->id_device
            ];
        }
    }
?>