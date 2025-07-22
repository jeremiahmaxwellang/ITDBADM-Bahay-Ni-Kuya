<?php
// Start the session (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Regenerate session ID for security
session_regenerate_id(true);

// Redirect to login page with logout status
header("Location: login.php?logout=1");
exit();
?>