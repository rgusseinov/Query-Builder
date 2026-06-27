<?php

require_once 'QueryBuilder.php';


class Connection
{
	private PDO $pdo;

	public function __construct(string $dsn, string $user, string $password) {
		$this->pdo = new PDO($dsn, $user, $password, [
    	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);

		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
	}

	private function prepareAndExecute(string $sql, array $params): PDOStatement {
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		
		return $stmt;
	}

	public function table(string $table): QueryBuilder {
		return (new QueryBuilder($this))->table($table);
	}


	public function fetch(string $sql, array $params = []): ?array {
		$stmt = $this->prepareAndExecute($sql, $params);
		$result = $stmt->fetch();

		return $result === false ? null : $result;
	}

	public function fetchAll(string $sql, array $params = []): array {
		$stmt = $this->prepareAndExecute($sql, $params);
		$result = $stmt->fetchAll();

		return $result;
	}

	public function execute(string $sql, array $params = []): int {
		$stmt = $this->prepareAndExecute($sql, $params);

		return $stmt->rowCount();
	}

	public function insert(string $sql, array $params = []): string|int {
		$this->prepareAndExecute($sql, $params);

		return $this->pdo->lastInsertId();
	}

}

// $db = new Connection("mysql:host=127.0.0.1;dbname=crm_test", "app", "secret");
// $res = $db->fetch("select * from users");

/* $users = $db->fetchAll("SELECT * FROM users WHERE id > 999");

if (empty($users)) {
    echo "No users";
} */

// var_dump($res);

$db = new Connection("mysql:host=127.0.0.1;dbname=crm_test", "app", "secret");

/* $result = $db->table('users')
              ->select(['email', 'full_name'])
							->where('id', '=', 1)
							->get(); */

/* $result = $db->table('users')
            ->where('id', '=', '1')
            ->first(); */


// $result = $db->table('users')->insert([]);

// $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
// params = ['Ruslan', 'abc@mail.ru', 1];

$result = $db->table('users')
						->where('password_hash', '=', '777')
						->delete();

print_r($result);


// $db->fetchAll("SELECT id, name FROM users WHERE id = ?", [1]);

/*   $db->table('users')
    ->select(['id', 'name'])
    ->where('id', '=', 1);
 */

/* function a(){

	return ['sql' => 'select * from users', 'params' => [1, 2]];
}

['sql' => $sql, 'params' => $params] = a();

print_r($sql); */