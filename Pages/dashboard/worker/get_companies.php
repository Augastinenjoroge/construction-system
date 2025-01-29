<?php
include("db_connection.php");

// Get search query from request
$search = $_GET['search'] ?? '';

// Prepare query to search companies by name
$query = "SELECT company_id, company_name FROM Company WHERE company_name LIKE ?";
$stmt = $conn->prepare($query);
$searchTerm = "%$search%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Prepare response
$companies = [];
while ($row = $result->fetch_assoc()) {
    $companies[] = $row;
}

// Return companies as JSON
header('Content-Type: application/json');
echo json_encode($companies);
?>
