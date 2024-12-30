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

// Get request details
$sql = "SELECT * FROM BorrowRequests WHERE id_borrow_request = :id";
$request = $db->fetch($sql, [':id' => $request_id]);

if (!$request) {
    set_error('request_not_found', 'Borrow request not found');
    header('Location: /admin/borrowings');
    exit;
}

// Check if book exists and get its status
$book->findById($request['id_book']);
if ($book->getStatus() === 'borrowed') {
    set_error('approve_request', 'Cannot approve request. Book is already borrowed.');
    header('Location: /admin/borrowings');
    exit;
}

// Approve this request
if ($book->approveBorrowRequest($request_id)) {
    // Reject all other pending requests for this book
    $book->rejectOtherPendingRequests($request_id);
    header('Location: /admin/borrowings');
    exit;
} else {
    set_error('approve_request', 'Failed to approve the request');
    header('Location: /admin/borrowings');
    exit;
}
