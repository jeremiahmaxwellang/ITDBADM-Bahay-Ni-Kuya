<?php
    /*
        login_success.php
        - After successful registration, user can select either to proceed to property_listing or get security questions
    */
    session_start();

    // Database configuration
    require_once('../includes/dbconfig.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="register-body">
    <div class="login-bg-gradient"></div>

<body style="background-image: url('../assets/images/pbb house.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="login-bg-gradient"></div>
    <div class="top_left_title">
        <img src="../assets/images/pbb logo.png" alt="Logo">
        BAHAY NI KUYA
    </div>

    <div class="register_container">
        <h2 class="register_subtitle">SET UP <span style="color: CornflowerBlue;">ACCOUNT RECOVERY?</span></h2>
        <div class="register_separator"></div>
        
        <button class="login_button" onclick="window.location.href='security_question.php'">Set up Account Recovery now</button>

        <button class="login_button" onclick="window.location.href='login.php'">No thanks.</button>
            
    </div>

</body>
</html>
