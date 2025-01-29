<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Retrieve session variables
$user_id = $_SESSION['user_id'] ?? null;
$worker_id = $_SESSION['worker_id'] ?? null;
$role = $_SESSION['role'] ?? null;

// Role check
if ($role != 2 || !$user_id || !$worker_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/index.php';</script>";
    exit();
}

// Fetch current worker details
$query = "SELECT * FROM Worker WHERE worker_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$worker = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $worker_name = $_POST['worker_username'];
    $worker_email = $_POST['worker_email'];
    $worker_phone = $_POST['worker_phone'];
    $worker_address = $_POST['worker_address'];
    $worker_description = $_POST['worker_description'];



    // Update query
    $update_query = "UPDATE Worker SET worker_username = ?, worker_email = ?, worker_phone_number = ?, 
                    worker_address = ?, worker_description = ? WHERE worker_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssi", $worker_name, $worker_email, $worker_phone, $worker_address, $worker_description, $worker_id);
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully'); window.location.href='worker_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
    $stmt->close();
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $password_query = "SELECT password_hash FROM Users WHERE user_id = ?";
        $stmt = $conn->prepare($password_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($password_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($old_password, $password_hash)) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password_query = "UPDATE Users SET password_hash = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_password_query);
            $stmt->bind_param("si", $new_password_hash, $user_id);
            if ($stmt->execute()) {
                echo "<script>alert('Password changed successfully'); window.location.href='worker_profile.php';</script>";
            } else {
                echo "<script>alert('Error changing password');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Old password is incorrect');</script>";
        }
    } else {
        echo "<script>alert('New passwords do not match');</script>";
    }
}
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
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span><?= $worker['worker_address'] ?></span>
                                <span></span>

                            </li>
                            <li>
                                <span><?= $worker['worker_email'] ?></span>
                                <span></span>
                            </li>
                            <li>
                                <span><?= $worker['worker_phone_number'] ?></span>
                                <span></span>

                            </li>

                        </ul>

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
                                <?= strip_tags($worker['worker_description']) ?: 'No description provided' ?>
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
                                <li class="active"><a href="#profile_settings" data-toggle="tab">Profile Settings</a></li>
                                <li><a href="#change_password_settings" data-toggle="tab">Change Password</a></li>
                            </ul>

                            <div class="tab-content">
                                <!-- Profile Settings -->
                                <div class="tab-pane fade in active" id="profile_settings">
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">worker Name</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="worker_username" class="form-control uppercase" value="<?= $worker['worker_username'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="email" name="worker_email" class="form-control" value="<?= $worker['worker_email'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="worker_phone" class="form-control" value="<?= $worker['worker_phone_number'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Address</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="worker_address" class="form-control" value="<?= $worker['worker_address'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card">
                                                    <div class="header">
                                                        <h2>
                                                            Description
                                                            <small>Enter worker description</small>
                                                        </h2>
                                                    </div>
                                                    <div class="body">
                                                        <textarea id="ckeditor" name="worker_description" class="form-control"><?= $worker['worker_description'] ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" name="update_profile" class="btn btn-danger">Update Profile</button>
                                    </form>
                                </div>

                                <!-- Change Password -->
                                <div class="tab-pane fade" id="change_password_settings">
                                    <form method="POST">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Old Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" name="old_password" class="form-control" id="OldPassword" placeholder="Old Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">New Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" name="new_password" class="form-control" id="NewPassword" placeholder="New Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Confirm New Password</label>
                                            <div class="col-sm-9">
                                                <div class="form-line">
                                                    <input type="password" name="confirm_password" class="form-control" id="NewPasswordConfirm" placeholder="New Password (Confirm)" required>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" name="change_password" class="btn btn-danger">Change Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<style>
    .image-area {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .content-area h3 {
        text-transform: uppercase; /* Converts text to uppercase */
    }
    .uppercase{
        text-transform: uppercase;
    }
</style>



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




<script>
    document.getElementById('workerProfileForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);

        fetch('update_worker_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Update the displayed worker details without page reload

                    document.querySelector('.profile-body h3').textContent = data.worker_username;
                    document.querySelector('.profile-footer li:nth-child(1) span').textContent = data.worker_address;
                    document.querySelector('.profile-footer li:nth-child(2) span').textContent = data.worker_email;
                    document.querySelector('.profile-footer li:nth-child(3) span').textContent = data.worker_phone_number;
                    document.querySelector('.card-about-me .content').textContent = data.worker_description;
                }
            })
            .catch(error => console.error('Error:', error));
    });
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