<?php
include 'helpers.php';

class Device // This allows the properties in the builder to change dynamically, since it is called again in "select" function
{
    private int $id_device;
    private string $os;
    private string $code;
    private string $description;
    private string $ip;
    private string $room;

    public function __construct($id_device, $os, $code, $description, $ip, $room)
    { // Builder.
        $this->id_device = $id_device;
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
    }
    public function getDeviceProperties() : array
    { // Associative array getter.
        return [
            'id_device' => $this->id_device,
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
        $statement = $connect->prepare("INSERT INTO gestio_incidencies.devices VALUES (?,?,?,?,?,?)"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->bind_param('isssis',$this->id_device,$this->os,$this->code,$this->description,$this->room,$this->ip);
        $statement->execute();
        if ($statement->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $connect->error;
        }

        mysqli_close($connect);
    }

    public function selectDeviceFromDatabase(string $type) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the query to prevent SQL attacks.
        $statement->bind_param('i',$this->id_device);
        $statement->execute();
        if ($statement->execute()) {
            $device = $statement->get_result()->fetch_assoc();
            $this->id = $device['id_device']; // This is niggerlicious
            $this->os = $device['os'];
            $this->code = $device['code'];
            $this->description = $device['description'];
            $this->room = $device['room'];
            $this->ip = $device['ip'];
            mysqli_close($connect);
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $connect->error;
        }
    }

    public function deleteDeviceFromDatabase(string $type) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->bind_param('i',$this->id_device);
        $statement->execute();
        if ($statement->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $connect->error;
        }
        mysqli_close($connect);
    }
}