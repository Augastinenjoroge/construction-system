<?php
// Include the header file
include("nav/header.php");

// Include the database connection
include("db_connection.php");

// Query the database to get available jobs
$sql = "SELECT j.job_id, j.job_title, j.job_description, j.job_location, j.job_salary, c.company_name, c.company_profile
        FROM Jobs j
        JOIN Company c ON j.company_id = c.company_id
        WHERE j.job_status = 'available'";

$result = mysqli_query($conn, $sql);

// Check if there are any available jobs
if (mysqli_num_rows($result) > 0) {
    echo '<section class="content">
            <div class="container-fluid">
                <div class="block-header">
                    <h2>List of All Available Jobs</h2>
                </div>
                <div class="row clearfix">';

    // Loop through the results and generate dynamic cards
    while ($row = mysqli_fetch_assoc($result)) {
        $job_id = $row['job_id'];
        $job_title = $row['job_title'];
        $job_description = substr($row['job_description'], 0, 200) . '...'; // Truncate description for display
        $job_location = $row['job_location'];
        $job_salary = number_format($row['job_salary'], 2); // Format salary
        $company_name = $row['company_name'];
        $company_profile = $row['company_profile'];

        // Randomize size and position for dynamic card styles
        $random_size = rand(4, 7) * 100; // Random width between 200px and 500px
        $random_position = rand(0, 100); // Random position for margin

        // Display the job card with company profile
        echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top: ' . $random_position . 'px; width: ' . $random_size . 'px;">
                <div class="card shadow-lg animated fadeInUp">
                    <div class="header">
                        <div class="content">

                            <!-- Company Profile Image on the Left -->
                            <span>
                                <img src="../company/' . $company_profile . '" class="img-fluid rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="' . $company_name . ' Logo">
                            </span>

                            <!-- Company Name and Job Location on the Right -->
                            <span>
                                <h3>' . $company_name . '</h3>
                                <p class="text-muted">' . $job_location . '</p>
                            </span>
                        </div>
                    </div>

                    <!-- Job Details Below -->
                    <div class="body">
                        <h5>' . $job_title . '</h5>
                        <p>' . $job_description . '</p>
                        <p><strong>Salary: </strong>$' . $job_salary . '</p>
                        <a href="job_detail.php?job_id=' . $job_id . '" class="btn btn-primary btn-sm">View More</a>
                    </div>
                </div>
            </div>';
    }

    echo '</div>
        </div>
    </section>
    <script src="./plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="./plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="./plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>';
} else {
    echo '<p>No available jobs at the moment.</p>';
}

// Close the database connection
mysqli_close($conn);

// Include the footer file
include("nav/footer.php");
?>
