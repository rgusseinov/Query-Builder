<?php

require_once 'Env.php';
require_once './src/Core/Container.php';
require_once './src/Connection.php';
require_once './src/Model.php';
require_once './src/User.php';

$container = new Container();

Env::load(__DIR__ . '/.env');

$container->singleton(Connection::class, function () {
    // здесь создать Connection с Env

    return new Connection(
        "mysql:host=" . Env::get('DB_HOST') .
        ";dbname=" . Env::get('DB_NAME'),
        Env::get('DB_USER'),
        Env::get('DB_PASS')
    );

});

$container->singleton(QueryBuilder::class, function ($container) {
    // здесь создать QueryBuilder и передать Connection
    return new QueryBuilder($container->get(Connection::class));
});

Model::setContainer($container);
// $users = User::all();

/* $result = User::find(5);
$result->full_name = 'Rus v3';
$result->save(); */

$rand = rand(10, 100);
$result = new User();
$result->full_name = 'New user ' . $rand;
$result->email = $rand . '_glk@mail.ru';
$result->password_hash = '444';

$result->save();
print_r($result);
