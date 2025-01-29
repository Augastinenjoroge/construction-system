<?php
include("db_connection.php");

$errors = [];
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
    $phone_number = trim($_POST['phone_number']);

    // Input validation
    if (empty($username) || empty($email) || empty($password) || empty($repeat_password)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must be at least 8 characters long, with at least one letter and one number.";
    }

    if ($password !== $repeat_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists in Users or Admin tables
    $stmt = $conn->prepare("SELECT 1 FROM Users WHERE email = ? UNION SELECT 1 FROM Admin WHERE admin_email = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "Email already exists.";
    }
    $stmt->close();

    if (empty($errors)) {
        // Start a transaction
        $conn->begin_transaction();

        try {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert into Users table
            $role_id = 1; // Admin role
            $stmt = $conn->prepare("INSERT INTO Users (email, password_hash, role_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $email, $password_hash, $role_id);
            $stmt->execute();
            $user_id = $stmt->insert_id; // Get the last inserted user ID
            $stmt->close();

            // Insert into Admin table
            $stmt = $conn->prepare("INSERT INTO Admin (user_id, admin_username, admin_email, admin_phone_number) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $username, $email, $phone_number);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();
            $success_message = "Admin registered successfully!";
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $errors[] = "An error occurred while processing your request.";
        }
    }

    // Return JSON response
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode('<br>', $errors)]);
    } else {
        echo json_encode(['status' => 'success', 'message' => $success_message]);
    }
}
?>
