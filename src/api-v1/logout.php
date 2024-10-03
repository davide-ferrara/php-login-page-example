<?php
require_once('../vendor/autoload.php');
require_once('../core/bootstrap.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_COOKIE[$_ENV['JWT_COOKIE_NAME']])) {
        http_response_code(401);
        exit();
    }
    
    $secretKey  = $_ENV['JWT_SECRET_KEY'];
    $jwt = $_COOKIE[$_ENV['JWT_COOKIE_NAME']];

    try {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS512'));
        
        $cookie = setcookie("JWT", "", time() - 3600, "/", "localhost", true, true);
        
        if(!$cookie) {
            http_response_code(500);
        }

        $looger->info("User logged out!");
        
    } catch (Exception $e) {
        http_response_code(401);
        exit();
    }
}
?>