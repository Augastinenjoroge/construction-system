<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Authenticate the user
if (!isset($_SESSION['admin_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

// Fetch payment data
$query = "SELECT p.payment_id, p.application_id, p.company_id, c.company_name, p.worker_id, w.worker_username, 
                 p.job_id, j.job_title, p.payment_amount, p.payment_status, p.payment_date 
          FROM Payments p
          JOIN Company c ON p.company_id = c.company_id
          JOIN Worker w ON p.worker_id = w.worker_id
          JOIN Jobs j ON p.job_id = j.job_id";
$result = $conn->query($query);
?>

<script type="text/javascript">
// Function to get the user's local time and format it
function getUserTime() {
    var userTime = new Date();
    var options = {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };
    var formattedTime = userTime.toLocaleString('en-US', options);
    document.getElementById('user-time').value = formattedTime;
}
</script>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Payment</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>View Payment</h2>
                        <br>
                        <form method="POST" action="print_payments_pdf.php" target="_blank" onsubmit="getUserTime()">
                            <input type="hidden" id="user-time" name="user_time" />
                            <button type="submit" class="btn btn-primary">Print Payment Report</button>
                        </form>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Payment ID</th>
                                    <th>Application ID</th>
                                    <th>Company ID</th>
                                    <th>Company Name</th>
                                    <th>Worker ID</th>
                                    <th>Worker Username</th>
                                    <th>Job ID</th>
                                    <th>Job Title</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Status</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$count}</td>
                                            <td>{$row['payment_id']}</td>
                                            <td>{$row['application_id']}</td>
                                            <td>{$row['company_id']}</td>
                                            <td>{$row['company_name']}</td>
                                            <td>{$row['worker_id']}</td>
                                            <td>{$row['worker_username']}</td>
                                            <td>{$row['job_id']}</td>
                                            <td>{$row['job_title']}</td>
                                            <td>{$row['payment_amount']}</td>
                                            <td>{$row['payment_status']}</td>
                                            <td>{$row['payment_date']}</td>
                                          </tr>";
                                    $count++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("nav/footer.php"); ?>
