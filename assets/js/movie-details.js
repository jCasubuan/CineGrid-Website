document.addEventListener('DOMContentLoaded', function () {
    // I am using your exact variable names and logic below
    const starRating = document.getElementById('starRating');

    if (!starRating) {
        return;
    }

    // Star Rating Interaction (10-point scale)
    const stars = starRating.querySelectorAll('i');
    const ratingValue = document.getElementById('ratingValue');
    let selectedRating = 0;

    stars.forEach(star => {
        star.style.cursor = 'pointer';

        star.addEventListener('mouseenter', () => {
            const rating = star.getAttribute('data-rating');
            highlightStars(rating);
        });

        star.addEventListener('click', () => {
            selectedRating = star.getAttribute('data-rating');
            ratingValue.textContent = selectedRating + '/10';
            ratingValue.style.display = 'block';
        });
    });

    starRating.addEventListener('mouseleave', () => {
        if (selectedRating > 0) {
            highlightStars(selectedRating);
        } else {
            resetStars();
        }
    });

    function highlightStars(rating) {
        stars.forEach(star => {
            if (star.getAttribute('data-rating') <= rating) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill', 'text-warning');
            } else {
                star.classList.remove('bi-star-fill', 'text-warning');
                star.classList.add('bi-star');
            }
        });
    }

    function resetStars() {
        stars.forEach(star => {
            star.classList.remove('bi-star-fill', 'text-warning');
            star.classList.add('bi-star');
        });
    }

    // Submit Rating
    const submitRatingBtn = document.getElementById('submitRating');
    if (submitRatingBtn) {
        submitRatingBtn.addEventListener('click', () => {
            if (selectedRating > 0) {
                alert(`You rated this movie ${selectedRating}/10`);
                const modalEl = document.getElementById('ratingModal');
                bootstrap.Modal.getInstance(modalEl)?.hide();
            } else {
                alert('Please select a rating first!');
            }
        });
    }

    // Review Star Rating (5-point scale)
    const reviewStars = document.querySelectorAll('#reviewStarRating i');
    let reviewRating = 0;

    if (reviewStars.length) {
        reviewStars.forEach(star => {
            star.style.cursor = 'pointer';

            star.addEventListener('click', () => {
                reviewRating = star.getAttribute('data-rating');

                reviewStars.forEach(s => {
                    if (s.getAttribute('data-rating') <= reviewRating) {
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill', 'text-warning');
                    } else {
                        s.classList.remove('bi-star-fill', 'text-warning');
                        s.classList.add('bi-star');
                    }
                });
            });
        });
    }


    // Submit Review
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', e => {
            e.preventDefault();

            if (reviewRating === 0) {
                alert('Please rate the movie!');
                return;
            }

            alert('Review submitted successfully!');
            const modalEl = document.getElementById('reviewModal');
            bootstrap.Modal.getInstance(modalEl)?.hide();
        });
    }
});
