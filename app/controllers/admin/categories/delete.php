<?php
require_once 'app/models/Category.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $id = $_POST['id'] ?? null;
    $error = '';

    if (empty($id)) {
        $error = 'Category ID is required';
        set_error('delete_category', $error);
    }

    if (empty($error)) {
        $config = require 'app/config/database.php';
        $db = new Database($config['username'], $config['password'], $config['database']);
        if (!$db->connect()) {
            abort(500);
        }

        $category = new Category($db);
        if ($category->findById($id)) {
            // Check if category has books
            $books = $category->getBooks();
            if (!empty($books)) {
                $error = 'Cannot delete category that has books';
                set_error('delete_category', $error);
            } else if ($category->delete()) {
                header('Location: /admin/categories');
                exit;
            } else {
                $error = 'Failed to delete category';
                set_error('delete_category', $error);
            }
        } else {
            $error = 'Category not found';
            set_error('delete_category', $error);
        }
    }
}

header('Location: /admin/categories');
exit;
