<?php 
/* 
    user_redirect.php
    - redirect user depending on role
*/

// Check if user is already logged in and set the session variables
if (isset($_SESSION['user_email'])) {
    $_SESSION['show_overlay'] = true;  // Set the overlay to true after login

    // Redirect if already logged in
    if ($_SESSION['user_role'] === 'A') {
        header("Location: admin.php");
        exit();
    } elseif ($_SESSION['user_role'] === 'S') {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: property_listing.php");
        exit();
    }
}

function redirectUser($user){
    // Redirect based on role
    if ($user['role'] == 'A' || $user['role'] == 'S') {
        header("Location: admin.php");
        exit();
    } 
    
    else {
        header("Location: property_listing.php");
        exit();
    }
}

// Log successful authentication to EVENT_LOGS table
function logAuthentication(&$conn, $email, $status) {

    $type = 'A'; // Authentication Log

    $log_stmt = $conn->prepare("CALL sp_log_event(?, ?, ?)");

    $log_stmt->bind_param("sss", $type, $email, $status);
    $log_stmt->execute();

}
?>