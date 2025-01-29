<?php
session_start();
include("db_connection.php");
include("nav/header.php");

// Authenticate the user
if (!isset($_SESSION['company_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 3) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized access."]);
    exit();
}

$company_id = $_SESSION['company_id'];

// Validate worker_id from the query string
if (!isset($_GET['worker_id']) || !filter_var($_GET['worker_id'], FILTER_VALIDATE_INT)) {
    echo "<script>alert('Invalid Worker ID'); window.location.href='view_applicants.php';</script>";
    exit();
}

$worker_id = intval($_GET['worker_id']);

// Fetch worker profile details
$worker_sql = "
    SELECT 
        w.worker_username, w.worker_email, w.worker_phone_number, 
        w.worker_address, w.worker_description
    FROM Worker w
    WHERE w.worker_id = ?
";
$worker_stmt = $conn->prepare($worker_sql);
$worker_stmt->bind_param('i', $worker_id);
$worker_stmt->execute();
$worker_result = $worker_stmt->get_result();

if ($worker_result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["error" => "Worker not found."]);
    exit();
}

$worker = $worker_result->fetch_assoc();
$worker_stmt->close();

// Fetch worker's jobs and payments
$jobs_sql = "
    SELECT 
        j.job_title, j.job_salary, 
        a.application_status, p.payment_amount, p.payment_status
    FROM Applications a
    JOIN Jobs j ON a.job_id = j.job_id
    LEFT JOIN Payments p ON a.application_id = p.application_id
    WHERE a.worker_id = ? AND j.company_id = ?
";
$jobs_stmt = $conn->prepare($jobs_sql);
$jobs_stmt->bind_param('ii', $worker_id, $company_id);
$jobs_stmt->execute();
$jobs_result = $jobs_stmt->get_result();
$jobs = $jobs_result->fetch_all(MYSQLI_ASSOC);
$jobs_stmt->close();
?>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <!-- worker Profile Display -->
            <div class="col-xs-12 col-sm-3">
                <div class="card profile-card">
                    <div class="profile-header">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area">
                            <div id="profile-image" alt="Profile Image" class="initials-image" style="width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; font-weight: bold; background-color: #007bff; color: white;">
                                <!-- Initials will be set dynamically -->
                            </div>
                        </div>

                        <div class="content-area">
                            <h3><?= $worker['worker_username'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="card card-about-me">
                    <div class="header">
                        <h2>About worker</h2>
                    </div>
                    <div class="body">
                        <ul>
                            <li>
                                <div class="title">
                                    <i class="material-icons">library_books</i>
                                    worker Description
                                </div>
                                <div class="content">
                                    <?= htmlspecialchars(strip_tags($worker['worker_description'])) ?: 'No description provided' ?>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>


            <!-- Profile Update Form -->
            <div class="col-xs-12 col-sm-9">
                <div class="card">
                    <div class="body">
                        <div>
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#profile_settings" data-toggle="tab">Worker Profile</a></li>
                            </ul>

                            <div class="tab-content">
                                <!-- Profile Settings -->
                                <div class="tab-pane fade in active" id="profile_settings">
                                    <form enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">worker Name</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="worker_username" class="form-control uppercase" value="<?= htmlspecialchars($worker['worker_username']) ?>" readonly>
                                                </div><br>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="email" name="worker_email" class="form-control" value="<?= htmlspecialchars($worker['worker_email']) ?>" readonly><br>
                                                </div><br>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="worker_phone" class="form-control" value="<?= $worker['worker_phone_number'] ?>" readonly>
                                                </div><br>
                                            </div>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label class="col-sm-2 control-label">Address</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="worker_address" class="form-control" value="<?= htmlspecialchars($worker['worker_address']) ?: 'Not provided' ?>" readonly>
                                                </div>
                                            </div>
                                        </div> -->
                                    </form>
                                    <br>

                                    <div class="header center">
                                        <h2>Jobs and Payments</h2>
                                    </div>

                                    <div class="body table-responsive">

                                        <?php if (empty($jobs)): ?>
                                            <p>This worker has not completed any jobs yet.</p>
                                        <?php else: ?>
                                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Job Title</th>
                                                        <th>Salary</th>
                                                        <th>Application Status</th>
                                                        <th>Payment Amount</th>
                                                        <th>Payment Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($jobs as $index => $job): ?>
                                                        <tr>
                                                            <td><?= $index + 1 ?></td>
                                                            <td><?= htmlspecialchars($job['job_title']) ?></td>
                                                            <td><?= number_format($job['job_salary'], 2) ?></td>
                                                            <td><?= ucfirst(htmlspecialchars($job['application_status'])) ?></td>
                                                            <td><?= $job['payment_amount'] !== null ? number_format($job['payment_amount'], 2) : 'N/A' ?></td>
                                                            <td><?= htmlspecialchars($job['payment_status']) ?: 'Pending' ?></td>
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
            </div>
        </div>
    </div>
</section>

<script>
    // Worker name from PHP (replace this with actual server-side dynamic data)
    let workerName = "<?= $worker['worker_username'] ?>";

    // Function to generate initials
    function getInitials(name) {
        let nameParts = name.trim().split(" ");
        if (nameParts.length === 1) {
            return nameParts[0][0].toUpperCase(); // Single name
        }
        return nameParts[0][0].toUpperCase() + nameParts[1][0].toUpperCase(); // Two names
    }

    // Set initials to the profile image
    document.getElementById('profile-image').textContent = getInitials(workerName);
</script>

<style>
    .image-area {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .content-area h3 {
        text-transform: uppercase;
        /* Converts text to uppercase */
    }

    .uppercase {
        text-transform: uppercase;
    }
</style>


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


<!-- Jquery Core Js -->
<script src="./plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Core Js -->
<script src="./plugins/bootstrap/js/bootstrap.js"></script>

<!-- Select Plugin Js -->
<script src="./plugins/bootstrap-select/js/bootstrap-select.js"></script>

<!-- Slimscroll Plugin Js -->
<script src="./plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="./plugins/node-waves/waves.js"></script>

<!-- Ckeditor -->
<script src="./plugins/ckeditor/ckeditor.js"></script>

<!-- TinyMCE -->
<script src="./plugins/tinymce/tinymce.js"></script>

<!-- Custom Js -->
<script src="./js/admin.js"></script>
<script src="./js/pages/forms/editors.js"></script>

<!-- Demo Js -->
<script src="./js/demo.js"></script>

<?php

include("nav/footer.php");


?>