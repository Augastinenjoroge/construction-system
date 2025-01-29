<?php
session_start();
include("db_connection.php");

// Retrieve session variables
$user_id = $_SESSION['user_id'] ?? null;
$worker_id = $_SESSION['worker_id'] ?? null;
$role = $_SESSION['role'] ?? null;

// Role check
if ($role != 2 || !$user_id || !$worker_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/index.php';</script>";
    exit();
}

// Fetch all reviews along with company and worker details
$query = "
    SELECT 
    r.review_id, 
    r.review_text, 
    r.rating, 
    r.review_date, 
    c.company_name, 
    w.worker_username 
FROM 
    Reviews r
JOIN 
    Company c ON r.company_id = c.company_id
JOIN 
    Worker w ON r.worker_id = w.worker_id
ORDER BY 
    r.review_date DESC;
";

$result = $conn->query($query);

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Return reviews as JSON
header('Content-Type: application/json');
echo json_encode($reviews);
