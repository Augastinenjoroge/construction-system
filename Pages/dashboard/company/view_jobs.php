<?php
session_start();
include("nav/header.php");
include("db_connection.php");


// Authenticate the user
if (!isset($_SESSION['company_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 3) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

$company_id = $_SESSION['company_id'];


// Fetch jobs and associated workers
$sql = "SELECT 
            j.job_id, j.job_title, j.job_status, j.job_salary,
            w.worker_username, w.worker_email, w.worker_phone_number,
            a.application_id, a.application_status,
            p.payment_status
        FROM Jobs j
        LEFT JOIN Applications a ON j.job_id = a.job_id
        LEFT JOIN Worker w ON a.worker_id = w.worker_id
        LEFT JOIN Payments p ON j.job_id = p.job_id
        WHERE j.company_id = ? AND j.job_status != 'pending' AND a.application_status = 'approved'";
try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param('i', $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobs = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "<script>alert('An error occurred while fetching data.');</script>";
    $jobs = [];
}
$conn->close();


?>

<!-- Include jQuery and Bootstrap JS at the top of the page -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>



<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Manage Jobs</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>View Job Assigned</h2>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Worker Name</th>
                                    <th>Worker Email</th>
                                    <th>Worker Phone</th>
                                    <th>Job Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jobs as $job): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($job['job_title']) ?></td>
                                        <td><?= htmlspecialchars($job['worker_username'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($job['worker_email'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($job['worker_phone_number'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($job['job_status']) ?></td>
                                        <td>
                                            <?php if ($job['payment_status'] === 'Payment Processed'): ?>
                                                <p class="alert alert-info"><strong>Payment Made</strong></p>
                                            <?php else: ?>

                                                <button class="btn btn-primary btn-sm" onclick="updateStatus(<?= $job['job_id'] ?>)">Update Status</button>
                                                <?php if ($job['job_status'] === 'completed'): ?>
                                                    <button class="btn btn-success btn-sm"
                                                        onclick="openPaymentModal(<?= $job['job_id'] ?>, <?= $job['application_id'] ?>, '<?= htmlspecialchars($job['worker_username'] ?? 'N/A') ?>', '<?= htmlspecialchars($job['job_title']) ?>', <?= $job['job_salary'] ?>)">
                                                        Complete & Pay
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <button class="btn btn-primary" onclick="$('#paymentModal').modal('show')">Test Modal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modal for Payment -->
<div class="modal" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="paymentForm">
                <div class="modal-header">
                    <h4 class="modal-title">Complete Payment</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Job Title:</label>
                        <div class="col-sm-10">
                            <div class="form-line">
                                <input type="text" id="jobTitle" class="form-control" readonly>
                            </div>
                        </div>
                    </div><br>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Worker Name:</label>
                        <div class="col-sm-10">
                            <div class="form-line">
                                <input type="text" id="workerName" class="form-control" readonly>
                            </div>
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Base Salary:</label>
                        <div class="col-sm-10">
                            <div class="form-line">
                                <input type="number" id="baseSalary" class="form-control" readonly>
                            </div>
                        </div>
                    </div><br><br>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Bonus:</label>
                        <div class="col-sm-10">
                            <div class="form-line">
                                <input type="number" id="bonus" class="form-control" value="0">
                            </div>
                        </div>
                    </div><br><br><br>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Total Payment:</label>
                        <div class="col-sm-10">
                            <div class="form-line">
                                <input type="number" id="totalPayment" class="form-control" readonly>
                            </div>
                        </div>
                    </div><br>
                    <!-- <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for Updating Status -->
<div class="modal" id="updateStatusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateStatusForm">
                <div class="modal-header">
                    <h4 class="modal-title">Update Job Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Select Status:</label>
                        <div class="col-sm-10">
                            <div class="form-line">
                                <select id="jobStatus" class="form-control show-tick">
                                    <option value="assigned">Assigned</option>
                                    <option value="completed">Completed</option>
                                    <option value="In progress">In Progress</option>
                                    <option value="pending">Pending</option>
                                    <option value="incomplete">Incomplete</option>
                                </select>
                            </div>
                        </div>
                    </div><br><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function openPaymentModal(jobId, applicationId, workerName, jobTitle, jobSalary) {
        console.log('Job ID:', jobId); // Debugging log
        console.log('Application ID:', applicationId); // Debugging log
        console.log('Worker Name:', workerName); // Debugging log
        console.log('Job Title:', jobTitle); // Debugging log
        console.log('Job Salary:', jobSalary); // Debugging log

        // Set values for the modal fields
        document.getElementById('jobTitle').value = jobTitle; // Set job title in the input field
        document.getElementById('workerName').value = workerName; // Set worker name in the input field
        document.getElementById('baseSalary').value = jobSalary; // Set job salary (base salary) in the input field
        document.getElementById('totalPayment').value = jobSalary; // Set total payment to job salary initially

        // Bonus field logic
        const bonusInput = document.getElementById('bonus');
        bonusInput.value = ''; // Ensure bonus input is blank initially
        bonusInput.addEventListener('input', () => {
            const bonusValue = Number(bonusInput.value) || 0; // If bonus is empty, set it to 0
            document.getElementById('totalPayment').value = jobSalary + bonusValue;
        });

        // Set hidden form data attributes for jobId and applicationId
        document.getElementById('paymentForm').setAttribute('data-job-id', jobId);
        document.getElementById('paymentForm').setAttribute('data-application-id', applicationId);

        // Show the payment modal
        $('#paymentModal').modal('show');
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            job_id: this.getAttribute('data-job-id'),
            application_id: this.getAttribute('data-application-id'),
            worker_name: document.getElementById('workerName').value,
            job_title: document.getElementById('jobTitle').value,
            base_salary: parseFloat(document.getElementById('baseSalary').value),
            bonus: parseFloat(document.getElementById('bonus').value || 0)
        };

        $.ajax({
            url: 'process_payment.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                alert(response.message || 'Payment processed successfully!');
                location.reload();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.error || 'An error occurred while processing the payment.');
            }
        });
    });

    function updateStatus(jobId) {
        // Open the modal
        $('#updateStatusModal').modal('show');

        // Handle form submission
        document.getElementById('updateStatusForm').onsubmit = function(e) {
            e.preventDefault();

            const selectedStatus = document.getElementById('jobStatus').value;

            // Submit the updated status via AJAX
            $.ajax({
                url: 'update_job_status.php',
                method: 'POST',
                data: {
                    job_id: jobId,
                    job_status: selectedStatus
                },
                success: function(response) {
                    if (response.error) {
                        alert('Error: ' + response.error);
                    } else {
                        alert(response.message || 'Job status updated successfully!');
                        location.reload(); // Refresh to show updated status
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });

            // Close the modal
            $('#updateStatusModal').modal('hide');
        };
    }
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
<?php include("nav/footer.php"); ?>