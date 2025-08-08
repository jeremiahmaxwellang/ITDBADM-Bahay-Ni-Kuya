<?php
session_start();

// Optional: Hide PHP warnings/notices from output
error_reporting(0);

require_once('../includes/dbconfig.php');

// Make sure the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "error";
    exit;
}

$currentPassword = $_POST['current_password'] ?? '';
$email = $_SESSION['user_email'];

// Fetch the hashed password from the DB
$stmt = $conn->prepare("SELECT password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user && password_verify($currentPassword, $user['password_hash'])) {
    echo "success";
} else {
    echo "error";
}

$conn->close();
exit;
