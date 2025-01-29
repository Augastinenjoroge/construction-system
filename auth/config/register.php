<?php
session_start(); // Start the session

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "roomTWO2";
$dbname = "construction_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Initialize success variable
$success = false;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = sanitizeInput($_POST['role']);
    $form_type = sanitizeInput($_POST['form_type']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $phone = sanitizeInput($_POST['phone']);
    $username = $role === 'worker' ? sanitizeInput($_POST['username']) : sanitizeInput($_POST['company_name']);
    $company_name = $role === 'company' ? sanitizeInput($_POST['company_name']) : '';
    $company_address = sanitizeInput($_POST['address']); // Get company address from the form

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email_stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();

    if ($check_email_stmt->num_rows > 0) {
        $_SESSION['error'] = 'Email already registered! Please use a different email.';
        $_SESSION['form_data'] = $_POST;
        $_SESSION['form_type'] = $form_type;
        header("Location: ../index.php");
        exit();
    } else {
        if ($role == 'company') {
            // Check if company name already exists
            $check_company_stmt = $conn->prepare("SELECT company_id FROM Company WHERE company_name = ?");
            $check_company_stmt->bind_param("s", $company_name);
            $check_company_stmt->execute();
            $check_company_stmt->store_result();

            if ($check_company_stmt->num_rows > 0) {
                $_SESSION['error'] = 'Company name already registered! Please use a different name.';
                $_SESSION['form_data'] = $_POST;
                $_SESSION['form_type'] = $form_type;
                header("Location: ../index.php");
                exit();
            }

            $check_company_stmt->close();
        }

        // Continue with registration only if there are no conflicts
        $role_id = ($role === 'worker') ? 2 : 3;

        // Prepare SQL statement for Users
        $stmt = $conn->prepare("INSERT INTO Users (email, password_hash, role_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $email, $password_hash, $role_id);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            if ($role == 'worker') {
                $worker_stmt = $conn->prepare("INSERT INTO Worker (user_id, worker_username, worker_email, worker_phone_number) VALUES (?, ?, ?, ?)");
                $worker_stmt->bind_param("isss", $user_id, $username, $email, $phone);

                if ($worker_stmt->execute()) {
                    $success = true;
                } else {
                    $_SESSION['error'] = 'Error registering worker: ' . $worker_stmt->error;
                }
                $worker_stmt->close();
            } elseif ($role == 'company') {
                // Log that we reached the company insertion block
                error_log("Attempting to insert into Company table.");

                // Prepare the Company insertion statement
                $company_stmt = $conn->prepare("INSERT INTO Company (user_id, company_name, company_email, company_phone_number, company_address) VALUES (?, ?, ?, ?, ?)");
                $company_stmt->bind_param("issss", $user_id, $company_name, $email, $phone, $company_address);

                // Execute and check for errors
                if ($company_stmt->execute()) {
                    $success = true;
                    error_log("Company inserted successfully.");
                } else {
                    error_log("Error registering company: " . $company_stmt->error);
                    $_SESSION['error'] = 'Error registering company: ' . $company_stmt->error;
                }
                $company_stmt->close();
            }
        } else {
            $_SESSION['error'] = 'Error registering user: ' . $stmt->error;
            error_log("Error registering user: " . $stmt->error);
        }

        $stmt->close();
    }
    $check_email_stmt->close();
}

$conn->close();

// If registration is successful
if ($success) {
    header("Location: ../index.php?success=1");
    exit();
} else {
    header("Location: ../index.php?error=1");
    exit();
}
