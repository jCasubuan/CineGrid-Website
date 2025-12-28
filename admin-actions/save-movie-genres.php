<?php
require_once '../includes/init.php';
header('Content-Type: application/json');

$genres = $_POST['genres'] ?? [];

if (empty($genres)) {
    http_response_code(422);
    echo json_encode(['error' => 'Please select at least one genre']);
    exit;
}

$_SESSION['movie_draft']['genres'] = $genres;
echo json_encode(['status' => 'ok']);