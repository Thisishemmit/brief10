<?php
    require_once 'app/models/Book.php';
    require_once 'app/helpers/Database.php';

    $config = include'app/config/database.php';
    $db = new Database($config['username'], $config['password'], $config['database']);

    if (!$db->connect()) {
        abort(500);
    }
    if(isset($_GET['q'])) {
        $searchTerm = $_GET['q'];
        $book = new Book($db);
        $books = $book->searchByCategory($searchTerm);
        echo json_encode($books);
    }
?>