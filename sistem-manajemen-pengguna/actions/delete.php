<?php
require_once 'crud.php';

if (deleteUser(1)) { // Ganti 1 dengan ID yang diinginkan
    echo "User deleted successfully!";
} else {
    echo "Failed to delete user.";
}
