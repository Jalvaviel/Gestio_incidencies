<?php
class User
{
    private int $id_user;
    private string $name;
    private string $surname;
    private string $email;
    private string $password;
    private string $role;

    public function __construct($id_user, $name, $surname, $email, $password, $role)
    {
        $this->id_user = $id_user;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function insert(string $type, int $id)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$id]);
        $user = $statement->get_result()->fetch_assoc();
        $this->__construct($user['user_id'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
        mysqli_close($connect);
    }

    public function select(string $type, int $id)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.devices WHERE id_device = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$this->id_user]);
        $user = $statement->get_result()->fetch_assoc();
        $this->id_user = $user['id_user'];
        $this->name = $user['name'];
        $this->surname = $user['surname'];
        $this->email = $user['email'];
        $this->password = $user['password'];
        $this->role = $user['role'];
        mysqli_close($connect);
    }
}
?>