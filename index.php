<?php

require_once 'Env.php';
require_once 'Connection.php';

Env::load(__DIR__ . '/.env');

$db = new Connection(
    "mysql:host=" . Env::get('DB_HOST') .
    ";dbname=" . Env::get('DB_NAME'),
    Env::get('DB_USER'),
    Env::get('DB_PASS')
);

$result = $db->table('users')
    ->where('id', '=', 3)
    ->update([
        'full_name' => 'Ruslan Gusseinov.'
    ]);

print_r($result);