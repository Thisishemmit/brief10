<?php

require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

$routes = [
    'admin' => [
        '/admin/dashboard' => [
            'controller' => 'app/controllers/admin/dashboard.php',
            'title' => 'Dashboard',
            'icon' => 'fa fa-dashboard',
            'roles' => ['admin']
        ],
        '/admin/categories' => [
            'controller' => 'app/controllers/admin/categories.php',
            'title' => 'Categories',
            'icon' => 'fa fa-tags',
            'roles' => ['admin']
        ],
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
        '/admin/members' => [
            'controller' => 'app/controllers/admin/members.php',
            'title' => 'Users',
            'icon' => 'fa fa-users',
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
        '/admin/books' => [
            'controller' => 'app/controllers/admin/books.php',
            'title' => 'Books',
            'icon' => 'fa fa-book',
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
        '/admin/borrowings' => [
            'controller' => 'app/controllers/admin/borrowings.php',
            'title' => 'Borrowings',
            'icon' => 'fa fa-book',
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
        '/admin/returns' => [
            'controller' => 'app/controllers/admin/returns.php',
            'title' => 'Returns',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/returns/approve' => [
            'controller' => 'app/controllers/admin/returns/approve.php',
            'roles' => ['admin']
        ],
        '/admin/returns/reject' => [
            'controller' => 'app/controllers/admin/returns/reject.php',
            'roles' => ['admin']
        ],
        '/admin/reservations' => [
            'controller' => 'app/controllers/admin/reservations.php',
            'title' => 'Reservations',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/reservations/delete' => [
            'controller' => 'app/controllers/admin/reservations/delete.php',
            'roles' => ['admin']
        ],
    ],

    'member' => [
        '/member/books/reserve' => [
            'controller' => 'app/controllers/member/books/reserve.php',
            'roles' => ['member']
        ],
        '/member/books/cancel-reservation' => [
            'controller' => 'app/controllers/member/books/cancel-reservation.php',
            'roles' => ['member']
        ],
        '/member/borrows' => [
            'controller' => 'app/controllers/member/borrows.php',
            'title' => 'Borrows',
            'icon' => 'fa fa-book',
            'roles' => ['member']
        ],
        '/member/borrows/return' => [
            'controller' => 'app/controllers/member/borrows/return.php',
            'roles' => ['member']
        ],
    ],

    'all' => []
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
    '/logout' => [
        'controller' => 'app/controllers/logout.php'
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
