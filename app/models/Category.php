<?php
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

class Category
{
    private $id;
    private $name;
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }

    public function create($name)
    {
        $sql = "INSERT INTO Categories (name) VALUES (:name)";
        $params = [':name' => $name];

        if ($this->db->query($sql, $params)) {
            $category = $this->db->lastInsertId();
            $category = $this->findById($category);
            $this->id = $category['id_category'];
            $this->name = $category['name'];
            return true;
        }
        return false;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM Categories WHERE id_category = :id";
        $params = [':id' => $id];
        $category = $this->db->fetch($sql, $params);
        if ($category) {
            $this->id = $category['id_category'];
            $this->name = $category['name'];
            return true;
        }
        return false;
    }

    public function update($name)
    {
        $sql = "UPDATE Categories SET name = :name WHERE id_category = :id";
        $params = [':id' => $this->id, ':name' => $name];
        if ($this->db->query($sql, $params)) {
            $this->name = $name;
            return true;
        }
        return false;
    }

    public function delete()
    {
        $sql = "DELETE FROM Categories WHERE id_category = :id";
        $params = [':id' => $this->id];
        if ($this->db->query($sql, $params)) {
            $this->id = null;
            $this->name = null;
            return true;
        }
        return false;
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM Categories";
        $categories = $this->db->fetchAll($sql);
        $categories = array_map(function ($category) {
            $c = new Category($this->db);
            $c->id = $category['id_category'];
            $c->name = $category['name'];
            return $c;
        }, $categories);
        return $categories;
    }

    public function getBooks()
    {
        if (!$this->id) return [];

        $sql = "SELECT * FROM Books WHERE category_id = :category_id";
        $params = [':category_id' => $this->id];
        $books = $this->db->fetchAll($sql, $params);
        $books = array_map(function ($book) {
            $b = new Book($this->db);
            $b->findById($book['id_book']);
            return $b;
        }, $books);
        return $books;
    }
}
