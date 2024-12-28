<?php

require_once 'app/helpers/auth.php';
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

require_auth();

$config = include 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if (!$db->connect()) {
    abort(500);
}

$bookMngr = new Book($db);
$books = $bookMngr->getAllBooksObj();

require_once 'app/views/admin/books.php';
