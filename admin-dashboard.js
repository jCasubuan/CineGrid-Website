function updateDashboardCounts() {
    let movies = JSON.parse(localStorage.getItem("movies")) || [];

    document.getElementById("totalMovies").innerText =
        movies.filter(m => m.type === "movie").length;

    document.getElementById("totalSeries").innerText =
        movies.filter(m => m.type === "series").length;

    document.getElementById("totalReviews").innerText =
        movies.reduce((sum, item) => sum + (item.reviews?.length || 0), 0);
}

// Initial load
updateDashboardCounts();

// Listen for changes in localStorage from other tabs/windows
window.addEventListener('storage', function(e) {
    if(e.key === 'movies') {
        updateDashboardCounts();
    }
});

// Optional: auto-refresh every 2 seconds if editing in same tab
setInterval(updateDashboardCounts, 2000);