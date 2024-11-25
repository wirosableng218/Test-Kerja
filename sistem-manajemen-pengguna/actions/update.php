<?php
require_once 'crud.php';

if (updateUser(1, 'johnupdated', 'newpassword123')) { // Ganti 1 dengan ID yang diinginkan
    echo "User updated successfully!";
} else {
    echo "Failed to update user.";
}
