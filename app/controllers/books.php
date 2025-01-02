<?php

require_once 'app/models/Book.php';
require_once 'app/helpers/Database.php';

$config = include 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if (!$db->connect()) {
    abort(500);
}

$book = new Book($db);
$books = $book->getAllBooksObj();
$books = array_filter($books, function ($book) {
    $var = $book->getBorrowedBook();
    if (!$var) {
        return true;
    } else {
        if (is_logged_in()) {
            return $var['id_user'] !== $_SESSION['user']['id_user'];
        } else {
            return true;
        }
    }
    return true;
});

$allPendingReqs = $book->getAllPendingBorrowReqs();
if (is_logged_in()) {
    $allPendingReqs = array_filter($allPendingReqs, function ($req) {
        return $req['id_user'] === $_SESSION['user']['id_user'];
    });
}

$allReservations = $book->getAllReservations();
if (is_logged_in()) {
    $allReservations = array_filter($allReservations, function ($res) {
        return $res['id_user'] === $_SESSION['user']['id_user'];
    });
}
function isBookRequested($id_book, $allPendingReqs)
{
    $book = array_filter($allPendingReqs, function ($req) use ($id_book) {
        return $req['id_book'] === $id_book;
    });
    return count($book) > 0;
}

function isBookReserved($id_book, $allReservations)
{
    $book = array_filter($allReservations, function ($res) use ($id_book) {
        return $res['id_book'] === $id_book;
    });
    return count($book) > 0;
}

require_once 'app/views/books.php';
