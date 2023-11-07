<?php

class Device
{
    private string $os; // Sistema operatiu.
    private string $code; // Codi únic.
    private string $description; // Descripció del dispositiu.
    private string $ip; // IP del dispositiu.
    private string $room; // Aula del dispositiu.

    public function __construct($os, $code, $description, $ip, $room)
    { // Constructor del device
        $this->os = $os;
        $this->code = $code;
        $this->description = $description;
        $this->room = $room;
        $this->ip = $ip;
    }

    public function getProperties() : array
    { // Getter d'una array associativa
        return 
        [
            'os' => $this->os,
            'code' => $this->code,
            'description' => $this->description,
            'room' => $this->room,
            'ip' => $this->ip
        ];
    }

    public function insert(string $type) : void // Metod que fa una consulta a la base de dades, i necesita el "role" per comprovar permisos
    {
        $connect = databaseConnect($type); // Crida la funcio databaseConnect, que inicia amb el usuari equivalent als permisos del usuari, i af un mysqli_connect()
        $statement = $connect->prepare("INSERT INTO gestio_incidencies.devices VALUES (DEFAULT,?,?,?,?,?)"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$this->os,$this->code,$this->description,$this->room,$this->ip]); // Executa la consulta.
        mysqli_close($connect);
    }

    public function select(string $type, int $id) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$id]);
        $device = $statement->get_result()->fetch_assoc();
        $this->__construct($device['os'],$device['code'],$device['description'],$device['room'],$device['ip']);
        mysqli_close($connect);
    }

    public function delete(string $type, int $id) : void
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$id]);
        mysqli_close($connect);
    }
}