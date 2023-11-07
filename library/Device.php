<?php

class Device
{
    private string $os;
    private string $code;
    private string $description;
    private string $ip;
    private string $room;

    public function create_device($os, $code, $description, $ip, $room) : void{ // Builder.
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
    }

    public function get_device_properties() : array{ // Associative array getter.
        return [
            'os' => $this->os,
            'code' => $this->code,
            'description' => $this->description,
            'room' => $this->room,
            'ip' => $this->ip
        ];
    }

    public function insert_device_into_database(string $type) : void{
        assert($type=='technician' or $type=='admin','$Error insertant dispositiu, potser no hi tens permissos?'); // Redundant, but just in case the database fails checking privileges.
        $env = parse_ini_file('.env');
        $db_user = $env[$type]; // Depending on the type of worker that wants to insert the device.
        $db_password = $env[$type . '_password'];
        $connect = database_connect($db_user, $db_password);
        $statement = $connect->prepare("INSERT INTO gestio_incidencies.devices VALUES (DEFAULT,?,?,?,?,?)"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->execute([$this->os,$this->code,$this->description,$this->room,$this->ip]);
        mysqli_close($connect);
    }

    public function select_device_from_database(string $type, int $id) : void{
        $env = parse_ini_file('.env');
        $db_user = $env[$type]; // Depending on the type of worker that wants to select the device.
        $db_password = $env[$type . '_password'];
        $connect = database_connect($db_user, $db_password);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the query to prevent SQL attacks.
        $statement->execute([$id]);
        $device = $statement->get_result()->fetch_assoc();
        $this->create_device($device['os'],$device['code'],$device['description'],$device['room'],$device['ip']);
        mysqli_close($connect);
    }

    public function delete_device_from_database(string $type, int $id) : void{
        assert($type=='technician' or $type=='admin','$Error insertant dispositiu, potser no hi tens permissos?'); // Redundant, but just in case the database fails checking privileges.
        $env = parse_ini_file('.env');
        $db_user = $env[$type]; // Depending on the type of worker that wants to insert the device.
        $db_password = $env[$type . '_password'];
        $connect = database_connect($db_user, $db_password);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->execute([$id]);
        mysqli_close($connect);
    }
}