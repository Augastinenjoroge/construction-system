<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'], $_POST['job_status'])) {
    $job_id = $_POST['job_id'];
    $job_status = $_POST['job_status'];
    $company_id = $_SESSION['company_id'] ?? null;

    if (!$company_id) {
        echo json_encode(['message' => 'Unauthorized access']);
        exit();
    }

    $sql = "UPDATE Jobs SET job_status = ? WHERE job_id = ? AND company_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sii', $job_status, $job_id, $company_id);
        if ($stmt->execute()) {
            echo json_encode(['message' => "Job status updated to $job_status successfully."]);
        } else {
            echo json_encode(['message' => 'Error updating job status.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['message' => 'Database error: Unable to prepare statement.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request']);
}
$conn->close();
?>
