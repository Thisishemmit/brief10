<?php
session_start();

// Process available books for reservations
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if ($db->connect()) {
    $book = new Book($db);
    $book->findById(3);
    // $book->requestReturn();
}


require 'routes.php';
