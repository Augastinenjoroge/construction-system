<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Retrieve session variables
$user_id = $_SESSION['user_id'] ?? null;
$company_id = $_SESSION['company_id'] ?? null;
$role = $_SESSION['role'] ?? null;

// Role check
if ($role != 3 || !$user_id || !$company_id) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/index.php';</script>";
    exit();
}

// Fetch current company details
$query = "SELECT * FROM Company WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $company_name = $_POST['company_name'];
    $company_email = $_POST['company_email'];
    $company_phone = $_POST['company_phone'];
    $company_address = $_POST['company_address'];
    $company_description = $_POST['company_description'];
    $company_profile = $company['company_profile'];  // Keep current profile if no new image is uploaded

    $upload_dir = 'images/profiles';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Try creating the directory with full permissions
    }

    if (!is_writable($upload_dir)) {
        chmod($upload_dir, 0777); // Attempt to set permissions if not writable
    }

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        // If a new image is uploaded
        $timestamp = time();
        $filename = $upload_dir . '/' . $timestamp . '_' . basename($_FILES['profile_image']['name']);

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $filename)) {
            $company_profile = $filename;  // Update profile image
        } else {
            echo "<script>alert('Image upload failed');</script>";
        }
    }


    // Update query
    $update_query = "UPDATE Company SET company_name = ?, company_email = ?, company_phone_number = ?, 
                    company_address = ?, company_description = ?, company_profile = ? WHERE company_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssi", $company_name, $company_email, $company_phone, $company_address, $company_description, $company_profile, $company_id);
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully'); window.location.href='company_profile.php';</script>";
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
                echo "<script>alert('Password changed successfully'); window.location.href='company_profile.php';</script>";
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
            <!-- Company Profile Display -->
            <div class="col-xs-12 col-sm-3">
                <div class="card profile-card">
                    <div class="profile-header">&nbsp;</div>
                    <div class="profile-body">
                        <div class="image-area">
                            <img src="<?= $company['company_profile'] ?: 'images/construction.jpeg'; ?>" alt="Profile Image" style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <div class="content-area">
                            <h3><?= $company['company_name'] ?></h3>
                            <!-- <p><?= $company['company_address'] ?></p>
                            <p><?= $company['company_email'] ?></p>
                            <p><?= $company['company_phone_number'] ?></p>
                            <p><?= $company['company_description'] ?></p> -->
                        </div>
                    </div>
                    <div class="profile-footer">
                        <ul>
                            <li>
                                <span><?= $company['company_address'] ?></span>
                                <span></span>

                            </li>
                            <li>
                                <span><?= $company['company_email'] ?></span>
                                <span></span>
                            </li>
                            <li>
                                <span><?= $company['company_phone_number'] ?></span>
                                <span></span>

                            </li>

                        </ul>

                    </div>
                </div>
                <div class="card card-about-me">
                    <div class="header">
                        <h2>About Company</h2>
                    </div>
                    <div class="body">
                        <ul>
                            <li>
                                <div class="title">
                                    <i class="material-icons">library_books</i>
                                    Company Description
                                </div>
                                <div class="content">
                                    <?= $company['company_description'] ?>
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
                                            <label class="col-sm-2 control-label">Company Name</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="company_name" class="form-control" value="<?= $company['company_name'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="email" name="company_email" class="form-control" value="<?= $company['company_email'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone Number</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="company_phone" class="form-control" value="<?= $company['company_phone_number'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Address</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="text" name="company_address" class="form-control" value="<?= $company['company_address'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Profile Image</label>
                                            <div class="col-sm-10">
                                                <div class="form-line">
                                                    <input type="file" name="profile_image" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="card">
                                                    <div class="header">
                                                        <h2>
                                                            Description
                                                            <small>Enter Company description</small>
                                                        </h2>
                                                    </div>
                                                    <div class="body">
                                                        <textarea id="ckeditor" name="company_description" class="form-control"><?= $company['company_description'] ?></textarea>
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

<!-- <script>
    // Select the form and set initial state
    const form = document.querySelector('form');
    const formSubmittedKey = 'formSubmitted';
    const formChangedKey = 'formChanged';

    // Listen for changes on form inputs to track if there are unsaved changes
    form.addEventListener('input', () => {
        localStorage.setItem(formChangedKey, 'true');
        localStorage.removeItem(formSubmittedKey); // Reset submission state on input change
    });

    // On form submission, set a flag in localStorage to mark it as submitted
    form.addEventListener('submit', (event) => {
        localStorage.setItem(formSubmittedKey, 'true');
        localStorage.removeItem(formChangedKey);
    });

    // Check on page load if the form was previously submitted
    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem(formSubmittedKey) === 'true' && !localStorage.getItem(formChangedKey)) {
            // If form was submitted and no new changes, reset the form and prevent resubmission
            form.reset();
            localStorage.removeItem(formSubmittedKey);
        }
    });
</script> -->

<script>
    document.getElementById('companyProfileForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = new FormData(this);

        fetch('update_company_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Update the displayed company details without page reload
                    document.querySelector('.profile-body .image-area img').src = data.company_profile;
                    document.querySelector('.profile-body h3').textContent = data.company_name;
                    document.querySelector('.profile-footer li:nth-child(1) span').textContent = data.company_address;
                    document.querySelector('.profile-footer li:nth-child(2) span').textContent = data.company_email;
                    document.querySelector('.profile-footer li:nth-child(3) span').textContent = data.company_phone_number;
                    document.querySelector('.card-about-me .content').textContent = data.company_description;
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