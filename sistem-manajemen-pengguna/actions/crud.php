<?php
require_once 'db.php';

// **CREATE User**
function createUser($username, $password)
{
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
}

// **READ All Users**
function getAllUsers()
{
    global $pdo;
    $sql = "SELECT id, username, created_at FROM users";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// **READ Single User by ID**
function getUserById($id)
{
    global $pdo;
    $sql = "SELECT id, username, created_at FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// **UPDATE User**
function updateUser($id, $username, $password)
{
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET username = :username, password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id, 'username' => $username, 'password' => $hashedPassword]);
}

// **DELETE User**
function deleteUser($id)
{
    global $pdo;
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}
