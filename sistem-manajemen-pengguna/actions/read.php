<?php
require_once 'crud.php';

$users = getAllUsers();
foreach ($users as $user) {
    echo "ID: {$user['id']}, Username: {$user['username']}, Created At: {$user['created_at']}<br>";
}
