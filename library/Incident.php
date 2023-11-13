<?php

#[AllowDynamicProperties] class Incident
{
    private int $id_incident;
    private string $description;
    private string $status;
    private string $date;
    private User $user;

    public function __construct($id_incident = 1, $description = NULL, $status = NULL, $date = NULL, $user = NULL)
    { // Builder.
        $this->id_incident = $id_incident;
        $this->description = $description;
        $this->status = $status;
        $this->date = $date;
        $this->user = $user;
    }

    public function getIncidentProperties() : array
    { // Associative array getter.
        return [
            'id_incident' => $this->id_incident,
            'description' => $this->description,
            'status' => $this->status,
            'date' => $this->date,
            'user' => $this->user
        ];
    }

    public function insertIncidentIntoDatabase(string $type) : void // Gets the Incident object and inserts it into the DB.
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("INSERT INTO gestio_incidencies.incidents VALUES (DEFAULT,?,?,?,?)"); // Prepare and execute the insert to prevent SQL attacks.
        $idUser = $this->user->getUserProperties('id_user');
        $statement->bind_param('sssi',$this->description,$this->status,$this->date,$idUser);
        $statement->execute();
        if ($statement->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error: " . $connect->error;

        }

        mysqli_close($connect);
    }
    public function loadIncidentFromDatabase(string $type) : void // Gets the incident from the DB with the same incident_id and updates the Incident object with the same properties.
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.incidents WHERE id_incident = ?"); // Prepare and execute the query to prevent SQL attacks.
        $statement->bind_param('i',$this->id_incident);
        $statement->execute();
        if ($statement->execute()) {
            $incident = $statement->get_result()->fetch_assoc();
            $user = new User($incident['id_user']); // Create a new User object associated with the user in the database entry.
            $this->__construct($incident['id_device'], $incident['description'], $incident['status'], $incident['date'], $user->select($type,$incident['id_user']));
            mysqli_close($connect);
            echo "Data loaded successfully.";
        } else {
            echo "Error: " . $connect->error;
        }
    }

    public function deleteIncidentFromDatabase(string $type) : void // Gets the incident from the DB with the same incident_id and deletes it from the DB.
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.incidents WHERE id_incident = ?"); // Prepare and execute the insert to prevent SQL attacks.
        $statement->bind_param('i',$this->id_incident);
        $statement->execute();
        if ($statement->execute()) {
            echo "Data deleted successfully.";
        } else {
            echo "Error: " . $connect->error;
        }
        mysqli_close($connect);
    }
}