<?php
require_once 'app/models/Category.php';
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
    public function getCategory()
    {
        $cat = new Category($this->db);
        $cat->findById($this->category_id);
        return $cat->getName();
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

    public function get()
    {
        return [
            'id_book' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'category_id' => $this->category_id,
            'cover_image' => $this->cover_image,
            'summary' => $this->summary,
            'id_user' => $this->id_user,
            'status' => $this->status,
            'created_at' => $this->created_at
        ];
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
        $sql = "UPDATE Books SET
                title = :title,
                author = :author,
                category_id = :category_id,
                cover_image = :cover_image,
                summary = :summary
                WHERE id_book = :id";

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
            return $b->get();
        }, $books);
        return $books;
    }

    public function getAllBooksObj()
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
            return $u->get();
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

    public function borrow($user_id, $deu_date)
    {
        if (!$this->id) {
            return false;
        }
        $sql = "INSERT INTO BorrowedBooks (id_book, id_user, due_at) VALUES (:id_book, :id_user,:due_at)";
        $params = [
            ":id_book" => $this->id,
            ":id_user" => $user_id,
            ":due_at" => $deu_date
        ];

        if ($this->db->query($sql, $params)) {
            return $this->updateStatus('borrowed');
        }
        return false;
    }

    public function reserve($user_id)
    {
        if (!$this->id) {
            return false;
        }
        $sql = "INSERT INTO Reservations (id_book, id_user) VALUES (:id_book, :id_user)";
        $params = [':id_book' => $this->id, ':id_user' => $user_id];
        return $this->db->query($sql, $params);
    }

    public function requestBorrow($user_id, $due_date)
    {
        if (!$this->id) {
            return false;
        }
        $sql = "INSERT INTO BorrowRequests (id_book, id_user, due_at) VALUES (:id_book, :id_user, :due_at)";
        $params = [':id_book' => $this->id, ':id_user' => $user_id, ':due_at' => $due_date];
        return $this->db->query($sql, $params);
    }

    public function getBookBorrowReqs()
    {
        $sql = "SELECT * FROM BorrowRequests WHERE id_book = :id";
        $params = [':id' => $this->id];
        return $this->db->fetchAll($sql, $params);
    }

    public function getAllBorrowReqs()
    {
        $sql = "SELECT * FROM BorrowRequests";
        return $this->db->fetchAll($sql);
    }

    public function getAllBorrowed()
    {
        $sql = "SELECT * FROM BorrowedBooks";
        return $this->db->fetchAll($sql);
    }

    public function getAllPendingBorrowReqs()
    {
        $sql = "SELECT * FROM BorrowRequests WHERE status = 'pending'";
        return $this->db->fetchAll($sql);
    }

    public function getAllReservations()
    {
        $sql = "SELECT * FROM Reservations";
        return $this->db->fetchAll($sql);
    }

    public function approveBorrowRequest($request_id)
    {
        $sql = "UPDATE BorrowRequests SET status = 'approved' WHERE id_borrow_request = :id";
        $params = [':id' => $request_id];
        if ($this->db->query($sql, $params)) {
            $sql = "SELECT id_user, due_at FROM BorrowRequests WHERE id_borrow_request = :id";
            $params = [':id' => $request_id];
            $request = $this->db->fetch($sql, $params);
            if ($request) {
                return $this->borrow($request['id_user'], $request['due_at']);
            }
        }
        return false;
    }

    public function rejectBorrowRequest($request_id)
    {
        $sql = "UPDATE BorrowRequests SET status = 'rejected' WHERE id_borrow_request = :id";
        $params = [':id' => $request_id];
        return $this->db->query($sql, $params);
    }

    private function getBorrowedBook(){
        $sql = "SELECT * FROM BorrowedBooks WHERE id_book = :id AND returned_at IS NULL";
        $params = [':id' => $this->id];
        return $this->db->fetch($sql, $params);
    }
    public function requestReturn()
    {
        $BB = $this->getBorrowedBook();
        $sql = "INSERT INTO ReturnRequests (id_borrowed_book, id_user) VALUES (:id_borrowed_book, :id_user)";
        $params = [':id_borrowed_book' => $BB['id_borrowed_book'], ':id_user' => $BB['id_user']];
        return $this->db->query($sql, $params);
    }


    public function approveReturnRequest($request_id)
    {
        $sql = "UPDATE ReturnRequests SET status = 'approved' WHERE id_return_request = :id";
        $params = [':id' => $request_id];
        if ($this->db->query($sql, $params)) {
            $sql = "UPDATE BorrowedBooks SET returned_at = CURRENT_TIMESTAMP WHERE id_borrowed_book = :id";
            $params = [':id' => $request_id];
            if ($this->db->query($sql, $params)) {
                return $this->updateStatus('available');
            }
        }
        return false;
    }

    public function rejectReturnRequest($request_id)
    {
        $sql = "UPDATE ReturnRequests SET status = 'rejected' WHERE id_return_request = :id";
        $params = [':id' => $request_id];
        return $this->db->query($sql, $params);
    }

    public function rejectOtherPendingRequests($approved_request_id)
    {
        $sql = "UPDATE BorrowRequests
                SET status = 'rejected'
                WHERE id_book = :id_book
                AND id_borrow_request != :request_id
                AND status = 'pending'";

        $params = [
            ':id_book' => $this->id,
            ':request_id' => $approved_request_id
        ];

        return $this->db->query($sql, $params);
    }

    public function getBooksWithReservations()
    {
        $sql = "SELECT b.*,
                COUNT(r.id_reservation) as reservation_count
                FROM Books b
                LEFT JOIN Reservations r ON b.id_book = r.id_book
                GROUP BY b.id_book
                HAVING reservation_count > 0";
        return $this->db->fetchAll($sql);
    }

    public function getBookReservationsWithUsers($book_id)
    {
        $sql = "SELECT r.*, u.name as user_name
                FROM Reservations r
                JOIN Users u ON r.id_user = u.id_user
                WHERE r.id_book = :book_id
                ORDER BY r.reserved_at ASC";
        return $this->db->fetchAll($sql, [':book_id' => $book_id]);
    }

    public function searchByCategory($term)
    {
        $sql = "SELECT b.* FROM Books b
                JOIN Categories c ON b.category_id = c.id_category
                WHERE c.name LIKE :term OR b.title LIKE :term2";
        $params = [':term' => '%' . $term . '%', ':term2' => '%' . $term . '%'];
        $books = $this->db->fetchAll($sql, $params);

        return array_map(function ($book) {
            return [
                'id_book' => $book['id_book'],
                'title' => $book['title'],
                'author' => $book['author'],
                'category_id' => $book['category_id'],
                'cover_image' => $book['cover_image'],
                'summary' => $book['summary'],
                'id_user' => $book['id_user'],
                'status' => $book['status'],
                'created_at' => $book['created_at']
            ];
        }, $books);
    }

    public function hasPendingReturnRequest()
    {
        $id_borrowed_book = $this->getBorrowedBook()['id_borrowed_book'];
        $sql = "SELECT * FROM ReturnRequests WHERE id_borrowed_book = :id AND status = 'pending'";
        $params = [':id' => $id_borrowed_book];
        $req = $this->db->fetch($sql, $params);
        return $req ? true : false;

    }
    public function getAllBorrowedBooks()
    {
        $sql = "SELECT b.id_book, u.id_user
                FROM BorrowedBooks bb
                JOIN Books b ON bb.id_book = b.id_book
                JOIN Users u ON bb.id_user = u.id_user
                WHERE bb.returned_at IS NULL";
        $books = $this->db->fetchAll($sql);
        if ($books) {
           $books = array_map(function ($book) {
                $b = new Book($this->db);
                $b->findById($book['id_book']);
                $u = new User($this->db);
                $u->findById($book['id_user']);
                return [
                    'book' => $b,
                    'user' => $u
                ];
            }, $books);
            return $books;
        }
        return [];
    }

    public function getReturnRequests()
    {
        $id_borrowed_book = $this->getBorrowedBook()['id_borrowed_book'];
        $sql = "SELECT * FROM ReturnRequests WHERE id_borrowed_book = :id";
        $params = [':id' => $id_borrowed_book];
        $reqs =  $this->db->fetchAll($sql, $params);
        if ($reqs) {
            $reqs = array_map(function ($req) {
                $u = new User($this->db);
                $u->findById($req['id_user']);
                return [
                    'request' => $req,
                    'user' => $u
                ];
            }, $reqs);
            return $reqs;
        } else {
            return [];
        }
    }
}
