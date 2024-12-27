<?php
require_once 'app/models/Book.php';
require_once 'app/models/Category.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

$config = require 'app/config/database.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category_id'];
    $summary = trim($_POST['summary']);
    $cover_image = trim($_POST['cover_image']);
    $error = '';

    if (empty($title) || empty($author) || empty($category_id) || empty($summary) || empty($cover_image)) {
        $error = 'Please fill all required fields';
        set_error('add_book', $error);
    }

    $db = new Database($config['username'], $config['password'], $config['database']);
    if (!$db->connect()) {
        abort(500);
    }

    $cat = new Category($db);
    if (!$cat->findById($category_id)) {
        $error = 'Invalid category';
        set_error('add_book', $error);
    }

    if (empty($error)) {
        $book = new Book($db);
        if ($book->create($title, $author, $category_id, $cover_image, $summary, $_SESSION['user']['id_user'])) {
            header('Location: /admin/books');
            exit;
        } else {
            $error = 'Failed to add book';
            set_error('add_book', $error);
        }
    }
}

$db = new Database($config['username'], $config['password'], $config['database']);
if (!$db->connect()) {
    abort(500);
}
$categories = $db->fetchAll("SELECT * FROM Categories");
require 'app/views/admin/books/add.php';
