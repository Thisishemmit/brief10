<?php
require_once 'app/models/User.php';
require_once 'app/models/Book.php';
require_once 'app/helpers/Database.php';

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);
if (!$db->connect()) {
    abort(500);
}
$user = new User($db);
$user->findById($_SESSION['user']['id_user']);

$reserved_books = $user->getReservations();

require 'app/views/reservations.php';
