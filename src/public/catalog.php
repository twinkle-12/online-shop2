<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login_form.php');
    exit();
} else {
    $pdo = new PDO('pgsql:host=db;port=5432;dbname=mydb', 'user', 'pwd');
    $stmt = $pdo->query("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll();
}

require_once './catalog_page.php';


