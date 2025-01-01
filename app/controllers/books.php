<?php

    require_once 'app/models/Book.php';
    require_once 'app/helpers/Database.php';

    $config = include'app/config/database.php';
    $db = new Database($config['username'], $config['password'], $config['database']);

    if (!$db->connect()) {
        abort(500);
    }

    $book = new Book($db);
    $books = $book->getAllBooksObj();
    
    $books = array_filter($books, function($book) {
        $book->getBorrowedBook();
        $var = $book->getBorrowedBook();
        if(!$var){
            return true;
        } else{
            if(is_logged_in()){
                if ($var['id_user'] ===  $_SESSION['user']['id_user']){
                    return false;
                } else{
                    return true;
                }
            } else {
                return true;
            }
        }
    });

    $allPendingReqs = $book->getAllPendingBorrowReqs();
    if (is_logged_in()) {
        $allPendingReqs = array_filter($allPendingReqs, function ($req) {
            return $req['id_user'] === $_SESSION['user']['id_user'];
        });
    }

    function isBookRequested($id_book, $allPendingReqs)
    {
        $book = array_filter($allPendingReqs, function ($req) use ($id_book) {
            return $req['id_book'] === $id_book;
        });
        return count($book) > 0;
    }

    require_once'app/views/books.php';



?>