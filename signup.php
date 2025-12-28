<?php
require_once 'includes/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    $_SESSION['signup_error'] = 'All fields are required.';
    header('Location: index.php');
    exit;
}

// 1. Check if email exists
$stmt = $Conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['signup_error'] = 'Email already registered.';
    header('Location: index.php');
    exit;
}
$stmt->close();

// 2. Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// 3. Insert user
$stmt = $Conn->prepare("
    INSERT INTO users (full_name, email, password_hash)
    VALUES (?, ?, ?)
");
$stmt->bind_param("sss", $name, $email, $passwordHash);

if (!$stmt->execute()) {
    $_SESSION['signup_error'] = 'Signup failed. Please try again.';
    header('Location: index.php');
    exit;
}

$userId = $stmt->insert_id;
$stmt->close();

/* 4. Create session */
$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;

// One-time welcome message (SIGNUP ONLY)
$_SESSION['welcome_new_user'] = true;
$_SESSION['welcome_name'] = $name;

/* 5. Redirect */
header('Location: index.php');
exit;

/* NOTE: prepared statements in PHP are a method for executing SQL queries 
that offer robust protection against SQL injection and improved performance.*/
