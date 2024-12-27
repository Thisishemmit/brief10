<?php
function set_error($key, $message)
{
    $_SESSION['errors'][$key] = $message;
}

function get_error($key)
{
    $error = $_SESSION['errors'][$key] ?? null;
    unset($_SESSION['errors'][$key]);
    return $error;
}

function has_error($key)
{
    return isset($_SESSION['errors'][$key]);
}

function show_error($message) {
    print_r($message);
}

function abort($code)
{
    if ($code === 404) {
        require 'app/views/errors/404.php';
        exit;
    } else if ($code === 403) {
        require 'app/views/errors/403.php';
        exit;
    } else if ($code === 500) {
        require 'app/views/errors/500.php';
        exit;
    } else if ($code === 'book_not_found') {
        require 'app/views/errors/book_not_found.php';
        exit;
    } else if ($code === 'book_not_found_admin') {
        require 'app/views/errors/book_not_found_admin.php';
        exit;
    }
}
