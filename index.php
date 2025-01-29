<?php
// The URL to redirect to
$url = './Pages';

// Perform the redirect
header("Location: $url");

// Ensure that the script stops executing after the redirect
exit();
?>



<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>404.VitalCare Medical Center</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
   </head>
   <body class="inner_page error_404">
      <div class="full_container">
         <div class="container">
            <div class="center verticle_center full_height">
               <div class="error_page">
                  <div class="center">
                     <div class="error_icon">
                        <img class="img-responsive" src="./error.png" alt="#">
                     </div>
                  </div>
                  <br>
                  <h3>PAGE NOT FOUND !</h3>
                  <P>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</P>
                  <div class="center"><a class="main_bt" href="./pages">Go To Home Page</a></div>
               </div>
            </div>
         </div>
      </div>
      <!-- Bootstrap JS and dependencies -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
      <script>
         var ps = new PerfectScrollbar('#sidebar');
      </script>
   </body>
</html>

