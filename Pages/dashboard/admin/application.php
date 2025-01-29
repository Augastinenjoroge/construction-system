<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Authenticate the user
if (!isset($_SESSION['admin_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

// Fetch application data
$query = "
    SELECT 
        Applications.application_id,
        Applications.application_status,
        Applications.application_date,
        Worker.worker_id,
        Worker.worker_username,
        Jobs.job_id,
        Jobs.job_title,
        Company.company_id,
        Company.company_name
    FROM Applications
    INNER JOIN Worker ON Applications.worker_id = Worker.worker_id
    INNER JOIN Jobs ON Applications.job_id = Jobs.job_id
    INNER JOIN Company ON Jobs.company_id = Company.company_id
";
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
            <h2>Applications</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>View Applications</h2>
                        <br>
                        <form method="POST" action="print_application_pdf.php" target="_blank" onsubmit="getUserTime()">
                            <input type="hidden" id="user-time" name="user_time" />
                            <button type="submit" class="btn btn-primary">Print Application Report</button>
                        </form>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Application ID</th>
                                    <th>Company ID</th>
                                    <th>Company Name</th>
                                    <th>Worker ID</th>
                                    <th>Worker Username</th>
                                    <th>Job Title</th>
                                    <th>Application Date</th>
                                    <th>Application Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row['application_id']; ?></td>
                                        <td><?php echo $row['company_id']; ?></td>
                                        <td><?php echo $row['company_name']; ?></td>
                                        <td><?php echo $row['worker_id']; ?></td>
                                        <td><?php echo $row['worker_username']; ?></td>
                                        <td><?php echo $row['job_title']; ?></td>
                                        <td><?php echo $row['application_date']; ?></td>
                                        <td><?php echo $row['application_status']; ?></td>
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
