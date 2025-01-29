<?php
// Include database connection
include 'db_connection.php';
session_start();

// Fetch job data from the database
$company_id = $_SESSION['company_id']; // Make sure the company is logged in

// Get the current page and page length for DataTable pagination
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$length = isset($_GET['length']) ? intval($_GET['length']) : 10;
$search_value = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';

// SQL query to fetch job data
$sql = "SELECT job_id, job_title, job_status FROM Jobs WHERE company_id = ?";

// If there is a search term, add it to the SQL query
if ($search_value != '') {
    $sql .= " AND (job_title LIKE ? OR job_status LIKE ?)";
}

$stmt = $conn->prepare($sql);
if ($search_value != '') {
    $search_term = '%' . $search_value . '%';
    $stmt->bind_param('iss', $company_id, $search_term, $search_term);
} else {
    $stmt->bind_param('i', $company_id);
}

$stmt->execute();
$result = $stmt->get_result();

// Get the total number of rows for pagination
$total_count_sql = "SELECT COUNT(*) as total FROM Jobs WHERE company_id = ?";
$total_count_stmt = $conn->prepare($total_count_sql);
$total_count_stmt->bind_param('i', $company_id);
$total_count_stmt->execute();
$total_count_result = $total_count_stmt->get_result();
$total_count = $total_count_result->fetch_assoc()['total'];

// Prepare the data for the DataTable
$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = [
        'job_id' => $row['job_id'],
        'job_title' => $row['job_title'],
        'job_status' => $row['job_status']
    ];
}

// Return the data in JSON format for DataTable
echo json_encode([
    'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
    'recordsTotal' => $total_count,
    'recordsFiltered' => $total_count,
    'data' => $jobs
]);

$stmt->close();
$total_count_stmt->close();
?>
