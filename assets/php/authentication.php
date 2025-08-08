<?php 
/* 
    user_redirect.php
    - redirect user depending on role
*/
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