<?php
session_start();

include("db_connection.php");

include("nav/header.php");

// Authenticate the user
if (!isset($_SESSION['company_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 3) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

$company_id = $_SESSION['company_id'];

// Validate job_id
if (!isset($_GET['job_id']) || !filter_var($_GET['job_id'], FILTER_VALIDATE_INT)) {
    echo "<script>alert('Invalid Job ID'); window.location.href='job_list.php';</script>";
    exit();
}

$job_id = intval($_GET['job_id']);

// Verify the job belongs to the company
$job_check_sql = "SELECT job_title FROM Jobs WHERE job_id = ? AND company_id = ?";
$job_check_stmt = $conn->prepare($job_check_sql);
$job_check_stmt->bind_param('ii', $job_id, $company_id);
$job_check_stmt->execute();
$job_result = $job_check_stmt->get_result();

if ($job_result->num_rows === 0) {
    echo "<script>alert('Unauthorized access to this job'); window.location.href='job_list.php';</script>";
    exit();
}

$job = $job_result->fetch_assoc();
$job_check_stmt->close();

// Fetch applicants for the job
$applicants_sql = "
    SELECT 
        w.worker_id, w.worker_username, w.worker_email, w.worker_phone_number, 
        a.application_date, a.application_status
    FROM Applications a
    JOIN Worker w ON a.worker_id = w.worker_id
    WHERE a.job_id = ?
    ORDER BY a.application_date DESC
";
$applicants_stmt = $conn->prepare($applicants_sql);
$applicants_stmt->bind_param('i', $job_id);
$applicants_stmt->execute();
$applicants_result = $applicants_stmt->get_result();
$applicants = $applicants_result->fetch_all(MYSQLI_ASSOC);
$applicants_stmt->close();

$conn->close();
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Applicants for <?= htmlspecialchars($job['job_title']) ?></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Applicants</h2>
                    </div>
                    <div class="body">
                    <div class="table-responsive">
                        <?php if (empty($applicants)): ?>
                            <p>No applications received for this job yet.</p>
                        <?php else: ?>
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Application Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applicants as $index => $applicant): ?>
                                        <tr
                                            class="clickable-row"
                                            data-href="worker_profile.php?worker_id=<?= $applicant['worker_id'] ?>"
                                            style="cursor: pointer;">
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($applicant['worker_username']) ?></td>
                                            <td><?= htmlspecialchars($applicant['worker_email']) ?></td>
                                            <td><?= htmlspecialchars($applicant['worker_phone_number']) ?></td>
                                            <td><?= date('F j, Y, \a\t g:i a', strtotime($applicant['application_date'])) ?></td>
                                            <td><?= htmlspecialchars($applicant['application_status']) ?></td>
                                            <td>
                                                <?php if ($applicant['application_status'] === 'pending'): ?>
                                                    <button onclick="updateApplicationStatus(<?= $job_id ?>, <?= $applicant['worker_id'] ?>, 'approved')">Approve</button>
                                                    <button onclick="updateApplicationStatus(<?= $job_id ?>, <?= $applicant['worker_id'] ?>, 'rejected')">Reject</button>
                                                <?php else: ?>
                                                    <span><?= ucfirst($applicant['application_status']) ?></span>
                                                <?php endif; ?>
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
    </div>
</section>

<script>
    function updateApplicationStatus(jobId, workerId, status) {
        if (confirm(`Are you sure you want to ${status} this application?`)) {
            fetch('update_application.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        job_id: jobId,
                        worker_id: workerId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
        }
    }

    // Make rows clickable except buttons
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', (e) => {
                // Exclude button clicks from triggering the row click
                if (!e.target.closest('button')) {
                    window.location.href = row.dataset.href;
                }
            });
        });
    });
</script>


<!-- Jquery DataTable Plugin Js -->
<script src="./plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="./plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>



<?php include("nav/footer.php"); ?>