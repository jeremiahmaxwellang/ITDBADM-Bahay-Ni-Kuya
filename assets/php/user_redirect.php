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

?>