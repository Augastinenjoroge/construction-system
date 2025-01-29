<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['job_id'], $data['worker_id'], $data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit();
}

$job_id = intval($data['job_id']);
$worker_id = intval($data['worker_id']);
$status = $data['status'];

if (!in_array($status, ['approved', 'rejected'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status.']);
    exit();
}

// Update application status
$sql = "UPDATE Applications SET application_status = ? WHERE job_id = ? AND worker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $status, $job_id, $worker_id);
$success = $stmt->execute();
$stmt->close();

if ($success && $status === 'approved') {
    // Update job status to assigned if approved
    $update_job_sql = "UPDATE Jobs SET job_status = 'assigned' WHERE job_id = ?";
    $update_job_stmt = $conn->prepare($update_job_sql);
    $update_job_stmt->bind_param('i', $job_id);
    $update_job_stmt->execute();
    $update_job_stmt->close();
}

echo json_encode(['success' => $success, 'message' => $success ? 'Status updated successfully.' : 'Failed to update status.']);
$conn->close();
?>
