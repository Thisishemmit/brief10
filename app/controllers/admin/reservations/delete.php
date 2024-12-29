<?php
require_once 'app/helpers/errors.php';
require_once 'app/helpers/Database.php';
require_once 'app/models/Book.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if (!$db->connect()) {
    abort(500);
}

if (isset($_GET['id'])) {
    $sql = "SELECT * FROM Reservations WHERE id_reservation = :id";
    $reservation = $db->fetch($sql, [':id' => $_GET['id']]);

    if (!$reservation) {
        set_error('delete_reservation', 'Reservation not found');
        header('Location: /admin/reservations');
        exit;
    }

    $user = new User($db);
    if (!$user->findByid($reservation['id_user'])) {
        set_error('delete_reservation', 'User not found for this reservation');
        header('Location: /admin/reservations');
        exit;
    }

    $sql = "DELETE FROM Reservations WHERE id_reservation = :id";
    if (!$db->query($sql, [':id' => $_GET['id']])) {
        set_error('delete_reservation', 'Failed to delete reservation');
        header('Location: /admin/reservations');
        exit;
    }
} elseif (isset($_GET['book_id']) && isset($_GET['user_id']) && $_GET['user_id'] === 'all') {

    $book = new Book($db);
    if (!$book->findById($_GET['book_id'])) {
        set_error('delete_reservation', 'Book not found');
        header('Location: /admin/reservations');
        exit;
    }

    $sql = "DELETE FROM Reservations WHERE id_book = :id";
    if (!$db->query($sql, [':id' => $_GET['book_id']])) {
        set_error('delete_reservation', 'Failed to delete reservations');
        header('Location: /admin/reservations');
        exit;
    }

} else {
    set_error('delete_reservation', 'Invalid request');
    header('Location: /admin/reservations');
    exit;
}

header('Location: /admin/reservations');
exit;
