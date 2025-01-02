<?php
require_once 'app/models/Book.php';
require_once 'app/helpers/Database.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);
if (!$db->connect()) {
    abort(500);
}

if (!isset($_GET['id'])) {
    abort(400);
}

$borrowed_book_id = $_GET['id'];
$sql = "SELECT id_book FROM BorrowedBooks WHERE id_borrowed_book = :id";
$book_id = $db->fetch($sql, [':id' => $borrowed_book_id])['id_book'];

$book = new Book($db);
if (!$book->findById($book_id)) {
    abort(404);
}

if ($book->hasPendingReturnRequest()) {
    set_error('return_request', 'A return request is already pending for this book');
    header('Location: /borrows');
    exit;
}

if ($book->requestReturn()) {
    header('Location: /borrows');
} else {
    set_error('return_request', 'Failed to submit return request');
    header('Location: /borrows');
}
