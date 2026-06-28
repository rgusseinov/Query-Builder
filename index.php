<?php

require_once 'Env.php';
require_once './src/Connection.php';
require_once './src/Model.php';

Model::setContainer(new Container());

Env::load(__DIR__ . '/.env');

$db = new Connection(
    "mysql:host=" . Env::get('DB_HOST') .
    ";dbname=" . Env::get('DB_NAME'),
    Env::get('DB_USER'),
    Env::get('DB_PASS')
);

$result = $db->table('users')
    ->where('id', '=', 3)
    ->get();

print_r($result);