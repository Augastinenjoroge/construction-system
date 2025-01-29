<?php

include("nav/header.php");

?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>View Review</h2>
        </div>

        <!-- Review Form -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>All Company Reviews</h2>
                    </div>
                    <div id="reviewsContainer" class="body mt-4">

                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch reviews
        $.ajax({
            url: 'view_reviews.php', // PHP script to fetch reviews
            type: 'GET',
            dataType: 'json',
            success: function(reviews) {
                const container = $('#reviewsContainer');
                if (reviews.length > 0) {
                    reviews.forEach(review => {
                        const reviewCard = `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${review.company_name}</h5>
                                        <h6 class="card-subtitle text-muted">Reviewed by: ${review.worker_username}</h6>
                                        <p class="card-text mt-3">${review.review_text}</p>
                                        <div>
                                            <span class="text-warning">${'★'.repeat(review.rating)}</span>
                                            <span class="text-muted">${'☆'.repeat(5 - review.rating)}</span>
                                        </div>
                                        <p class="text-end text-muted">${new Date(review.review_date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
                                    </div>
                                </div>
                            `;
                        container.append(reviewCard);
                    });
                } else {
                    container.html('<p class="text-center">No reviews available.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching reviews:", error);
                $('#reviewsContainer').html('<p class="text-center text-danger">Failed to load reviews. Please try again later.</p>');
            }
        });
    });
</script>


<?php
include("nav/footer.php");
?>