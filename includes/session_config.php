<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 3600, // 1 hour session
    'path' => '/',
    'domain' => 'localhost', 
    'secure' => false,
    'httponly' => true,
]);

session_start();

function session_regeneration() {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}

if (!isset($_SESSION['last_regen'])) {
    session_regeneration();
} else {
    $interval = 60 * 30; //30 mintue
    if (time() - $_SESSION['last_regen'] >= $interval) {
        session_regeneration();
    }
}