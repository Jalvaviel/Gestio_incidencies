<?php

class Device
{
    private string $os;
    private string $code;
    private string $description;
    private string $ip;
    private string $room;

    public function createDevice($os, $code, $description, $ip, $room) : void
    { // Builder.
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
    }

    public function getDeviceProperties() : array
    { // Associative array getter.
        return [
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

    public function selectDeviceFromDatabase(string $type, int $id) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the query to prevent SQL attacks.
        $statement->execute([$id]);
        $device = $statement->get_result()->fetch_assoc();
        $this->createDevice($device['os'],$device['code'],$device['description'],$device['room'],$device['ip']);
        mysqli_close($connect);
    }

    public function deleteDeviceFromDatabase(string $type, int $id) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->execute([$id]);
        mysqli_close($connect);
    }
}