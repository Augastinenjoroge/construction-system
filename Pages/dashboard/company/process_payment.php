<?php
session_start();
include("db_connection.php");

// Authenticate the user
if (!isset($_SESSION['company_id'], $_SESSION['role']) || $_SESSION['role'] != 3) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Input validation
        $jobId = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);
        $applicationId = filter_input(INPUT_POST, 'application_id', FILTER_VALIDATE_INT);
        $companyId = $_SESSION['company_id']; // Assuming company_id is stored in session
        $workerName = filter_input(INPUT_POST, 'worker_name', FILTER_SANITIZE_STRING);
        $jobTitle = filter_input(INPUT_POST, 'job_title', FILTER_SANITIZE_STRING);
        $baseSalary = filter_input(INPUT_POST, 'base_salary', FILTER_VALIDATE_FLOAT);
        $bonus = filter_input(INPUT_POST, 'bonus', FILTER_VALIDATE_FLOAT);

        // Check required inputs
        if (!$jobId || !$applicationId || !$companyId || !$baseSalary || $baseSalary < 0) {
            throw new Exception("Invalid input provided.");
        }

        $totalPayment = $baseSalary + ($bonus ?? 0);

        // Prepare SQL statement to insert payment
        $stmt = $conn->prepare("
            INSERT INTO Payments (worker_id, job_id, company_id, application_id, payment_amount) 
            SELECT w.worker_id, j.job_id, ?, ?, ? 
            FROM Worker w 
            JOIN Applications a ON w.worker_id = a.worker_id 
            JOIN Jobs j ON a.job_id = j.job_id 
            WHERE j.job_id = ? AND a.application_id = ? AND j.company_id = ?
        ");
        $stmt->bind_param('iidiii', $companyId, $applicationId, $totalPayment, $jobId, $applicationId, $companyId);

        // Execute query
        if ($stmt->execute()) {
            // Log successful payment
            file_put_contents('logs/payment.log', "[" . date('Y-m-d H:i:s') . "] Payment successful: Job ID $jobId, Application ID $applicationId, Amount $totalPayment\n", FILE_APPEND);

            echo json_encode(['message' => 'Payment processed successfully']);
        } else {
            throw new Exception("Error processing payment. Please try again.");
        }
    } catch (Exception $e) {
        // Log error
        file_put_contents('logs/error.log', "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . "\n", FILE_APPEND);

        // Send error response
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        $stmt->close();
        $conn->close();
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
?>

