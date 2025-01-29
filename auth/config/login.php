<?php
session_start();
include 'config.php'; // Database connection file

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the Users table
    $stmt = $conn->prepare("SELECT user_id, password_hash, role_id FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password_hash, $role_id);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $password_hash)) {
            // Initialize session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role_id;

            // Determine if the user is an Admin, Worker, or Company
            if ($role_id == '1') { // Admin role
                $roleStmt = $conn->prepare("SELECT admin_id FROM Admin WHERE user_id = ?");
                $roleStmt->bind_param("i", $user_id);
                $roleStmt->execute();
                $roleStmt->bind_result($admin_id);
                $roleStmt->fetch();

                $_SESSION['admin_id'] = $admin_id;
                $_SESSION['role_type'] = 'admin';

                header("Location:  ../../Pages/dashboard/admin/");
            } elseif ($role_id == '2') { // Worker role
                $roleStmt = $conn->prepare("SELECT worker_id FROM Worker WHERE user_id = ?");
                $roleStmt->bind_param("i", $user_id);
                $roleStmt->execute();
                $roleStmt->bind_result($worker_id);
                $roleStmt->fetch();

                $_SESSION['worker_id'] = $worker_id;
                $_SESSION['role_type'] = 'worker';

                header("Location: ../../Pages/dashboard/worker/");
            } elseif ($role_id == '3') { // Company role
                $roleStmt = $conn->prepare("SELECT company_id FROM Company WHERE user_id = ?");
                $roleStmt->bind_param("i", $user_id);
                $roleStmt->execute();
                $roleStmt->bind_result($company_id);
                $roleStmt->fetch();

                $_SESSION['company_id'] = $company_id;
                $_SESSION['role_type'] = 'company';

                header("Location: ../../Pages/dashboard/company/");
            } else {
                // Redirect to login with error
                $_SESSION['error'] = "Role not found.";
                header("Location: ../index.php");
            }
        } else {
            // Password does not match
            $_SESSION['error'] = "Incorrect email or password.";
            header("Location: ../index.php");
        }
    } else {
        // User does not exist
        $_SESSION['error'] = "Incorrect email or password.";
        header("Location: ../index.php");
    }

    $stmt->close();
} else {
    header("Location: ../index.php");
}
?>
