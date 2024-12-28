<?php

require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

require_auth();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $config = require 'app/config/database.php';
    $db = new Database($config['username'], $config['password'], $config['database']);
    if (!$db->connect()) {
        abort(500);
    }

    $book = new Book($db);
    if ($book->findById($id)) {
        if (!$book->delete()) {
            $error = "Couldn't delete book: Unknown error";
            set_error('delete_book', $error);
        }
    } else {
        $error = "Couldn't delete book: book not found";
        set_error('delete_book', $error);
    }
}
header('Location: /admin/books');
