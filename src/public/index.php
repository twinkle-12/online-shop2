<?php

$pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'user', 'pwd');

$pdo->exec("INSERT INTO users (name, email, password) VALUES ('Ivan', 'ivan@mail.ru', 'qwerty123')");
$statement = $pdo->query('SELECT * FROM users');
$data = $statement->fetchAll();
print_r($data);