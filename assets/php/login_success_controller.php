<?php
    /*
        login_controller.php 
        - backend for views/login.php
    */

    include('authentication.php');

    // Log successful authentication to EVENT_LOGS table
    function logAuthentication(&$conn) {
        
        if($_SESSION['logged_in']){
            $email = $_SESSION['user_email'];
            $type = 'A'; // Authentication Log
            $result = 'Success';

            $log_stmt = $conn->prepare("CALL sp_log_event(?, ?, ?)");

            $log_stmt->bind_param("sss", $type, $email, $result);
            $log_stmt->execute();
        }
    
   }
?>