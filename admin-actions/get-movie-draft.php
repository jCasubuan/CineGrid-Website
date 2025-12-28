<?php
require_once '../includes/init.php';

if (
    empty($_SESSION['user_id']) ||
    $_SESSION['user_role'] !== 'admin'
) {
    http_response_code(403);
    exit;
}

header('Content-Type: application/json');

if (empty($_SESSION['movie_draft'])) {
    http_response_code(404);
    echo json_encode(['error' => 'No active draft']);
    exit;
}

echo json_encode($_SESSION['movie_draft']);
