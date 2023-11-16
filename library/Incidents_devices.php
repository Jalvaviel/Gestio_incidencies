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
        * Funció que actualitza tots el valors del dispositiu,
          i fa servir el id per buscar-lo.
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
    }
?>