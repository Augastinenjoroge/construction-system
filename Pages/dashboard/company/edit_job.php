<?php
session_start();
include 'db_connection.php';

$company_id = $_SESSION['company_id'] ?? null;
if (!$company_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

// Check if job_id is provided in the URL
if (!isset($_GET['job_id'])) {
    echo "<script>alert('Job ID is missing.'); window.location.href='job_list.php';</script>";
    exit();
}

$job_id = $_GET['job_id'];
$error = "";

// Fetch job details
$sql = "SELECT job_title, job_location, job_salary, job_description, job_status FROM Jobs WHERE job_id = ? AND company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();
$stmt->close();

if (!$job) {
    echo "<script>alert('Job not found.'); window.location.href='job_list.php';</script>";
    exit();
}

// Update job details upon form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = $_POST['job_title'] ?? '';
    $job_location = $_POST['job_location'] ?? '';
    $job_salary = $_POST['job_salary'] ?? 0.00;
    $job_description = $_POST['job_description'] ?? '';
    $job_status = $_POST['job_status'] ?? 'available';

    $sql = "UPDATE Jobs SET job_title = ?, job_location = ?, job_salary = ?, job_description = ?, job_status = ? WHERE job_id = ? AND company_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssdssii', $job_title, $job_location, $job_salary, $job_description, $job_status, $job_id, $company_id);
        if ($stmt->execute()) {
            echo "<script>alert('Job updated successfully.'); window.location.href='job_list.php';</script>";
            exit();
        } else {
            $error = "Error updating job: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Error preparing the statement.";
    }
}
$conn->close();
?>


<?php

include("nav/header.php");

?>



<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Edit Job</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Update Job Information</h2>
                    </div>
                    <div class="body">
                        <?php if ($error): ?>
                            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="col-md-3">
                                <b>Job Title</b>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">computer</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="job_title" id="job_title" value="<?= htmlspecialchars($job['job_title']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <b>Job Location</b>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">location_on</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="text" name="job_location" class="form-control" id="job_location" value="<?= htmlspecialchars($job['job_location']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <b>Money (Dollar)</b>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">attach_money</i>
                                    </span>
                                    <div class="form-line">
                                        <input type="number" step="0.01" class="form-control" name="job_salary" id="job_salary" value="<?= htmlspecialchars($job['job_salary']) ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>
                                                Job description
                                                <small>Enter Job description in details </small>
                                            </h2>
                                        </div>
                                        <div class="body">
                                            <textarea class="form-control" name="job_description" id="ckeditor" required><?= htmlspecialchars($job['job_description']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label for="job_status">Job Status</label>
                            <select name="job_status" id="job_status">
                                <option value="available" <?= $job['job_status'] === 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="postponed" <?= $job['job_status'] === 'postponed' ? 'selected' : '' ?>>Postponed</option>
                                <option value="canceled" <?= $job['job_status'] === 'canceled' ? 'selected' : '' ?>>Canceled</option>
                            </select>

                            <button type="submit" class="btn btn-primary">Update Job</button>
                            <a href="job_list.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




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