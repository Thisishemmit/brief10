<?php

function is_logged_in()
{
    return isset($_SESSION['user']);
}

function skip_if_logged_in()
{
    if (is_logged_in()) {
        header('Location: /');
        exit();
    }
}

function require_auth()
{
    if (!is_logged_in()) {
        header('Location: /login');
        exit;
    }
}

function logout_user()
{
    unset($_SESSION['user']);
    session_destroy();
}

function login_user($user)
{
    $_SESSION['user'] = $user;
}

function get_logged_user_role()
{
    return $_SESSION['user']['role'];
}

function is_admin()
{
    return get_logged_user_role() === 'admin';
}

function is_member()
{
    return get_logged_user_role() === 'member';
}

function is_allowed($roles, $user_role)
{
    return in_array($user_role, $roles);
}
