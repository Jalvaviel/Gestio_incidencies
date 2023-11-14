<?php
include 'helpers.php';

#[AllowDynamicProperties] class Device
{
    private int $id_device;
    private string $os;
    private string $code;
    private string $description;
    private string $ip;
    private string $room;

    public function __construct($id_device = 1, $os = NULL, $code = NULL, $description = NULL, $ip = NULL, $room = NULL)
    { // Builder.
        $this->id_device = $id_device;
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
    }

    public function updateDeviceObject($id_device, $os, $code, $description, $ip, $room): void
    {
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

    /**
     * @throws Exception
     */
    public function insertDeviceIntoDatabase(string $type): void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("INSERT INTO gestio_incidencies.devices VALUES (DEFAULT,?,?,?,?,?)");

        checkStatement($statement, $connect);

        $statement->bind_param('sssis', $this->os, $this->code, $this->description, $this->room, $this->ip);
        $result = $statement->execute();

        checkResult($result, $statement);

        mysqli_close($connect);
    }

    /**
     * @throws Exception
     */
    public function loadDeviceFromDatabase(string $type): void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?");

        checkStatement($statement, $connect);

        $statement->bind_param('i', $this->id_device);
        $result = $statement->execute();

        if ($result) {
            $device = $statement->get_result()->fetch_assoc();
            $this->updateDeviceObject($device['id_device'], $device['os'], $device['code'], $device['description'], $device['room'], $device['ip']);
            mysqli_close($connect);
            echo "Data carregada correctament.";
        } else {
            throw new Exception("Error executing query: " . $statement->error);
        }
    }

    /**
     * @throws Exception
     */
    public function deleteDeviceFromDatabase(string $type): void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.devices WHERE id_device = ?");

        checkStatement($statement, $connect);

        $statement->bind_param('i', $this->id_device);
        $result = $statement->execute();

        checkResult($result, $statement);

        mysqli_close($connect);
    }

    /**
     * @throws Exception
     */
    public function updateDeviceInDatabase(string $type): void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("UPDATE gestio_incidencies.devices SET os=?, code=?, description=?, room=?, ip=? WHERE id_device = ?");

        checkStatement($statement, $connect);

        $statement->bind_param('issss', $this->id_device, $this->os, $this->code, $this->description, $this->room, $this->ip);
        $result = $statement->execute();

        checkResult($result, $statement);

        mysqli_close($connect);
    }

}
