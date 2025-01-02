<?php

return [
    'admin' => [
        '/admin/books' => [
            'controller' => 'app/controllers/admin/books.php',
            'title' => 'Books',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/categories' => [
            'controller' => 'app/controllers/admin/categories.php',
            'title' => 'Categories',
            'icon' => 'fa fa-tags',
            'roles' => ['admin']
        ],
        '/admin/members' => [
            'controller' => 'app/controllers/admin/members.php',
            'title' => 'Users',
            'icon' => 'fa fa-users',
            'roles' => ['admin']
        ],
        '/admin/books' => [
            'controller' => 'app/controllers/admin/books.php',
            'title' => 'Books',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/borrowings' => [
            'controller' => 'app/controllers/admin/borrowings.php',
            'title' => 'Borrowings',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/returnings' => [
            'controller' => 'app/controllers/admin/returnings.php',
            'title' => 'Returns',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/reservations' => [
            'controller' => 'app/controllers/admin/reservations.php',
            'title' => 'Reservations',
            'icon' => 'fa fa-book',
            'roles' => ['admin']
        ],
        '/admin/statistics' => [
            'controller' => 'app/controllers/admin/statistics.php',
            'title' => 'Statistics',
            'icon' => 'fa fa-bar-chart',
            'roles' => ['admin']
        ],
    ],
    'member' => [
        '/books' => [
            'controller' => 'app/controllers/books.php',
            'title' => 'Books',
            'icon' => 'fa fa-book',
            'roles' => ['member']
        ],
        '/borrows' => [
            'controller' => 'app/controllers/borrows.php',
            'title' => 'My Borrows',
            'icon' => 'fa fa-book',
            'roles' => ['member']
        ],
        '/reservations' => [
            'controller' => 'app/controllers/reservations.php',
            'title' => 'My Reservations',
            'icon' => 'fa fa-bookmark',
            'roles' => ['member']
        ],
    ],
];
