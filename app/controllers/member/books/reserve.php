<?php
require_once 'app/helpers/Database.php';
require_once 'app/helpers/errors.php';
require_once 'app/models/Book.php';

$config = include 'app/config/database.php';
$db = new Database($config['username'], $config['password'], $config['database']);

if (!$db->connect()) {
    abort(500);
}

if (isset($_GET['id']) && isset($_GET['days'])) {
    $id = $_GET['id'];
    $id_user = $_SESSION['user']['id_user'];
    $days = $_GET['days'];
    if ($days !== "5" && $days !== "10" && $days !== "15") {
        $error = 'Invalid days value: got' . $days;
        set_error("member_req_bor", $error);
        header("Location: /");
        exit;
    }
    $date = new DateTime();
    $date->modify("+{$days} days");

    $book = new Book($db);
    if ($book->findById($id)) {
        if ($book->getStatus() !== 'available') {
            if (!$book->reserve($id_user, $date->format("Y-m-d H:i:s"))) {
                $error = "unknown error happened during reservation";
                set_error("member_req_bor", $error);
                header("Location: /");
                exit;
            } else {
                header("Location: /");
                exit;
            }
        } else {
            $error = "Book is available, You have to request a borrow";
            set_error("member_req_bor", $error);
            header("Location: /");
            exit;
        }
    } else {
        $error = "Book Not Found";
        set_error("member_req_bor", $error);
        header("Location: /");
        exit;
    }
} else {
    abort(404);
}

