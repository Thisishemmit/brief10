<?php
require_once 'app/helpers/errors.php';
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if (!$db->connect()) {
    abort(500);
}

$booksManager = new Book($db);

$mostBorrowedBooks = $booksManager->getMostBorrowedBooks();
$mostActiveUsers = $booksManager->getMostActiveUsers();

require 'app/views/admin/statistics.php';
