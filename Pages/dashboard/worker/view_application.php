<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Retrieve session variables
$user_id = $_SESSION['user_id'] ?? null;
$worker_id = $_SESSION['worker_id'] ?? null;
$role = $_SESSION['role'] ?? null;

// Role check
if ($role != 2 || !$user_id || !$worker_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/index.php';</script>";
    exit();
}

// Handle search and pagination
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10; // Number of rows per page
$offset = ($page - 1) * $limit;

try {
    // Count total applications for completed jobs
    $completedCountQuery = "
        SELECT COUNT(*) as total
        FROM Applications a
        LEFT JOIN Jobs j ON a.job_id = j.job_id
        WHERE a.worker_id = ? AND j.job_status = 'completed' AND j.job_title LIKE ?";
    $completedCountStmt = $conn->prepare($completedCountQuery);
    $searchParam = "%$search%";
    $completedCountStmt->bind_param("is", $worker_id, $searchParam);
    $completedCountStmt->execute();
    $completedTotal = $completedCountStmt->get_result()->fetch_assoc()['total'];
    
    // Fetch completed jobs with pagination
    $completedQuery = "
        SELECT 
            a.application_id, j.job_title, j.job_salary, j.job_status, a.application_status, 
            p.payment_amount, p.payment_status, j.job_id
        FROM Applications a
        LEFT JOIN Jobs j ON a.job_id = j.job_id
        LEFT JOIN Payments p ON a.application_id = p.application_id
        WHERE a.worker_id = ? AND j.job_status = 'completed' AND j.job_title LIKE ?
        LIMIT ? OFFSET ?";
    $completedStmt = $conn->prepare($completedQuery);
    $completedStmt->bind_param("isii", $worker_id, $searchParam, $limit, $offset);
    $completedStmt->execute();
    $completedJobs = $completedStmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Count total applications for other jobs
    $otherCountQuery = "
        SELECT COUNT(*) as total
        FROM Applications a
        LEFT JOIN Jobs j ON a.job_id = j.job_id
        WHERE a.worker_id = ? AND j.job_status != 'completed' AND j.job_title LIKE ?";
    $otherCountStmt = $conn->prepare($otherCountQuery);
    $otherCountStmt->bind_param("is", $worker_id, $searchParam);
    $otherCountStmt->execute();
    $otherTotal = $otherCountStmt->get_result()->fetch_assoc()['total'];
    
    // Fetch other jobs with pagination
    $otherQuery = "
        SELECT 
            a.application_id, j.job_title, j.job_salary, j.job_status, a.application_status, 
            j.job_id
        FROM Applications a
        LEFT JOIN Jobs j ON a.job_id = j.job_id
        WHERE a.worker_id = ? AND j.job_status != 'completed' AND j.job_title LIKE ?
        LIMIT ? OFFSET ?";
    $otherStmt = $conn->prepare($otherQuery);
    $otherStmt->bind_param("isii", $worker_id, $searchParam, $limit, $offset);
    $otherStmt->execute();
    $otherJobs = $otherStmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "<script>alert('An error occurred while fetching applications. Please try again later.');</script>";
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>My Applications</h2>
        </div>

        <!-- Search Bar -->
        <form method="GET" class="form-inline mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search by Job Title" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Completed Jobs Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Completed Jobs</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <?php if (empty($completedJobs)): ?>
                                <p>No completed jobs found.</p>
                            <?php else: ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Job Title</th>
                                            <th>Salary</th>
                                            <th>Job Status</th>
                                            <th>Application Status</th>
                                            <th>Payment Amount</th>
                                            <th>Payment Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($completedJobs as $index => $job): ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <td><?= htmlspecialchars($job['job_title']) ?></td>
                                                <td><?= number_format($job['job_salary'], 2) ?></td>
                                                <td><?= htmlspecialchars($job['job_status']) ?></td>
                                                <td><?= htmlspecialchars($job['application_status']) ?></td>
                                                <td><?= number_format($job['payment_amount'], 2) ?></td>
                                                <td><?= htmlspecialchars($job['payment_status']) ?></td>
                                                <td>
                                                    <a href="job_detail.php?job_id=<?= $job['job_id'] ?>" class="btn btn-primary btn-sm">View Job</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Jobs Table -->
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Other Jobs</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <?php if (empty($otherJobs)): ?>
                                <p>No other jobs found.</p>
                            <?php else: ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Job Title</th>
                                            <th>Salary</th>
                                            <th>Job Status</th>
                                            <th>Application Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($otherJobs as $index => $job): ?>
                                            <tr>
                                                <td><?= $offset + $index + 1 ?></td>
                                                <td><?= htmlspecialchars($job['job_title']) ?></td>
                                                <td><?= number_format($job['job_salary'], 2) ?></td>
                                                <td><?= htmlspecialchars($job['job_status']) ?></td>
                                                <td><?= htmlspecialchars($job['application_status']) ?></td>
                                                <td>
                                                    <a href="job_detail.php?job_id=<?= $job['job_id'] ?>" class="btn btn-primary btn-sm">View Job</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <?php
        $totalPagesCompleted = ceil($completedTotal / $limit);
        $totalPagesOther = ceil($otherTotal / $limit);
        ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= max($totalPagesCompleted, $totalPagesOther); $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</section>

<?php include("nav/footer.php"); ?>
