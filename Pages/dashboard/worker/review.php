<?php include("nav/header.php"); ?>


<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Submit Your Review</h2>
        </div>

        <!-- Review Form -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Review a Company</h2>
                    </div>
                    <div class="body">
                        <form id="reviewForm">
                            <!-- Company Selection -->
                            <div class="form-group">
                                <label for="companySearch">Select Company</label>
                                <input type="text" id="companySearch" class="form-control" placeholder="Search for a company">
                                <div id="companyDropdown" class="dropdown-menu" style="max-height: 200px; overflow-y: auto;"></div>
                                <input type="hidden" id="company_id" name="company_id">
                            </div>
                            <br>

                            <!-- Review Text -->
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        <div class="header">
                                            <h2>Review<small>Enter Company Review</small>
                                            </h2>
                                        </div>
                                        <div class="body">
                                            <textarea class="form-control" id="reviewText" name="review_text" class="form-control" rows="4" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="form-group">
                                <label for="rating">Rating</label>
                                <div id="ratingStars" class="rating">
                                    <span data-value="1" class="star">&#9733;</span>
                                    <span data-value="2" class="star">&#9733;</span>
                                    <span data-value="3" class="star">&#9733;</span>
                                    <span data-value="4" class="star">&#9733;</span>
                                    <span data-value="5" class="star">&#9733;</span>
                                </div>
                                <input type="hidden" id="rating" name="rating" value="1">
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // Dynamic company search
        $('#companySearch').on('input', function() {
            const query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: 'get_companies.php',
                    type: 'GET',
                    data: {
                        search: query
                    },
                    success: function(response) {
                        $('#companyDropdown').empty(); // Clear previous results
                        if (response.length > 0) {
                            response.forEach(function(company) {
                                const companyItem = `<a href="#" class="dropdown-item" data-id="${company.company_id}" style="padding: 8px 15px; display: block;">${company.company_name}</a>`;
                                $('#companyDropdown').append(companyItem);
                            });
                            $('#companyDropdown').show();
                        } else {
                            $('#companyDropdown').append('<div style="padding: 8px 15px;">No companies found</div>');
                            $('#companyDropdown').show();
                        }
                    }
                });
            } else {
                $('#companyDropdown').hide();
            }
        });

        // Select company from dropdown
        $('#companyDropdown').on('click', '.dropdown-item', function() {
            const companyId = $(this).data('id');
            const companyName = $(this).text();
            $('#companySearch').val(companyName);
            $('#company_id').val(companyId); // Populate hidden input
            $('#companyDropdown').hide();

            console.log('Selected Company ID:', companyId); // Debugging
        });


        // Star rating system
        $('#ratingStars .star').on('click', function() {
            const rating = $(this).data('value');
            $('#rating').val(rating); // Update hidden input value
            updateStarColors(rating);
        });


        function updateStarColors(rating) {
            $('#ratingStars .star').each(function() {
                const starValue = $(this).data('value');
                if (starValue <= rating) {
                    $(this).css('color', 'gold'); // Gold color for selected stars
                    $(this).addClass('active').removeClass('selected'); // Add active class for selected stars
                } else {
                    $(this).css('color', 'gray'); // Gray color for unselected stars
                    $(this).removeClass('active').addClass('selected'); // Add selected class for unselected stars
                }
            });
        }

        // Submit review
        $('#reviewForm').on('submit', function(e) {
            e.preventDefault();

            const companyId = $('#company_id').val();
            const reviewText = $('#reviewText').val();
            const rating = $('#rating').val();

            if (!companyId || !reviewText.trim() || !rating) {
                alert("Please fill out all fields.");
                return;
            }

            $.ajax({
                url: 'submit_review.php',
                type: 'POST',
                data: {
                    company_id: companyId,
                    review_text: reviewText,
                    rating: rating
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert("Review submitted successfully!");
                        location.reload();
                    } else if (response.error) {
                        alert(response.error); // Handle PHP error message
                    } else {
                        alert("An unknown error occurred.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Failed to submit the review. Please try again.");
                }
            });
        });
    });
</script>

<style>
    #companySearch {
        width: 100%;
        margin-bottom: 10px;
        position: relative;
        /* Ensures that the dropdown is positioned relative to the search box */
    }

    #companyDropdown {
        display: none;
        position: absolute;
        top: 100%;
        /* Ensures the dropdown appears below the search input */
        width: 100%;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        z-index: 10;
        max-height: 200px;
        overflow-y: auto;
    }

    #companyDropdown .dropdown-item {
        cursor: pointer;
        padding: 8px 15px;
        display: block;
    }

    #companyDropdown .dropdown-item:hover {
        background-color: #f0f0f0;
    }

    #ratingStars .star {
        font-size: 30px;
        /* Bigger stars */
        color: gray;
        /* Default color for unselected stars */
        cursor: pointer;
        transition: transform 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        /* Smooth transition for all effects */
    }

    /* Hover effect for stars */
    #ratingStars .star:hover {
        transform: scale(1.4) rotate(15deg);
        /* Grow and rotate the star on hover */
        color: gold;
        /* Change color to gold */
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
        /* Add a stronger golden glow around the star */
    }

    /* Selected stars */
    #ratingStars .star.selected {
        color: gold;
        /* Gold color for selected stars */
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
        /* Glow effect for selected stars */
    }

    /* Active stars (clicked ones) */
    #ratingStars .star.active {
        color: gold;
        /* Gold color for active stars */
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
        /* Strong glow effect for active stars */
        transform: scale(1.3);
        /* Slightly bigger scale for active stars */
    }
</style>



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



<?php include("nav/footer.php"); ?>