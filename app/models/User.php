<?php
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
    private $created_at;
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getID()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function get()
    {
        return [
            'id_user' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at
        ];
    }

    public function create($name, $email, $password, $role)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ];
        if ($this->db->query($sql, $params)) {
            $user = $this->db->lastInsertId();
            $user = $this->findByid($user);
            return true;
        }
        return false;
    }


    public function findByid($id)
    {
        $sql = "SELECT * FROM Users WHERE id_user = :id";
        $params = [':id' => $id];
        $user = $this->db->fetch($sql, $params);
        if ($user) {
            $this->id = $user['id_user'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->role = $user['role'];
            $this->created_at = $user['created_at'];
            return true;
        }
        return false;
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM Users WHERE email = :email";
        $params = [':email' => $email];
        $user = $this->db->fetch($sql, $params);
        if ($user) {
            $this->id = $user['id_user'];
            $this->name = $user['name'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->role = $user['role'];
            $this->created_at = $user['created_at'];
            return true;
        }
        return false;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }



    public function update($name, $email, $password, $role)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE Users SET name = :name, email = :email, password = :password, role = :role WHERE id_user = :id";
        $params = [
            ':id' => $this->id,
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':role' => $role
        ];
        if($this->db->query($sql, $params)){
            $this->name = $name;
            $this->email = $email;
            $this->password = $password;
            $this->role = $role;
            return true;
        }
        return false;
    }

    public function delete()
    {
        $sql = "DELETE FROM Users WHERE id_user = :id";
        $params = [':id' => $this->id];

        if ($this->db->query($sql, $params)) {
            $this->id = null;
            $this->name = null;
            $this->email = null;
            $this->role = null;
            $this->created_at = null;
            return true;
        }
        return false;
    }

    public function updatePassword($password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE Users SET password = :password WHERE id_user = :id";
        $params = [':id' => $this->id, ':password' => $password];
        if ($this->db->query($sql, $params)) {
            $this->password = $password;
            return true;
        }
        return false;
    }

    public function updateRole($role)
    {
        $sql = "UPDATE Users SET role = :role WHERE id_user = :id";
        $params = [':id' => $this->id, ':role' => $role];
        if ($this->db->query($sql, $params)) {
            $this->role = $role;
            return true;
        }
        return false;
    }

    public function updateName($name)
    {
        $sql = "UPDATE Users SET name = :name WHERE id_user = :id";
        $params = [':id' => $this->id, ':name' => $name];
        if ($this->db->query($sql, $params)) {
            $this->name = $name;
            return true;
        }
        return false;
    }

    public function updateEmail($email)
    {
        $sql = "UPDATE Users SET email = :email WHERE id_user = :id";
        $params = [':id' => $this->id, ':email' => $email];
        if ($this->db->query($sql, $params)) {
            $this->email = $email;
            return true;
        }
        return false;
    }


    public function getAllUsers()
    {
        $sql = "SELECT * FROM Users";
        $users = $this->db->fetchAll($sql);
        $users = array_map(function ($user) {
            $u = new User($this->db);
            $u->findByID($user['id_user']);
            return $u;
        }, $users);
        return $users;
    }

    public function getBorrowedBooks()
    {
        if (!$this->id) return [];

        $sql = "SELECT b.*, bb.borrowed_at, bb.due_at, bb.returned_at
                FROM Books b
                JOIN BorrowedBooks bb ON b.id_book = bb.id_book
                WHERE bb.id_user = :id
                ORDER BY bb.borrowed_at DESC";

        $params = [':id' => $this->id];
        $books = $this->db->fetchAll($sql, $params);
        $books = array_map(function ($book) {
            $b = new Book($this->db);
            $b->findByID($book['id_book']);
            return $b;
        }, $books);
        return $books;
    }
    public function getReservations()
    {
        if (!$this->id) return [];

        $sql = "SELECT b.*, r.reserved_at
                FROM Books b
                JOIN Reservations r ON b.id_book = r.id_book
                WHERE r.id_user = :id
                ORDER BY r.reserved_at DESC";

        $params = [':id' => $this->id];
        $books = $this->db->fetchAll($sql, $params);
        $books = array_map(function ($book) {
            $b = new Book($this->db);
            $b->findByID($book['id_book']);
            return $b;
        }, $books);
        return $books;
    }

}
