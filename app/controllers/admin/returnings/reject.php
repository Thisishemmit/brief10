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
    set_error('return_request', 'Return request not found');
    header('Location: /admin/returnings');
    exit;
}

$request_id = $_GET['id'];
$book = new Book($db);

$sql = "SELECT * FROM ReturnRequests WHERE id_return_request = :id AND status = 'pending'";
$request = $db->fetch($sql, [':id' => $request_id]);

if (!$request) {
    set_error('return_request', 'Return request not found or already processed');
    header('Location: /admin/returnings');
    exit;
}

if ($book->rejectReturnRequest($request_id)) {
    header('Location: /admin/returnings');
    exit;
} else {
    set_error('return_request', 'Failed to reject return request');
    header('Location: /admin/returnings');
    exit;
}
