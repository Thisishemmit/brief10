<?php
require_once 'app/helpers/errors.php';
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if (!$db->connect()) {
    abort(500);
}

if (!isset($_GET['id'])) {
    set_error('request_not_found', 'Borrow request not found');
    header('Location: /admin/borrowings');
    exit;
}

$request_id = $_GET['id'];
$book = new Book($db);
$request_id = $_GET['id'];
$book = new Book($db);

$request = $book->getBorrowReqById($request_id);
if (!$request) {
    set_error('request_not_found', 'Borrow request not found');
    header('Location: /admin/borrowings');
    exit;
}

$book->findById($request['id_book']);

if ($book->rejectBorrowRequest($request_id)) {
    header('Location: /admin/borrowings');
    exit;
} else {
    set_error('reject_request', 'Failed to reject the request');
    header('Location: /admin/borrowings');
    exit;
}
