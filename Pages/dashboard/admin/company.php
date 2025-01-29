<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Authenticate the user
if (!isset($_SESSION['admin_id'], $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}

// Fetch companys data
$query = "SELECT * FROM Company";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching companys: " . $conn->error);
}
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
            <h2>companys</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>View companys</h2>
                        <br>
                        <form method="POST" action="print_companys_pdf.php" target="_blank" onsubmit="getUserTime()">
                            <input type="hidden" id="user-time" name="user_time" />
                            <button type="submit" class="btn btn-primary">Print company Report</button>
                        </form>
                    </div>
                    <div class="body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>S. No.</th>
                                    <th>User ID</th>
                                    <th>company ID</th>
                                    <th>company name</th>
                                    <th>company Email</th>
                                    <th>company Phone Number</th>
                                    <th>company Address</th>
                                    <th>company Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$row['user_id']}</td>
                                        <td>{$row['company_id']}</td>
                                        <td>{$row['company_name']}</td>
                                        <td>{$row['company_email']}</td>
                                        <td>{$row['company_phone_number']}</td>
                                        <td>{$row['company_address']}</td>
                                        <td>{$row['company_created_at']}</td>
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
