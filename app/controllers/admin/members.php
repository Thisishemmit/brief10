<?php

require_once'app/helpers/auth.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/errors.php';
require_once 'app/models/User.php';

require_auth();

$config = include 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);
if (! $db->connect()) {
    abort(500);
}


$user = new User($db);
$allUsers = $user->getAllUsers();

require_once 'app/views/admin/members.php';