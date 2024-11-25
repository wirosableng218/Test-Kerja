<?php
require_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->execute(['username' => $username, 'password' => $password]);
        echo "User registered successfully. <a href='../views/login.php'>Login here</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
