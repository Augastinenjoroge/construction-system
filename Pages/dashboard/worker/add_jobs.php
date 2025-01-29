<?php

include("nav/header.php");


?>


<section class="content">
    <div class="container-fluid">

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                        Job details
                        </h2>

                    </div>
                    <div class="body">
                        <div class="demo-masked-input">
                            <div class="row clearfix">
                                <form id="addJobForm" action="submit_job.php" method="POST">
                                    <div class="col-md-3">
                                        <b>Job Title</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">computer</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="job_title" class="form-control" placeholder="Ex: Site preparation" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <b>Job Location</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">location_on</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="job_location" class="form-control" placeholder="Ex: Kabuku" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <b>Money (Dollar)</b>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">attach_money</i>
                                            </span>
                                            <div class="form-line">
                                                <input type="text" name="job_salary" class="form-control" 
                                                step="0.01"
                                                placeholder="Ex: 99.99" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <div class="header">
                                                    <h2>
                                                        Job description
                                                    </h2>
                                                </div>
                                                <div class="body">
                                                    <div>Job description </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmSubmission() {
        const form = document.getElementById('addJobForm');
        const title = form.job_title.value;
        const location = form.job_location.value;
        const salary = form.job_salary.value;
        const description = form.job_description.value;

        const message = `Please confirm your details:\n\nJob Title: ${title}\nLocation: ${location}\nSalary: $${salary}\nDescription: ${description}\n\nClick OK to submit or Cancel to edit.`;
        if (confirm(message)) {
            form.submit();
        }
    }
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