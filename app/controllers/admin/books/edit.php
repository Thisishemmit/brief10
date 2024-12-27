<?php
require_once 'app/models/Book.php';
require_once 'app/models/Category.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

require_auth();

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);
if (!$db->connect()) {
    abort(500);
}

$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    abort(404);
}

// Load the book
$book = new Book($db);
if (!$book->findById($book_id)) {
    abort('book_not_found_admin');
}

// Get current book data
$bookData = [
    'id' => $book->getId(),
    'title' => $book->getTitle(),
    'author' => $book->getAuthor(),
    'category_id' => $book->getCategoryId(),
    'cover_image' => $book->getCoverImage(),
    'summary' => $book->getSummary()
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_book'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category_id'];
    $summary = trim($_POST['summary']);
    $cover_image = trim($_POST['cover_image']);
    $error = '';

    // Validate inputs
    if (empty($title) || empty($author) || empty($category_id) || empty($summary) || empty($cover_image)) {
        $error = 'Please fill all required fields';
        set_error('edit_book', $error);
    }

    // Validate category
    $cat = new Category($db);
    if (!$cat->findById($category_id)) {
        $error = 'Invalid category';
        set_error('edit_book', $error);
    }

    if (empty($error)) {
        if ($book->update($title, $author, $category_id, $cover_image, $summary)) {
            header('Location: /admin/books');
            exit;
        } else {
            $error = 'Failed to update book';
            set_error('edit_book', $error);
        }
    }

    // Update bookData with submitted values if there was an error
    if (!empty($error)) {
        $bookData = [
            'id' => $book->getId(),
            'title' => $title,
            'author' => $author,
            'category_id' => $category_id,
            'cover_image' => $cover_image,
            'summary' => $summary
        ];
    }
}

// Get categories for the dropdown
$categories = $db->fetchAll("SELECT * FROM Categories");

require 'app/views/admin/books/edit.php';
