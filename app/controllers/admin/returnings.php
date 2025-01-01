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

$borrowedBooks = $booksManager->getAllBorrowedBooks();
$returnings = [];
foreach ($borrowedBooks as $bb) {
    $book = $bb['book'];
    $user = $bb['user'];
    if ($book->hasPendingReturnRequest()) {
        $returnings[] = [
            'book' => $book,
            'user' => $user,
            'returnRequests' => array_filter($book->getReturnRequests(), function ($returnRequest) {
                return $returnRequest['request']['status'] === 'pending';
            }),
        ];
    }
}

$allProccessedReturnings = [];
foreach ($borrowedBooks as $bb) {
    $book = $bb['book'];
    $user = $bb['user'];
    $allProccessedReturnings[] = [
        'book' => $book,
        'user' => $user,
        'returnRequests' => array_filter($book->getReturnRequests(), function ($returnRequest) {
            return $returnRequest['request']['status'] !== 'pending';
        }),
    ];
}


require 'app/views/admin/returnings.php';
