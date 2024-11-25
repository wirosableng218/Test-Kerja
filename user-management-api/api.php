<?php
header("Content-Type: application/json");
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Helper function for response
function sendResponse($status, $message, $data = null)
{
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

// Handle API
switch ($method) {
    case 'POST': // Create
        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input['name'], $input['email'], $input['phone'])) {
            sendResponse('error', 'Missing required fields');
        }

        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_STRING);

        if (!$email) {
            sendResponse('error', 'Invalid email format');
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $phone]);
            sendResponse('success', 'User created successfully', ['id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            sendResponse('error', 'Error: ' . $e->getMessage());
        }
        break;

    case 'GET': // Read
        $id = $_GET['id'] ?? null;
        try {
            if ($id) {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    sendResponse('success', 'User retrieved successfully', $user);
                } else {
                    sendResponse('error', 'User not found');
                }
            } else {
                $stmt = $pdo->query("SELECT * FROM users");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                sendResponse('success', 'Users retrieved successfully', $users);
            }
        } catch (PDOException $e) {
            sendResponse('error', 'Error: ' . $e->getMessage());
        }
        break;

    case 'PUT': // Update
        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input['id'], $input['name'], $input['email'], $input['phone'])) {
            sendResponse('error', 'Missing required fields');
        }

        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_VALIDATE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_STRING);

        if (!$id || !$email) {
            sendResponse('error', 'Invalid input');
        }

        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $id]);
            sendResponse('success', 'User updated successfully');
        } catch (PDOException $e) {
            sendResponse('error', 'Error: ' . $e->getMessage());
        }
        break;

    case 'DELETE': // Delete
        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input['id'])) {
            sendResponse('error', 'Missing required field: id');
        }

        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if (!$id) {
            sendResponse('error', 'Invalid ID');
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            sendResponse('success', 'User deleted successfully');
        } catch (PDOException $e) {
            sendResponse('error', 'Error: ' . $e->getMessage());
        }
        break;

    default:
        sendResponse('error', 'Invalid request method');
        break;
}
