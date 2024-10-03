<?php
require_once('../vendor/autoload.php');
require_once('../core/bootstrap.php');
require_once('../core/logger.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_COOKIE['jwt'])) {

        $jwt = $_COOKIE['jwt'];
        $secretKey = $_ENV['JWT_SECRET_KEY'];

        try {
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS512'));
            http_response_code(200);
            exit();

        } catch (Exception $e) {
            http_response_code(401);
            exit();  
        }
}
else {
    http_response_code(401);
    exit();
}