<?php

require_once 'app/helpers/auth.php';

$routes = [
    'admin' => [
        'admin/dashboard' => [
            'controller' => 'app/controllers/admin/dashboard.php',
            'title' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'roles' => ['admin']
        ],
        'admin/members' => [
            'controller' => 'app/controllers/admin/members.php',
            'title' => 'Users',
            'icon' => 'fa fa-users',
            'roles' => ['admin']
        ],
        'admin/delete-user' => [
            'controller' => 'app/controllers/admin/members/delete.php',
            'roles' => ['admin']
        ],
        'admin/books' => [
            'controller' => 'app/controllers/admin/books.php',
            'title' => 'Books',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        'admin/books/add' => [
            'controller' => 'app/controllers/admin/books/add.php',
            'roles' => ['admin']
        ],
        'admin/books/edit' => [
            'controller' => 'app/controllers/admin/books/edit.php',
            'roles' => ['admin']
        ],
        'admin/books/delete' => [
            'controller' => 'app/controllers/admin/books/delete.php',
            'roles' => ['admin']
        ],
        'admin/borrows' => [
            'controller' => 'app/controllers/admin/borrows.php',
            'title' => 'Borrows',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        'admin/borrows/confirm-return' => [
            'controller' => 'app/controllers/admin/borrows/confirm-return.php',
            'roles' => ['admin']
        ],
        'admin/borrows/confirm-borrow' => [
            'controller' => 'app/controllers/admin/borrows/confirm-borrow.php',
            'roles' => ['admin']
        ],
        'admin/reservations' => [
            'controller' => 'app/controllers/admin/reservations.php',
            'title' => 'Reservations',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
    ],

    'member' => [
        'member/dashboard' => [
            'controller' => 'app/controllers/member/dashboard.php',
            'title' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'roles' => ['member']
        ],
        'member/books' => [
            'controller' => 'app/controllers/member/books.php',
            'title' => 'Books',
            'icon' => 'fa fa-book',
            'roles' => ['member']
        ],
        'member/books/reserve' => [
            'controller' => 'app/controllers/member/books/reserve.php',
            'roles' => ['member']
        ],
        'member/books/cancel-reservation' => [
            'controller' => 'app/controllers/member/books/cancel-reservation.php',
            'roles' => ['member']
        ],
        'member/borrows' => [
            'controller' => 'app/controllers/member/borrows.php',
            'title' => 'Borrows',
            'icon' => 'fa fa-book',
            'roles' => ['member']
        ],
        'member/borrows/return' => [
            'controller' => 'app/controllers/member/borrows/return.php',
            'roles' => ['member']
        ],
    ],

    'all' => [
        'logout' => [
            'controller' => 'app/controllers/logout.php',
            'roles' => ['admin', 'member']
        ],
    ]
];


$public_routes = [
    'login'=> [
        'controller' => 'app/controllers/login.php'
    ],
    'signup'=>[
        'controller' => 'app/controllers/signup.php'
    ]
];

$PATH = $_SERVER['REQUEST_URI'];
$PATH = parse_url($PATH)['path'];

if (array_key_exists($PATH, $public_routes)) {
    $route = $public_routes[$PATH];
    if (!is_logged_in()){
        require $route['controller'];
    } 
}

if (!is_logged_in()){
    require 'app/controllers/member/books.php';
}

$routes = array_merge($routes['all'], $routes['admin'], $routes['member']);


if (array_key_exists($PATH, $routes)) {
    $route = $routes[$PATH];

    $roles = $route['roles'];
    $user_role = get_logged_user_role();
    if (is_allowed($roles, $user_role)) {
        require $route['controller'];
    } else {
        abort(403);
    }
} else {
    abort(404);
}
