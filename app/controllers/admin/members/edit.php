<?php

    require_once 'app/models/User.php';
    require_once 'app/helpers/Database.php';
    require_once 'app/helpers/auth.php';

    $email = $_GET['userEmail'] ?? null;
    $role = $_GET['role'] ?? null;
    if (empty($email) || empty($role)) {
        abort(404);
    }

    if ($role !== 'admin' && $role !== 'member') {
        abort(code: 'member_not_found');
    }

    $config = include 'app/config/database.php';
    $db =  new Database($config['username'], $config['password'], $config['database']);

    if (!$db->connect()) {
        abort(500);
    }

    $user = new User($db);
    if ($user->findByEmail($email)){
        if (!$user->updateRole($role)) {
           abort(500);
        }
        header('Location: /admin/members');
    } else {
        abort(code: 'member_not_found');
    }

?>
