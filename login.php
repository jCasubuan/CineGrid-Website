<?php
require_once 'includes/init.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Initialize response array for better error handling
$response = [
    'success' => false,
    'message' => '',
    'field' => '' 
];

// Get and sanitize inputs
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validation: Check for empty fields
if (empty($email) || empty($password)) {
    $response['message'] = 'Please fill in all fields.';
    $response['field'] = empty($email) ? 'email' : 'password';
    $_SESSION['login_error'] = $response['message'];
    $_SESSION['login_error_field'] = $response['field'];
    $_SESSION['show_login_modal'] = true; 
    header('Location: index.php');
    exit;
}

// Validation: Check email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please enter a valid email address.';
    $response['field'] = 'email';
    $_SESSION['login_error'] = $response['message'];
    $_SESSION['login_error_field'] = $response['field'];
    $_SESSION['form_email'] = $email; // Preserve email for UX
    $_SESSION['show_login_modal'] = true; 
    header('Location: index.php');
    exit;
}

// Validation: Check password length (minimum 6 characters)
if (strlen($password) < 6) {
    $response['message'] = 'Password must be at least 6 characters long.';
    $response['field'] = 'password';
    $_SESSION['login_error'] = $response['message'];
    $_SESSION['login_error_field'] = $response['field'];
    $_SESSION['form_email'] = $email;
    $_SESSION['show_login_modal'] = true; 
    header('Location: index.php');
    exit;
}

// Prepare statement to prevent SQL injection
$stmt = $Conn->prepare("
    SELECT id, full_name, email, password_hash, role
    FROM users
    WHERE email = ?
    LIMIT 1
");

if (!$stmt) {
    $_SESSION['login_error'] = 'System error. Please try again later.';
    $_SESSION['show_login_modal'] = true; 
    header('Location: index.php');
    exit;
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows !== 1) {
    $response['message'] = 'Invalid email or password.';
    $response['field'] = 'email';
    $_SESSION['login_error'] = $response['message'];
    $_SESSION['login_error_field'] = $response['field'];
    $_SESSION['show_login_modal'] = true; 
    header('Location: index.php');
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Verify password
if (!password_verify($password, $user['password_hash'])) {
    $response['message'] = 'Invalid email or password.';
    $response['field'] = 'password';
    $_SESSION['login_error'] = $response['message'];
    $_SESSION['login_error_field'] = $response['field'];
    $_SESSION['form_email'] = $email; // Preserve email for UX
    $_SESSION['show_login_modal'] = true; 
    header('Location: index.php');
    exit;
}

// LOGIN SUCCESS - Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['login_success'] = 'Welcome back, ' . htmlspecialchars($user['full_name']) . '!';

// Clear any previous errors
unset($_SESSION['login_error']);
unset($_SESSION['login_error_field']);
unset($_SESSION['form_email']);
unset($_SESSION['show_login_modal']);

// Redirect based on role
if ($_SESSION['user_role'] === 'admin') {
    header('Location: admin.php');
} else {
    header('Location: index.php');
}
exit;