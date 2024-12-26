<?php
require_once 'app/helpers/Database.php';
require_once 'app/models/User.php';

class Book
{
    private $id;
    private $title;
    private $author;
    private $category_id;
    private $cover_image;
    private $summary;
    private $id_user;
    private $status;
    private $created_at;
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function getCategoryId()
    {
        return $this->category_id;
    }
    public function getCoverImage()
    {
        return $this->cover_image;
    }
    public function getSummary()
    {
        return $this->summary;
    }
    public function getUserId()
    {
        return $this->id_user;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function create($title, $author, $category_id, $cover_image, $summary, $id_user, $status = 'available')
    {
        $sql = "INSERT INTO Books (title, author, category_id, cover_image, summary, id_user, status)
                VALUES (:title, :author, :category_id, :cover_image, :summary, :id_user, :status)";
        $params = [
            ':title' => $title,
            ':author' => $author,
            ':category_id' => $category_id,
            ':cover_image' => $cover_image,
            ':summary' => $summary,
            ':id_user' => $id_user,
            ':status' => $status
        ];

        if ($this->db->query($sql, $params)) {
            $book = $this->db->lastInsertId();
            $book = $this->findById($book);
            $this->id = $book['id_book'];
            $this->title = $book['title'];
            $this->author = $book['author'];
            $this->category_id = $book['category_id'];
            $this->cover_image = $book['cover_image'];
            $this->summary = $book['summary'];
            $this->id_user = $book['id_user'];
            $this->status = $book['status'];
            $this->created_at = $book['created_at'];
            return true;
        }
        return false;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM Books WHERE id_book = :id";
        $params = [':id' => $id];
        $book = $this->db->fetch($sql, $params);
        if ($book) {
            $this->id = $book['id_book'];
            $this->title = $book['title'];
            $this->author = $book['author'];
            $this->category_id = $book['category_id'];
            $this->cover_image = $book['cover_image'];
            $this->summary = $book['summary'];
            $this->id_user = $book['id_user'];
            $this->status = $book['status'];
            $this->created_at = $book['created_at'];
            return true;
        }
        return false;
    }

    public function update($title, $author, $category_id, $cover_image, $summary)
    {
        $sql = "UPDATE Books SET title = :title, author = :author, category_id = :category_id,
                cover_image = :cover_image, summary = :summary WHERE id_book = :id";
        $params = [
            ':id' => $this->id,
            ':title' => $title,
            ':author' => $author,
            ':category_id' => $category_id,
            ':cover_image' => $cover_image,
            ':summary' => $summary
        ];
        if ($this->db->query($sql, $params)) {
            $this->title = $title;
            $this->author = $author;
            $this->category_id = $category_id;
            $this->cover_image = $cover_image;
            $this->summary = $summary;
            return true;
        }
        return false;
    }

    public function delete()
    {
        $sql = "DELETE FROM Books WHERE id_book = :id";
        $params = [':id' => $this->id];
        if ($this->db->query($sql, $params)) {
            $this->id = null;
            $this->title = null;
            $this->author = null;
            $this->category_id = null;
            $this->cover_image = null;
            $this->summary = null;
            $this->id_user = null;
            $this->status = null;
            $this->created_at = null;
            return true;
        }
        return false;
    }

    public function updateStatus($status)
    {
        $sql = "UPDATE Books SET status = :status WHERE id_book = :id";
        $params = [':id' => $this->id, ':status' => $status];
        if ($this->db->query($sql, $params)) {
            $this->status = $status;
            return true;
        }
        return false;
    }

    public function getAllBooks()
    {
        $sql = "SELECT * FROM Books";
        $books = $this->db->fetchAll($sql);
        $books = array_map(function ($book) {
            $b = new Book($this->db);
            $b->id = $book['id_book'];
            $b->title = $book['title'];
            $b->author = $book['author'];
            $b->category_id = $book['category_id'];
            $b->cover_image = $book['cover_image'];
            $b->summary = $book['summary'];
            $b->id_user = $book['id_user'];
            $b->status = $book['status'];
            $b->created_at = $book['created_at'];
            return $b;
        }, $books);
        return $books;
    }

    public function getCurrentBorrower()
    {
        if ($this->status !== 'borrowed') return null;

        $sql = "SELECT u.* FROM Users u
                JOIN BorrowedBooks bb ON u.id_user = bb.id_user
                WHERE bb.id_book = :id AND bb.returned_at IS NULL";
        $params = [':id' => $this->id];
        $user = $this->db->fetch($sql, $params);

        if ($user) {
            $u = new User($this->db);
            $u->findById($user['id_user']);
            return $u;
        }
        return null;
    }

    public function getReservations()
    {
        $sql = "SELECT u.*, r.reserved_at FROM Users u
                JOIN Reservations r ON u.id_user = r.id_user
                WHERE r.id_book = :id
                ORDER BY r.reserved_at ASC";
        $params = [':id' => $this->id];
        return $this->db->fetchAll($sql, $params);
    }
}
