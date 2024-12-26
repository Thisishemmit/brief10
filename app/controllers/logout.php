<?php
require_once 'app/helpers/auth.php';

if (is_logged_in()) {
    logout_user();
}
header('Location: /');
exit;
