<?php
require_once 'app/models/Category.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category'])) {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name']);
    $error = '';

    if (empty($id) || empty($name)) {
        $error = 'Category ID and name are required';
        set_error('edit_category', $error);
    }

    if (empty($error)) {
        $config = require 'app/config/database.php';
        $db = new Database($config['username'], $config['password'], $config['database']);
        if (!$db->connect()) {
            abort(500);
        }

        $category = new Category($db);
        if ($category->findById($id) && $category->update($name)) {
            header('Location: /admin/categories');
            exit;
        } else {
            $error = 'Failed to update category';
            set_error('edit_category', $error);
        }
    }
}

header('Location: /admin/categories');
exit;
