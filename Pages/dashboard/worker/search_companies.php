<?php
include("db_connection.php");

if (isset($_GET['query'])) {
    $search = $_GET['query'];

    $stmt = $conn->prepare("
        SELECT company_id, company_name 
        FROM Company 
        WHERE company_name LIKE ?
        LIMIT 10
    ");
    $searchParam = '%' . $search . '%';
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $companies = [];
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($companies);
}
?>
