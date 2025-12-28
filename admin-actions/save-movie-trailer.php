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

// Ensure draft exists
if (empty($_SESSION['movie_draft'])) {
    http_response_code(409);
    echo json_encode(['error' => 'No active movie draft']);
    exit;
}

$trailer_url = trim($_POST['trailer_url'] ?? '');

// Optional but validated
if ($trailer_url !== '' && !filter_var($trailer_url, FILTER_VALIDATE_URL)) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid trailer URL']);
    exit;
}

// Save trailer
$_SESSION['movie_draft']['trailer'] = [
    'url' => $trailer_url
];

echo json_encode(['status' => 'ok']);
