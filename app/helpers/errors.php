<?php
function set_error($key, $message)
{
    $_SESSION["error_{$key}"] = $message;
}

function get_error($key)
{
    if (has_error($key)) {
        $error = $_SESSION["error_{$key}"];
        unset($_SESSION["error_{$key}"]);
        return $error;
    }
    return null;
}

function has_error($key)
{
    return isset($_SESSION["error_{$key}"]);
}

function show_error($message) {
    print_r($message);
}

function abort($code = 404) {
    require "app/views/errors/{$code}.php";
    exit;
}
