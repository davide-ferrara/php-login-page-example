<?php
declare(strict_types=1);

require_once('../core/bootstrap.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['fusername'];
    $password = $_POST['fpassword'];

    # https://www.php.net/manual/en/function.password-hash.php
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $register_query = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
        $res = $register_query->execute(array('username' => $username, 'password_hash' => $password_hash));

        if ($res) {
            $logger->info($username . " has been registered successfully!");
        } else {
            $logger->error("Error: " . $register_query->errorInfo()[2]);
        }

        $register_query = null;
        $conn = null;

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>