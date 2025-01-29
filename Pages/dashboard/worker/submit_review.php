<?php
session_start();
include("db_connection.php");


// Check if user is a worker and logged in
if ($_SESSION['role'] != 2) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$worker_id = $_SESSION['worker_id'] ?? null;
$company_id = $_POST['company_id'] ?? null;
$review_text = $_POST['review_text'] ?? '';
$rating = $_POST['rating'] ?? 1;

if (!$worker_id) {
    echo json_encode(['error' => 'Worker ID is missing.']);
    exit();
}
if (!$company_id) {
    echo json_encode(['error' => 'Company ID is missing.']);
    exit();
}
if (!$review_text) {
    echo json_encode(['error' => 'Review text is empty.']);
    exit();
}
if (!in_array($rating, [1, 2, 3, 4, 5])) {
    echo json_encode(['error' => 'Invalid rating.']);
    exit();
}

// Validate input
if (!$worker_id || !$company_id || !in_array($rating, [1, 2, 3, 4, 5])) {
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

// Insert review into database
$query = "INSERT INTO Reviews (worker_id, company_id, review_text, rating) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iisi", $worker_id, $company_id, $review_text, $rating);
$stmt->execute();

echo json_encode(['success' => 'Review submitted']);
?>
