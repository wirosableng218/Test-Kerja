<?php
require_once 'crud.php';

if (createUser('johndoe', 'password123')) {
    echo "User created successfully!";
} else {
    echo "Failed to create user.";
}
