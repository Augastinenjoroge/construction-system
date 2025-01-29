<?php
session_start();
header("Content-Type: application/json");

// Include database connection
include("db_connection.php");

// Check if the user is authenticated
if (!isset($_SESSION['worker_id']) || $_SESSION['role_type'] !== 'worker' || $_SESSION['role'] != 2) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

// Check CSRF token
$headers = getallheaders();
if (!isset($headers['X-CSRF-TOKEN']) || $headers['X-CSRF-TOKEN'] !== $_SESSION['csrf_token']) {
    echo json_encode(["success" => false, "message" => "Invalid CSRF token."]);
    exit();
}

// Parse JSON input
$input = json_decode(file_get_contents("php://input"), true);
if (!isset($input['job_id']) || !filter_var($input['job_id'], FILTER_VALIDATE_INT)) {
    echo json_encode(["success" => false, "message" => "Invalid job ID."]);
    exit();
}

$job_id = intval($input['job_id']);
$worker_id = intval($_SESSION['worker_id']);

// Check if the worker has already applied for the job
$check_sql = "SELECT application_status FROM Applications WHERE worker_id = ? AND job_id = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("ii", $worker_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "You have already applied for this job."]);
    exit();
}

// Insert new application
$insert_sql = "INSERT INTO Applications (worker_id, job_id, application_status) VALUES (?, ?, 'pending')";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("ii", $worker_id, $job_id);

if ($insert_stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Application submitted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to submit your application. Please try again later."]);
}

$stmt->close();
$insert_stmt->close();
$conn->close();
?>
