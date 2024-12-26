<?php
function show_error($message) {
    print_r($message);
}

function abort($code = 404) {
    require "app/views/errors/{$code}.php";
    exit;
}
