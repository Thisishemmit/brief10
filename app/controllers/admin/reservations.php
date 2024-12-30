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

$booksWithReservations = $booksManager->getBooksWithReservations();

$books = array_map(function($book) use ($booksManager) {
    return [
        'id_book' => $book['id_book'],
        'title' => $book['title'],
        'author' => $book['author'],
        'cover_image' => $book['cover_image'],
        'reservation_count' => $book['reservation_count'],
        'reservations' => $booksManager->getBookReservationsWithUsers($book['id_book'])
    ];
}, $booksWithReservations);

require 'app/views/admin/reservations.php';
