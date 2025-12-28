<?php
require_once '../includes/init.php';

header('Content-Type: application/json');

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


if (empty($_SESSION['movie_draft'])) {
    http_response_code(409);
    echo json_encode(['error' => 'No active movie draft']);
    exit;
}

$actors = $_POST['actors'] ?? [];
$characters = $_POST['characters'] ?? [];

if (!is_array($actors) || !is_array($characters)) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid cast format']);
    exit;
}

if (count($actors) !== count($characters)) {
    http_response_code(422);
    echo json_encode(['error' => 'Mismatched cast entries']);
    exit;
}

$cleanCast = [];

for ($i = 0; $i < count($actors); $i++) {
    $actor = trim($actors[$i]);
    $character = trim($characters[$i]);

    if ($actor === '' || $character === '') {
        continue;
    }

    if (strlen($actor) > 255 || strlen($character) > 255) {
        http_response_code(422);
        echo json_encode(['error' => 'Actor or character name too long']);
        exit;
    }

    $cleanCast[] = [
        'actor' => $actor,
        'character' => $character
    ];
}

if (empty($cleanCast)) {
    http_response_code(422);
    echo json_encode(['error' => 'At least one cast member is required']);
    exit;
}

$_SESSION['movie_draft']['cast'] = $cleanCast;

echo json_encode(['status' => 'ok']);
