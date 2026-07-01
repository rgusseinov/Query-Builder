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

// User::find(5);

$users = User::all();
echo "<pre>"; print_r($users);

