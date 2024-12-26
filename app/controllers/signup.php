<?php
    require_once 'app/config/database.php';
    require_once 'app/models/User.php';
    require_once 'app/views/signup.php';

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

    }
?>