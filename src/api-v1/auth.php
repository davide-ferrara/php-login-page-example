<?php
declare(strict_types=1);

require_once('../vendor/autoload.php');
require_once('../core/bootstrap.php');

use Firebase\JWT\JWT;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['fusername']) || !isset($_POST['fpassword'])) {
            $logger->error("Missing username or password");
            http_response_code(400);
            exit();
        }
        
        $username = $_POST['fusername'];
        $password = $_POST['fpassword'];

        $login_stmt = $conn->prepare("SELECT username, password_hash FROM users WHERE username = :username");
        $login_stmt->bindParam(":username", $username);
        $login_stmt->execute();

        $res = $login_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$res || !(password_verify($password, $res['password_hash'])) ) {
            $logger->error($username . " inserted incorrect credential");
            http_response_code(401);
            exit();
        }

        $logger->info($username . ' logged in, now setting cookies...');

        $secretKey  = $_ENV['JWT_SECRET_KEY'];
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+60 minutes')->getTimestamp();
        $serverName = "localhost";

        $data = [
            'iat'  => $issuedAt->getTimestamp(),
            'iss'  => $serverName,
            'nbf'  => $issuedAt->getTimestamp(),
            'exp'  => $expire,
            'userName' => $username,
        ];

        $jwt = JWT::encode($data, $secretKey, 'HS512');
        
        $cookie = setcookie(
            "JWT",
            $jwt,
            $expire,
            "/",
            "localhost",
            true,
            true,
        );
        
        if(!$cookie){
            $logger->error('Could not set cookie for user: ' . $username);
            http_response_code(500);
        }

        $logger->info($username . ' JWT cookie stored successfully!');

        http_response_code(200);
        exit();

    } catch (PDOException $e) {
        $logger->error($e);
        http_response_code(500);
        exit();
    }
}

?>