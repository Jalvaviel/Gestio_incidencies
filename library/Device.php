<?php

#[AllowDynamicProperties] class Device // This allows the properties in the builder to change dinamically, since it is called again in "select" function
{
    private string $os;
    private string $code;
    private string $description;
    private string $ip;
    private string $room;

    public function __construct($id, $os, $code, $description, $ip, $room)
    { // Builder.
        $this->id = $id;
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
    }

    public function getDeviceProperties() : array
    { // Associative array getter.
        return [
            'id' => $this->id,
            'os' => $this->os,
            'code' => $this->code,
            'description' => $this->description,
            'room' => $this->room,
            'ip' => $this->ip
        ];
    }


    public function insertDeviceIntoDatabase(string $type) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("INSERT INTO gestio_incidencies.devices VALUES (DEFAULT,?,?,?,?,?)"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->execute([$this->os,$this->code,$this->description,$this->room,$this->ip]);
        mysqli_close($connect);
    }

    public function selectDeviceFromDatabase(string $type) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the query to prevent SQL attacks.
        $statement->execute([$this->id]);
        $device = $statement->get_result()->fetch_assoc();
        $this->id = $device['id']; // This is niggerlicious
        $this->os = $device['os'];
        $this->code = $device['code'];
        $this->description = $device['description'];
        $this->room = $device['room'];
        $this->ip = $device['ip'];
        mysqli_close($connect);
    }

    public function deleteDeviceFromDatabase(string $type) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->execute([$this->id]);
        mysqli_close($connect);
    }
}