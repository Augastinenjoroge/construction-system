<?php
session_start();
include("db_connection.php");

// Validate session
$user_id = $_SESSION['user_id'] ?? null;
$worker_id = $_SESSION['worker_id'] ?? null;
$role = $_SESSION['role'] ?? null;

if ($role != 2 || !$user_id || !$worker_id) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Retrieve and sanitize form data
$worker_username = trim($_POST['worker_username'] ?? '');
$worker_email = filter_var(trim($_POST['worker_email'] ?? ''), FILTER_SANITIZE_EMAIL);
$worker_phone_number = trim($_POST['worker_phone_number'] ?? '');
$worker_address = trim($_POST['worker_address'] ?? '');
$worker_description = trim($_POST['worker_description'] ?? '');

if (!filter_var($worker_email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address']);
    exit();
}

// Update worker details
$sql = "UPDATE Worker SET worker_username = ?, worker_email = ?, worker_phone_number = ?, 
        worker_address = ?, worker_description = ? WHERE worker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $worker_username, $worker_email, $worker_phone_number, $worker_address, $worker_description, $worker_id);

if ($stmt->execute()) {
    echo json_encode([
        'worker_username' => $worker_username,
        'worker_email' => $worker_email,
        'worker_phone_number' => $worker_phone_number,
        'worker_address' => $worker_address,
        'worker_description' => $worker_description,
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => $stmt->error]);
}

$stmt->close();
$conn->close();
