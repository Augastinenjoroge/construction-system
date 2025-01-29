<?php
session_start();
include("nav/header.php");
include("db_connection.php");

// Authenticate the user
if (!isset( $_SESSION['role_type'], $_SESSION['user_id'], $_SESSION['role'])) {
    echo "<script>alert('Unauthorized access'); window.location.href='../../../auth/';</script>";
    exit();
}


?>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>DASHBOARD</h2>
            </div>

            
            <section class="md-section" id="id2" style="background-color:#fff;padding:60px 0;">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 ">
							<div class="mb-30">

								<!-- sec-title -->
								<div class="sec-title sec-title__lg-title">
									<h2 class="sec-title__title"> <span>Quality Builders</span></h2><span class="sec-title__divider"></span>
								</div><!-- End / sec-title -->

								<p>Quality Builders is simply dummy text of the printing and typesetting industry. Quality Builders has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p><br>
								<p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularized in the 1960s with the release of Letraset sheets containing Quality Builders passages,</p><br><br>
							</div>
						</div>
						<div class="col-lg-6 ">

							<!-- about -->
							<div class="about"><img src="assets/img/about/2.jpg" alt="" />

								

							</div><!-- End / about -->

						</div>
					</div>
				</div>
			</section>
            <!-- #END# Widgets -->
            <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2>CPU USAGE (%)</h2>
                                </div>
                                <div class="col-xs-12 col-sm-6 align-right">
                                    <div class="switch panel-switch-btn">
                                        <span class="m-r-10 font-12">REAL TIME</span>
                                        <label>OFF<input type="checkbox" id="realtime" checked><span class="lever switch-col-cyan"></span>ON</label>
                                    </div>
                                </div>
                            </div>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div id="real_time_chart" class="dashboard-flot-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->
           
        </div>
    </section>

    <?php include("nav/footer.php"); ?>