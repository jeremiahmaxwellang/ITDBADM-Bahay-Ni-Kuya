<?php
function adminAccess(){
    if ($_SESSION['user_role'] != 'A' || $_SESSION['user_role'] != 'S') {
        header("Location: login.php");
        exit();
    }
}

function customerAccess(){
    if ($_SESSION['user_role'] != 'C') {
        header("Location: login.php");
        exit();
    }
}

// Log access control event to EVENT_LOGS table
function logAuthorization(&$conn, $status) {

    $type = 'C'; // Authentication Log

    $log_stmt = $conn->prepare("CALL sp_log_event(?, ?, ?)");

    $log_stmt->bind_param("sss", $type, $_SESSION['user_email'], $status);
    $log_stmt->execute();

}

?>