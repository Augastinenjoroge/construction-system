<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
  <meta
    content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
    name="viewport" />
  <title>Welcome To </title>
  <!-- Favicon-->
  <link rel="icon" href="favicon.ico" type="image/x-icon" />

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext"
    rel="stylesheet"
    type="text/css" />
  <link
    href="https://fonts.googleapis.com/icon?family=Material+Icons"
    rel="stylesheet"
    type="text/css" />

  <!-- Bootstrap Core Css -->
  <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet" />

  <!-- Waves Effect Css -->
  <link href="plugins/node-waves/waves.css" rel="stylesheet" />

  <!-- Animation Css -->
  <link href="plugins/animate-css/animate.css" rel="stylesheet" />

  <!-- Morris Chart Css-->
  <link href="plugins/morrisjs/morris.css" rel="stylesheet" />

  <!-- Custom Css -->
  <link href="css/style.css" rel="stylesheet" />

  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
  <link href="css/themes/all-themes.css" rel="stylesheet" />




  <!-- JQuery DataTable Css -->
  <link href="plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
</head>

<body class="theme-red">
  <!-- Page Loader -->
  <div class="page-loader-wrapper">
    <div class="loader">
      <div class="preloader">
        <div class="spinner-layer pl-red">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div>
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
      <p>Please wait...</p>
    </div>
  </div>
  <!-- #END# Page Loader -->
  <!-- Overlay For Sidebars -->
  <div class="overlay"></div>
  <!-- #END# Overlay For Sidebars -->
  <!-- Search Bar -->
  <div class="search-bar">
    <div class="search-icon">
      <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="START TYPING..." />
    <div class="close-search">
      <i class="material-icons">close</i>
    </div>
  </div>
  <!-- #END# Search Bar -->
  <!-- Top Bar -->
  <nav class="navbar">
    <div class="container-fluid">
      <div class="navbar-header">
        <a
          href="javascript:void(0);"
          class="navbar-toggle collapsed"
          data-toggle="collapse"
          data-target="#navbar-collapse"
          aria-expanded="false"></a>
        <a href="javascript:void(0);" class="bars"></a>
        <a class="navbar-brand" href="index.html">WORKER</a>
      </div>

    </div>
  </nav>
  <!-- #Top Bar -->
  <section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
      <!-- User Info -->
      <div class="user-info">
        <!-- <div class="image">
          <img src="images/user.png" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
          <div
            class="name"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
            John Doe
          </div>
          <div class="email">john.doe@example.com</div>

        </div> -->
      </div>
      <!-- #User Info -->
      <!-- Menu -->
      <div class="menu">
        <ul class="list">
          <li class="header">MAIN NAVIGATION</li>
          <li class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
            <a href="index.php">
              <i class="material-icons">home</i>
              <span>Home</span>
            </a>
          </li>
          <li class="<?= $current_page == 'job_card_list.php' ? 'active' : '' ?>">
            <a href="job_card_list.php">
              <i class="material-icons">local_offer</i>
              <span>Job offers</span>
            </a>
          </li>
          <li class="<?= $current_page == 'view_application.php' ? 'active' : '' ?>">
            <a href="view_application.php">
              <i class="material-icons">layers</i>
              <span>Application</span>
            </a>
          </li>
          <li class="<?= $current_page == 'review.php' ? 'active' : '' ?>">
            <a href="review.php">
              <i class="material-icons">note_add</i>
              <span>Add Reviews</span>
            </a>
          </li>
          <li class="<?= $current_page == 'display_view.php' ? 'active' : '' ?>">
            <a href="display_view.php">
              <i class="material-icons">rate_review</i>
              <span>Company Reviews</span>
            </a>
          </li>

          <li class="<?= $current_page == 'worker_profile.php' ? 'active' : '' ?>">
            <a href="worker_profile.php">
              <i class="material-icons">person_pin</i>
              <span>Profile</span>
            </a>
          </li>
          
          <li class="<?= $current_page == 'logout.php' ? 'active' : '' ?>">
            <a href="logout.php">
              <i class="material-icons">input</i>
              <span>Sign out</span>
            </a>
          </li>
        </ul>
      </div>
      <!-- #Menu -->

    </aside>

  </section>