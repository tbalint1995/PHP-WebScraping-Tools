<?php

// Ellenőrzés, hogy az alkalmazás a helyes kontextusban van-e.
defined('IN_WS') or call_user_func(function () {
    http_response_code(403);
    exit;
});

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'tesztfeladatok_kardi-soft');
define("DB_USER", "root");
define('DB_PASSWORD', '');
