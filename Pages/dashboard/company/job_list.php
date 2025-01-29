<?php
session_start();
include 'db_connection.php';

$company_id = $_SESSION['company_id'] ?? null;
if (!$company_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

// Fetch jobs posted by the company, ordered by latest post
$sql = "SELECT job_id, job_title, job_location, job_salary, job_description, job_status, job_created_at 
        FROM Jobs 
        WHERE company_id = ? 
        ORDER BY job_created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $company_id);
$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>



<?php

include("nav/header.php");

?>


<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Jobs <small>List of Job posted</small></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Filter Jobs</h2>
                        <div>
                            <select id="filterStatus" onchange="filterJobs()">
                                <option value="all">All</option>
                                <option value="available">Available</option>
                                <option value="postponed">Postponed</option>
                                <option value="canceled">Canceled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped" id="jobTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Salary ($)</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    <th>view applicant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jobs as $index => $job): ?>
                                    <tr data-status="<?= $job['job_status'] ?>">
                                        <th scope="row"><?= $index + 1 ?></th>
                                        <td><?= htmlspecialchars($job['job_title']) ?></td>
                                        <td><?= htmlspecialchars($job['job_location']) ?></td>
                                        <td><?= number_format($job['job_salary'], 2) ?></td>
                                        <td><?= htmlspecialchars(substr(strip_tags($job['job_description']), 0, 250)) . (strlen($job['job_description']) > 50 ? "..." : "") ?></td>
                                        <td><?= htmlspecialchars($job['job_status']) ?></td>
                                        <td>
                                            <button onclick="editJob(<?= $job['job_id'] ?>)">Edit</button>

                                        </td>
                                        <td>
                                            <a href="view_applicants.php?job_id=<?= $job['job_id'] ?>" class="btn btn-primary">View</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Function to filter jobs by status
    function filterJobs() {
        const filter = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#jobTable tbody tr');
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            row.style.display = (filter === 'all' || filter === status) ? '' : 'none';
        });
    }

    // Function to handle job actions
    function editJob(jobId) {
        if (confirm('Are you sure you want to edit this job?')) {
            window.location.href = `edit_job.php?job_id=${jobId}`;
        }
    }

    function updateJobStatus(jobId, status) {
        const action = status === 'canceled' ? 'cancel' : 'postpone';
        if (confirm(`Are you sure you want to ${action} this job?`)) {
            $.ajax({
                url: 'update_job_status.php',
                type: 'POST',
                data: {
                    job_id: jobId,
                    job_status: status
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function() {
                    alert('Error updating job status.');
                }
            });
        }
    }
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

<?php

include("nav/footer.php");


?>