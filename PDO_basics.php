<?php

$pdo = new PDO('mysql:host=localhost;dbname=app', 'app', 'secret', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);


// $stmt = $pdo->query("SELECT id, name FROM users");
// $row = $stmt->fetch();
// print_r($row);

/* $ID = 2;

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch();
print_r($row); */



/* $name = 'Jane';

$stmt = $pdo->prepare("INSERT INTO users(name) VALUES (:name)");
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->execute();

print_r($pdo->lastInsertId());
 */

$stmt = $pdo->query("SELECT * FROM users");
// $stmt->execute();

$row = $stmt->fetchAll();

print_r($row);
 

/* try {
	$stmt = $pdo->prepare("SELECT * FROM users_not_exist");
	$stmt->execute();
	$row = $stmt->fetchAll();
	print_r($row);

} catch (PDOException $e) {
	print_r($e->getMessage());
}
 */

