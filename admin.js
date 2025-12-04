let movies = JSON.parse(localStorage.getItem("movies")) || [];

document.getElementById("totalMovies").innerText =
    movies.filter(m => m.type === "movie").length;

document.getElementById("totalSeries").innerText =
    movies.filter(m => m.type === "series").length;

document.getElementById("totalReviews").innerText =
    movies.reduce((sum, item) => sum + (item.reviews?.length || 0), 0);