<?php

require_once 'Env.php';
require_once './src/Core/Container.php';
require_once './src/Database/Connection.php';
require_once './src/Database/Model.php';
require_once './src/Model/Post.php';
require_once './src/Model/User.php';
require_once './src/Model/Company.php';
require_once './src/Model/Employee.php';

$container = new Container();

Env::load(__DIR__ . '/.env');

$container->bind(Connection::class, function () {
    // здесь создать Connection с Env

    return new Connection(
        "mysql:host=" . Env::get('DB_HOST') .
        ";dbname=" . Env::get('DB_NAME'),
        Env::get('DB_USER'),
        Env::get('DB_PASS')
    );

});

$container->bind(QueryBuilder::class, function ($container) {
    // здесь создать QueryBuilder и передать Connection
    return new QueryBuilder($container->get(Connection::class));
});

Model::setContainer($container);
// $users = User::all();

/* $result = User::find(5);
$result->full_name = 'Rus v3';
$result->save(); */

/* $rand = rand(10, 100);
$result = new User();
$result->full_name = 'New user ' . $rand;
$result->email = $rand . '_glk@mail.ru';
$result->password_hash = '444';

$result->save();
print_r($result); */


/* $result = Post::find(10);
$user = $result->user();
echo '<pre>'; print_r($user); */

$queryBuilder = $container->get('queryBuilder');

/* $r = $queryBuilder
    ->table('posts')
    ->whereIn('user_id', [1, 2, 3])
    // ->where('id', '=', '5')
    ->get(); */


// $users = User::with('posts')->get();
// echo '<pre>'; print_r($users);


/* $users = User::with('posts')->get();

foreach ($users as $user) {
    echo $user->full_name . PHP_EOL;

    foreach ($user->posts as $post) {
        echo " - {$post['title']}" . PHP_EOL;
    }
} */

//

/* $result = new Company;
$result->name = 'Coca-Cola';
$result->save(); */

/* $result = new Employee;
$result->full_name = 'Conor';
$result->position = 'Manager';
$result->salary = 500000;
$result->company_id = 1;

$result->save(); */

/* $result = Employee::find(1);
$user = $result->company();
echo '<pre>'; print_r($user); */


/* $result = Company::find(1);
$data = $result->employees();
echo '<pre>'; print_r($data); */