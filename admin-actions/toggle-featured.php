<?php
require_once '../includes/init.php';
header('Content-Type: application/json');

$id = (int)$_GET['id'];

// Get current state
$res = $Conn->query("SELECT is_featured FROM movies WHERE movie_id = $id");
$current = $res->fetch_assoc()['is_featured'];

// Flip it (0 to 1 or 1 to 0)
$newState = $current ? 0 : 1;

$stmt = $Conn->prepare("UPDATE movies SET is_featured = ? WHERE movie_id = ?");
$stmt->bind_param('ii', $newState, $id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'new_state' => $newState]);
} else {
    echo json_encode(['status' => 'error']);
}