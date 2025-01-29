<?php
// Start session and include database connection
session_start();
include("nav/header.php");
include("db_connection.php");

// Check if the user is authenticated and has the worker role
if (!isset($_SESSION['worker_id']) || $_SESSION['role_type'] !== 'worker' || $_SESSION['role'] != 2) {
    header("Location: ../../../auth/"); // Redirect unauthorized users to the login page
    exit();
}

// Generate CSRF token if not already created
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Sanitize and validate job_id from the URL
if (!isset($_GET['job_id']) || !filter_var($_GET['job_id'], FILTER_VALIDATE_INT)) {
    die("Invalid job ID.");
}

$job_id = intval($_GET['job_id']);
$worker_id = intval($_SESSION['worker_id']);

// Fetch job details along with company info
$sql = "SELECT 
            j.job_title, 
            j.job_description, 
            j.job_location, 
            j.job_salary, 
            j.job_status, 
            j.job_created_at, 
            c.company_name, 
            c.company_profile, 
            c.company_description
        FROM Jobs j
        JOIN Company c ON j.company_id = c.company_id
        WHERE j.job_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc();

// Check if the worker has already applied for the job
$application_sql = "SELECT application_status FROM Applications WHERE worker_id = ? AND job_id = ?";
$app_stmt = $conn->prepare($application_sql);
$app_stmt->bind_param("ii", $worker_id, $job_id);
$app_stmt->execute();
$app_result = $app_stmt->get_result();
$already_applied = $app_result->num_rows > 0;

if ($already_applied) {
    $application = $app_result->fetch_assoc();
    $application_status = $application['application_status'];
}

$stmt->close();
$app_stmt->close();
$conn->close();
?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Job Details</h2>
                    </div>
                    <div class="body">
                        <h1><?= htmlspecialchars($job['job_title']) ?></h1>
                        <div class="company-details">
                            <img src="../company/<?= htmlspecialchars($job['company_profile']) ?>" alt="<?= htmlspecialchars($job['company_name']) ?>" class="company-logo">
                            <h3><?= htmlspecialchars($job['company_name']) ?></h3>
                        </div>
                        <div class="job-details">
                            <p><strong>Location:</strong> <?= htmlspecialchars($job['job_location']) ?></p>
                            <p><strong>Salary:</strong> $<?= number_format($job['job_salary'], 2) ?></p>
                            <p><strong>Description:</strong></p>
                            <p><?= nl2br(htmlspecialchars_decode($job['job_description'])) ?></p>
                            <p><strong>Posted on:</strong> <?= date('F j, Y', strtotime($job['job_created_at'])) ?></p>
                        </div>
                        <?php if ($already_applied): ?>
                            <p class="alert alert-info">You have already applied for this job. Your application status: <strong><?= htmlspecialchars($application_status) ?></strong>.</p>
                        <?php else: ?>
                            <form id="application-form" class="application-form">
                                <input type="hidden" id="csrf-token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="button" id="apply-button" class="btn btn-primary">Apply for this Job</button>
                            </form>
                            <p id="response-message" style="display: none;" class="alert"></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById("apply-button").addEventListener("click", function() {
        const csrfToken = document.getElementById("csrf-token").value;

        fetch("process_application.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    job_id: <?= $job_id ?>
                })
            })
            .then(response => response.json())
            .then(data => {
                const messageElement = document.getElementById("response-message");
                messageElement.style.display = "block";
                if (data.success) {
                    messageElement.className = "alert alert-success";
                    messageElement.innerText = data.message;
                    document.getElementById("apply-button").disabled = true;
                } else {
                    messageElement.className = "alert alert-danger";
                    messageElement.innerText = data.message;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An unexpected error occurred. Please try again later.");
            });
    });
</script>

<?php include("nav/footer.php"); ?>