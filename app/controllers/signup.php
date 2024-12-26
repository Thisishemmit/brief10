<?php
    require_once 'app/models/User.php';
    require_once 'app/helpers/errors.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])){
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $error = '';
        
        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)){
            $error = 'Please fill all inputs';
            set_error('signup', $error);
        } 
        
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
            set_error('signup', $error);
        } 
        
        if ($_POST["confirm_password"] !== $_POST["password"]) {
            $error = "Passwords do not match.";
            set_error('signup', $error);
        } 
        
        if (empty($error)){
            $config = require 'app/config/database.php';
            $db = new Database($config['username'], $config['password'], $config['database']);
            if (!$db->connect()){
                abort(102);
            }
            
            $user = new User($db);
            if ($user->findByEmail($email)){
                $error = 'Email already exist';
                set_error('signup', $error);
            } else {
                $user->create($name, $email, $password, 'member');
            }
        }
    }
    require_once 'app/views/signup.php';
    ?>