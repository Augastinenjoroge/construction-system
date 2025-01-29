<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Authenticate the user
if (!isset($_SESSION['admin_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

// Fetch job data
$query = "
    SELECT 
        j.job_id, j.company_id, c.company_name, j.job_title, j.job_description, 
        j.job_location, j.job_salary, j.job_status, j.job_created_at 
    FROM Jobs j
    JOIN Company c ON j.company_id = c.company_id
";
$result = $conn->query($query);
?>

<script type="text/javascript">
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
            <h2>Job Listings</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Jobs Overview</h2>
                        <form method="POST" action="print_job_pdf.php" target="_blank" onsubmit="getUserTime()">
                            <input type="hidden" id="user-time" name="user_time" />
                            <button type="submit" class="btn btn-primary">Print Job Report</button>
                        </form>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>Job ID</th>
                                    <th>Company Name</th>
                                    <th>Job Title</th>
                                    <th>Job Description</th>
                                    <th>Job Location</th>
                                    <th>Job Salary</th>
                                    <th>Job Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row['job_id']; ?></td>
                                        <td><?php echo $row['company_name']; ?></td>
                                        <td><?php echo $row['job_title']; ?></td>
                                        <td><?php echo substr($row['job_description'], 0, 250); ?></td>
                                        <td><?php echo $row['job_location']; ?></td>
                                        <td><?php echo $row['job_salary']; ?></td>
                                        <td><?php echo $row['job_status']; ?></td>
                                        <td><?php echo $row['job_created_at']; ?></td>
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
