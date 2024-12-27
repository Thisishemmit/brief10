<?php

    require_once 'app/models/User.php';
    require_once 'app/helpers/Database.php';
    require_once 'app/helpers/auth.php';

    if (empty($_GET['userEmail'])) {
        abort(404);
    }


    $config = include 'app/config/database.php';
    $db = new Database($config['username'], $config['password'], $config['database']);

    if (!$db->connect()){
        abort(500);
    }

    $user = new User(db: $db); 
    if($user->findByEmail($_GET['userEmail'])){
        $user->delete();
        header('Location: /admin/members');
    } else {
        abort('member_not_found');
    }

?>