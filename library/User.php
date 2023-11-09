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

    private function executeQuery($connect, $statement)
    {
        
    }

    public function insert(string $type, int $id)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$id]);
        $user = $statement->get_result()->fetch_assoc();
        $this->__construct($user['user_id'], $user['name'], $user['surname'], $user['email'], $user['password'], $user['role']);
        mysqli_close($connect);
    }

    public function select(string $type, $id) // Funció dedicada a buscar usuaris per ID únicament
    {
        $connect = databaseConnect($type); // Fa la conexió a la base de dades
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections. 
        $statement->bind_param("s", $id);
        if(!$statement->execute()) // Executa i comproba si s'executa bé el codi
        {
            echo("Error: " . $statement->error);
        }
        else
        {
            $num_rows = $statement->num_rows;
            if($num_rows <= 0) // Comproba si troba algún usuari
            {
                echo("Error: no s'ha trobat cap usuari."); 
            }
            else
            {
                $user = $statement->get_result()->fetch_assoc(); // Guarda la informació als atributs de la clase
                $this->id_user = $user['id_user'];
                $this->name = $user['name'];
                $this->surname = $user['surname'];
                $this->email = $user['email'];
                $this->password = $user['password'];
                $this->role = $user['role'];
            }
        }
        mysqli_close($connect); // Tanca la conexió
    }

    public function login(string $type, $email)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("SELECT * FROM gestio_incidencies.users WHERE email = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->bind_param("s", $email);
        $statement->execute();
        $user = $statement->get_result()->fetch_assoc();
        $this->id_user = $user['id_user'];
        $this->name = $user['name'];
        $this->surname = $user['surname'];
        $this->email = $email;
        $this->password = $user['password'];
        $this->role = $user['role'];
        mysqli_close($connect);
    }

    public function delete(string $type, $id)
    {
        $connect = databaseConnect($type);
        $statement = $connect->prepare("DELETE FROM gestio_incidencies.users WHERE id_user = ?"); // Prepara i executa el insert per prevenir SQL injections.
        $statement->execute([$id]);
        $user = $statement->get_result()->fetch_assoc();
        mysqli_close($connect);
    }

    public function getInfo()
    {
        return [
            $this->id_user,
            $this->name,
            $this->surname,
            $this->email,
            $this->password,
            $this->role
        ];
    }

}
?>