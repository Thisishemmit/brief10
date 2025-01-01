<?php

require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

$routes = include 'app/config/sidebar.php';
$action_routes = [
    'admin' => [
        '/admin/categories/add' => [
            'controller' => 'app/controllers/admin/categories/add.php',
            'roles' => ['admin']
        ],
        '/admin/categories/edit' => [
            'controller' => 'app/controllers/admin/categories/edit.php',
            'roles' => ['admin']
        ],
        '/admin/categories/delete' => [
            'controller' => 'app/controllers/admin/categories/delete.php',
            'roles' => ['admin']
        ],
        '/admin/members/delete' => [
            'controller' => 'app/controllers/admin/members/delete.php',
            'roles' => ['admin']
        ],
        '/admin/members/edit' => [
            'controller' => 'app/controllers/admin/members/edit.php',
            'roles' => ['admin']
        ],
        '/admin/books/add' => [
            'controller' => 'app/controllers/admin/books/add.php',
            'roles' => ['admin']
        ],
        '/admin/books/edit' => [
            'controller' => 'app/controllers/admin/books/edit.php',
            'roles' => ['admin']
        ],
        '/admin/books/delete' => [
            'controller' => 'app/controllers/admin/books/delete.php',
            'roles' => ['admin']
        ],
        '/admin/borrowings/approve' => [
            'controller' => 'app/controllers/admin/borrowings/approve.php',
            'roles' => ['admin']
        ],
        '/admin/borrowings/reject' => [
            'controller' => 'app/controllers/admin/borrowings/reject.php',
            'roles' => ['admin']
        ],
        '/admin/returnings/approve' => [
            'controller' => 'app/controllers/admin/returnings/approve.php',
            'roles' => ['admin']
        ],
        '/admin/returnings/reject' => [
            'controller' => 'app/controllers/admin/returnings/reject.php',
            'roles' => ['admin']
        ],
        '/admin/reservations/delete' => [
            'controller' => 'app/controllers/admin/reservations/delete.php',
            'roles' => ['admin']
        ],
    ],

    'member' => [
        '/books/reserve' => [
            'controller' => 'app/controllers/member/books/reserve.php',
            'roles' => ['member']
        ],
        '/books/borrow' => [
            'controller' => 'app/controllers/member/books/borrow.php',
            'roles' => ['member']
        ],
        '/books/cancel-reservation' => [
            'controller' => 'app/controllers/member/books/cancel-reservation.php',
            'roles' => ['member']
        ],
        '/borrows/return' => [
            'controller' => 'app/controllers/member/borrows/return.php',
            'roles' => ['member']
        ],
    ],

];

$auth_routes = [
    '/login' => [
        'controller' => 'app/controllers/login.php'
    ],
    '/signup' => [
        'controller' => 'app/controllers/signup.php'
    ]
];

$public_routes = [
    '/' => [
        'controller' => 'app/controllers/books.php'
    ],
    '/books' => [
        'controller' => 'app/controllers/books.php'
    ],
    '/logout' => [
        'controller' => 'app/controllers/logout.php'
    ],
    '/search' => [
        'controller' => 'app/controllers/search.php'
    ]
];

$PATH = $_SERVER['REQUEST_URI'];
$PATH = parse_url($PATH)['path'];

if (array_key_exists($PATH, $auth_routes)) {
    if (is_logged_in()) {
        header('Location: /');
        exit;
    }
    $route = $auth_routes[$PATH];
    require $route['controller'];
    exit;
}

if (array_key_exists($PATH, $public_routes)) {
    $route = $public_routes[$PATH];
    require $route['controller'];
    exit;
}

require_auth();

$routes = array_merge($routes['admin'], $routes['member'], $action_routes['admin'], $action_routes['member']);

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
