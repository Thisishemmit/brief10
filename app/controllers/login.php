<?php
require_once 'app/models/User.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';
require_once 'app/helpers/Database.php';

skip_if_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill all inputs';
        set_error('login', $error);
    }

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
        set_error('login', $error);
    }

    if (empty($error)) {
        $config = require 'app/config/database.php';
        $db = new Database($config['username'], $config['password'], $config['database']);
        $db->connect();

        $user = new User($db);
        if ($user->findByEmail($email)) {
            if ($user->verifyPassword($password)) {
                login_user($user->get());
                header('Location: /');
                exit;
            } else {
                $error = 'Invalid credentials';
                set_error('login', $error);
            }
        } else {
            $error = 'User not found';
            set_error('login', $error);
        }
    }
}

require 'app/views/login.php';