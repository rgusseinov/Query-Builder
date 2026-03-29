<?php

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

	// $db->execute("UPDATE users SET name = ?", [$name]);

	public function execute(string $sql, array $params = []): int {
		$stmt = $this->prepareAndExecute($sql, $params);

		return $stmt->rowCount();
	}

	public function insert(string $sql, array $params = []): string|int {
		$this->prepareAndExecute($sql, $params);

		return $this->pdo->lastInsertId();
	}

}

$db = new Connection("mysql:host=localhost;dbname=app", "app", "secret");
// $res = $db->fetch("select * from users");

$users = $db->fetchAll("SELECT * FROM users WHERE id > 999");

if (empty($users)) {
    echo "No users";
}

// var_dump($res);