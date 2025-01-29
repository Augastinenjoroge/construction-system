<?php
session_start(); // Start the session

// Check for error messages and form data
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$form_type = isset($_SESSION['form_type']) ? $_SESSION['form_type'] : 'worker'; // Default to worker form

// Clear session variables
unset($_SESSION['error'], $_SESSION['form_data'], $_SESSION['form_type']);
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <!-- Custom Style -->
  <link rel="stylesheet" href="css/style.css">

  <title>HardHatJobs - Register</title>

  <style>
    /* Smooth form transitions */
    .hidden {
      display: none;
    }

    .form-transition {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.5s ease-in-out;
    }

    .form-transition.active {
      opacity: 1;
      transform: translateY(0);
    }

    /* Animation and custom styles */
    .form-container {
      position: relative;
    }

    .slide-in {
      animation: slideIn 0.6s ease-in-out forwards;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .btn-toggle {
      cursor: pointer;
      color: #007bff;
      text-decoration: underline;
    }

    /* Hover effect for buttons */
    .btn-primary,
    .btn-secondary {
      transition: all 0.3s ease;
      width: 100%;
      /* Full width for better responsiveness */
    }

    .btn-primary:hover,
    .btn-secondary:hover {
      background-color: #333;
      color: white;
    }

    .scrollable-form-container {
      max-height: 400px;
      /* Adjust this height as needed */
      overflow-y: auto;
      /* Enable vertical scrolling */
      padding-right: 15px;
      /* Optional: padding for better alignment */
    }

    /* Custom scrollbar styling for a cleaner look */
    .scrollable-form-container::-webkit-scrollbar {
      width: 8px;
    }

    .scrollable-form-container::-webkit-scrollbar-thumb {
      background-color: #007bff;
      border-radius: 4px;
    }
  </style>

  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Font Awesome for icons -->
</head>

<body onload="showLogin()">
  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('images/markusspiske.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">



            <h3>Welcome to <strong>HardHatJobs</strong></h3>
            <p id="form-description" class="mb-4">Choose to register as a worker or company, and log in when you're done!</p>

            <!-- Toggle buttons -->
            <div class="d-flex flex-column mb-3">
              <button class="btn btn-primary mb-2" onclick="toggleForm('worker')">Register as Worker</button>
              <button class="btn btn-secondary mb-2" onclick="toggleForm('company')">Register as Company</button>
              <button class="btn btn-outline-dark" onclick="showLogin()">Login</button>
            </div>

            <?php if ($error_message): ?>
              <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <!-- Worker Registration Form -->
            <form id="worker-form" class="<?php echo ($form_type == 'worker') ? '' : 'hidden'; ?>" method="POST" action="./config/register.php">
              <h5>Register as Worker</h5>
              <input type="hidden" name="role" value="worker">
              <input type="hidden" name="form_type" value="worker">
              <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Username</label>
                <input type="text" class="form-control" name="username" required value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>">
              </div>
              <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" name="email" required value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>">
              </div>
              <div class="form-group">
                <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                <input type="tel" class="form-control" name="phone" required value="<?php echo isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : ''; ?>">
              </div>
              <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <!-- Company Registration Form -->
            <div class="scrollable-form-container">
              <form id="company-form" class="<?php echo ($form_type == 'company') ? '' : 'hidden'; ?>" method="POST" action="./config/register.php">
                <h5>Register as Company</h5>
                <input type="hidden" name="role" value="company">
                <input type="hidden" name="form_type" value="company">
                <div class="form-group">
                  <label for="company_name"><i class="fas fa-building"></i> Company Name</label>
                  <input type="text" class="form-control" name="company_name" required value="<?php echo isset($_SESSION['form_data']['company_name']) ? $_SESSION['form_data']['company_name'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="email"><i class="fas fa-envelope"></i> Email</label>
                  <input type="email" class="form-control" name="email" required value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                  <input type="tel" class="form-control" name="phone" required value="<?php echo isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : ''; ?>">
                </div>
                <!-- <div class="form-group">
                  <label for="contact_person"><i class="fas fa-user"></i> Contact Person</label>
                  <input type="text" class="form-control" name="contact_person" required value="<?php echo isset($_SESSION['form_data']['contact_person']) ? $_SESSION['form_data']['contact_person'] : ''; ?>">
                </div> -->
                <div class="form-group">
                  <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                  <input type="text" class="form-control" name="address" required value="<?php echo isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : ''; ?>">
                </div>
                <div class="form-group">
                  <label for="password"><i class="fas fa-lock"></i> Password</label>
                  <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
              </form>
            </div>

            <p id="login-link" class="mt-3">Already have an account? <span class="btn-toggle" onclick="showLogin()">Log in here</span></p>

            <!-- Login Form -->
            <form id="login-form" class="form-transition hidden" method="POST" action="./config/login.php">
              <h5>Login</h5>
              <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <input type="submit" value="Log In" class="btn btn-primary">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Clear the error message when any button is clicked
    document.querySelectorAll('button, input[type="submit"]').forEach(button => {
      button.addEventListener('click', () => {
        const errorAlert = document.querySelector('.alert-danger');
        if (errorAlert) {
          errorAlert.remove(); // Remove the error message
        }
      });
    });

    // Function to toggle between forms
    function toggleForm(type) {
      document.getElementById('worker-form').classList.add('hidden');
      document.getElementById('company-form').classList.add('hidden');
      document.getElementById('login-form').classList.add('hidden');
      document.getElementById('login-link').classList.add('hidden'); // Hide login link on registration forms

      const description = document.getElementById('form-description');

      if (type === 'worker') {
        description.innerHTML = 'Fill in your details to register as a construction worker!';
        document.getElementById('worker-form').classList.remove('hidden');
        document.getElementById('worker-form').classList.add('active', 'slide-in');
      } else if (type === 'company') {
        description.innerHTML = 'Provide your company details to register as an employer!';
        document.getElementById('company-form').classList.remove('hidden');
        document.getElementById('company-form').classList.add('active', 'slide-in');
      }
    }

    // Show login form
    function showLogin() {
      const description = document.getElementById('form-description');
      description.innerHTML = 'Login with your credentials below!';
      document.getElementById('worker-form').classList.add('hidden');
      document.getElementById('company-form').classList.add('hidden');
      document.getElementById('login-form').classList.remove('hidden');
      document.getElementById('login-form').classList.add('active', 'slide-in');
      document.getElementById('login-link').classList.add('hidden'); // Hide login link when on login form
      document.getElementById('form-description').textContent = "Please enter your email and password to log in.";
    }
  </script>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
</body>

</html>