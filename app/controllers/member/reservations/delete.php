
<?php
require_once 'app/models/Book.php';
require_once 'app/helpers/Database.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);
if (!$db->connect()) {
    abort(500);
}

if (!isset($_GET['id'])) {
    abort(400);
}

$book = new Book($db);

if ($book->cancelReservation($_GET['id'])) {
    header('Location: /reservations');
} else {
    set_error('reservation', 'Failed to cancel reservation');
    header('Location: /reservations');
}
