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

// Fetch payment details
$query = "
    SELECT 
        w.worker_username, 
        w.worker_phone_number, 
        w.worker_email, 
        j.job_title, 
        p.payment_amount 
    FROM Payments p
    INNER JOIN Worker w ON p.worker_id = w.worker_id
    INNER JOIN Jobs j ON p.job_id = j.job_id
    WHERE p.company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
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
            <h2>Payment Processed</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>View Payment Processed</h2>
                        <br>
                        <form method="POST" action="print_payment_pdf.php" target="_blank" onsubmit="getUserTime()">
                            <input type="hidden" id="user-time" name="user_time" />
                            <button type="submit">Print Payment Report</button>
                        </form>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Worker Username</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Job Title</th>
                                    <th>Payment Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['worker_username']); ?></td>
                                        <td><?= htmlspecialchars($row['worker_phone_number']); ?></td>
                                        <td><?= htmlspecialchars($row['worker_email']); ?></td>
                                        <td><?= htmlspecialchars($row['job_title']); ?></td>
                                        <td><?= number_format($row['payment_amount'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include("nav/footer.php"); ?>