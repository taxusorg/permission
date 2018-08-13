<?php
include_once __DIR__.'/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\MySqlConnection;

date_default_timezone_set('PRC');
$dotenv = new Dotenv('./');
$dotenv->load();

$connection = new ConnectionResolver([
    'mysql' => new MySqlConnection(
        function () {
            return new \PDO(
                "mysql:host=" . env('DB_HOST', '127.0.0.1') .
                ";port=" . env('DB_PORT', '3306') .
                ";dbname=" . env('DB_DATABASE', 'forge'),
                env('DB_USERNAME', 'forge'),
                env('DB_PASSWORD', '')
            );
        }
    ),
]);
$connection->setDefaultConnection('mysql');
Model::setConnectionResolver($connection);
