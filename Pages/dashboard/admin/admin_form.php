<?php
include("nav/header.php");
include("db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
    $phone_number = trim($_POST['phone_number']);

    // Error messages
    $errors = [];

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
            header("Location: admin_form.php?status=success");
            exit;

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $errors[] = "An error occurred while processing your request.";
        }
    }
}
// Check for the success message in the URL (after redirection)
$status_message = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $status_message = "Admin registered successfully!";
}
?>


<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Admin Registration</h2>
        </div>
        <!-- Input -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <h2 class="card-inside-title">
                            Admin Form
                        </h2>
                        <div class="row clearfix">
                            <form method="POST" action="admin_form.php">
                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <?php foreach ($errors as $error): ?>
                                            <p><?php echo htmlspecialchars($error); ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($status_message)): ?>
                                    <div class="alert alert-success">
                                        <p><?php echo htmlspecialchars($status_message); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" value="<?php echo htmlspecialchars($_POST['phone_number'] ?? ''); ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" name="password" class="form-control" placeholder="Password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="password" name="repeat_password" class="form-control" placeholder="Repeat Password" />
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Register Admin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Input -->
    </div>
</section>



<?php include("nav/footer.php"); ?>