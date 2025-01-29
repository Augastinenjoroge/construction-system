<?php
session_start();
include 'db_connection.php'; // Database connection file

// Debug: Print session values
/* echo "<pre>";
print_r($_SESSION);
echo "</pre>"; */

// Retrieve session variables and verify they are set
$user_id = $_SESSION['user_id'] ?? null;
$company_id = $_SESSION['company_id'] ?? null;
$role = $_SESSION['role'] ?? null;

// Ensure role check uses integer comparison
if ($role != 3 || !$user_id || !$company_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/index.php';</script>";
    exit();
}

// Proceed if authorized
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'];
    $job_location = $_POST['job_location'];
    $job_salary = $_POST['job_salary'];
    $job_description = $_POST['job_description'];
    $job_status = $_POST['job_status'];

    $sql = "INSERT INTO Jobs (company_id, job_title, job_description, job_location, job_salary, job_status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('isssds', $company_id, $job_title, $job_description, $job_location, $job_salary, $job_status);

        if ($stmt->execute()) {
            echo "<script>alert('Job posted successfully!'); window.location.href='job_list.php';</script>";
        } else {
            echo "<script>alert('Error posting job: " . $stmt->error . "'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error: Unable to prepare statement'); window.history.back();</script>";
    }
}

$conn->close();
?>
