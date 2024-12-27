<?php
require_once 'app/models/Category.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $error = '';

    if (empty($name)) {
        $error = 'Category name is required';
        set_error('add_category', $error);
    }

    if (empty($error)) {
        $config = require 'app/config/database.php';
        $db = new Database($config['username'], $config['password'], $config['database']);
        if (!$db->connect()) {
            abort(500);
        }

        $category = new Category($db);
        if ($category->create($name)) {
            header('Location: /admin/categories');
            exit;
        } else {
            $error = 'Failed to add category';
            set_error('add_category', $error);
        }
    }
}

header('Location: /admin/categories');
exit;
