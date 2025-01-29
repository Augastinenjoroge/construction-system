<?php
session_start();
include("db_connection.php");

// Only allow access if user is authorized (role == 3)
$user_id = $_SESSION['user_id'] ?? null;
$company_id = $_SESSION['company_id'] ?? null;
$role = $_SESSION['role'] ?? null;

if ($role != 3 || !$user_id || !$company_id) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

// Retrieve form data
$company_name = $_POST['company_name'] ?? '';
$company_email = $_POST['company_email'] ?? '';
$company_phone_number = $_POST['company_phone_number'] ?? '';
$company_address = $_POST['company_address'] ?? '';
$company_description = $_POST['company_description'] ?? '';

// Handle profile image upload with timestamp
$upload_dir = 'images/profiles';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$profile_image = $_FILES['company_profile']['name'] ?? '';
if ($profile_image) {
    $timestamp = time();
    $target_file = $upload_dir . '/' . $timestamp . '_' . basename($profile_image);
    move_uploaded_file($_FILES['company_profile']['tmp_name'], $target_file);
} else {
    $target_file = 'images/construction.jpeg';
}

// Update the company details in the database
$sql = "UPDATE Company SET company_name=?, company_email=?, company_phone_number=?, company_address=?, company_description=?, company_profile=? WHERE company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $company_name, $company_email, $company_phone_number, $company_address, $company_description, $target_file, $company_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode([
        'company_name' => $company_name,
        'company_email' => $company_email,
        'company_phone_number' => $company_phone_number,
        'company_address' => $company_address,
        'company_description' => $company_description,
        'company_profile' => $target_file
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update company details']);
}
