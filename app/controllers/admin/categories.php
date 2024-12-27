<?php
require_once 'app/models/Category.php';
require_once 'app/helpers/Database.php';
require_once 'app/helpers/auth.php';
require_once 'app/helpers/errors.php';

require_auth();

$config = require 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);
if (!$db->connect()) {
    abort(500);
}

$category = new Category($db);
$categories = $category->getAllCategories();

require 'app/views/admin/categories.php';
