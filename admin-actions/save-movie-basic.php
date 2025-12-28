<?php
require_once '../includes/init.php';

if (
    empty($_SESSION['user_id']) ||
    $_SESSION['user_role'] !== 'admin'
) {
    http_response_code(403);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

header('Content-Type: application/json');

// Required fields
$title = trim($_POST['title'] ?? '');
$overview = trim($_POST['overview'] ?? '');
$releaseYear = $_POST['release_year'] ?? null;

// Optional fields
$tmdbId = $_POST['tmdb_id'] ?? null;
$posterPath = $_POST['poster_path'] ?? null;
$backdropPath = $_POST['backdrop_path'] ?? null;

// Validation
if ($title === '' || $overview === '' || !$releaseYear) {
    echo json_encode([
        'error' => 'Required fields missing.'
    ]);
    exit;
}

// Store draft in session (STEP 1)
$_SESSION['movie_draft']['basic'] = [
    'title' => $title,
    'overview' => $overview,
    'release_year' => (int)$releaseYear,
    'rating' => (float)$_POST['rating'],
    'type' => $_POST['type'] ?? 'movie',
    'tmdb_id' => $tmdbId ?: null,
    'poster_path' => $posterPath ?: null,
    'backdrop_path' => $backdropPath ?: null
];

echo json_encode([
    'status' => 'ok'
]);
