<!-- MODALS -->
    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="ratingModalLabel">
                        <i class="bi bi-star me-2"></i>Rate This Movie
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h2 class="mb-4">The Dark Knight</h2>
                    <div class="star-rating mb-4" id="starRating">
                        <i class="bi bi-star fs-1 mx-1" data-rating="1"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="2"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="3"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="4"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="5"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="6"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="7"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="8"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="9"></i>
                        <i class="bi bi-star fs-1 mx-1" data-rating="10"></i>
                    </div>
                    <p class="text-muted">Click on a star to rate</p>
                    <div id="ratingValue" class="display-4 text-warning mb-3" style="display: none;">0/10</div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitRating">Submit Rating</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="reviewModalLabel">
                        <i class="bi bi-pencil me-2"></i>Write a Review
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reviewForm">
                        <div class="mb-3">
                            <label for="reviewTitle" class="form-label">Review Title</label>
                            <input type="text" class="form-control" id="reviewTitle" placeholder="Give your review a title" required>
                        </div>
                        <div class="mb-3">
                            <label for="reviewText" class="form-label">Your Review</label>
                            <textarea class="form-control" id="reviewText" rows="6" placeholder="Share your thoughts about this movie..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Rating</label>
                            <div id="reviewStarRating" class="d-flex gap-2">
                                <i class="bi bi-star fs-3" data-rating="1"></i>
                                <i class="bi bi-star fs-3" data-rating="2"></i>
                                <i class="bi bi-star fs-3" data-rating="3"></i>
                                <i class="bi bi-star fs-3" data-rating="4"></i>
                                <i class="bi bi-star fs-3" data-rating="5"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-2"></i>Submit Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>