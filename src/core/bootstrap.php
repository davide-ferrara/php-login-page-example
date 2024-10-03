<?php
declare(strict_types=1);

require '../vendor/autoload.php';

require_once('Database.php');

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->load();

$host = $_ENV['MYSQL_HOST'];
$name = $_ENV['MYSQL_NAME'];
$user = $_ENV['MYSQL_USER'];
$pass = $_ENV['MYSQL_ROOT_PASSWORD'];

$database = new Database($host, $name, $user, $pass);
$conn = $database->getConnection();

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('my_logger');

$logger->pushHandler(new StreamHandler('php://stdout'));

/*
$sql = 'SELECT now()';
foreach ($conn->query($sql) as $row) {
    print 'Connected to db at: '  . $row['now()'] . "\t";
}
*/