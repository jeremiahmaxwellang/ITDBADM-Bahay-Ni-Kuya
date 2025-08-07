<?php
    // Database configuration
    require_once('../includes/dbconfig.php');
    include('../assets/php/login_controller.php');

    session_start();

    // Redirect if already logged in
    if (isset($_SESSION['user_email'])) {
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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page - Bahay ni Kuya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body style="background-image: url('../assets/images/pbb house.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="login-bg-gradient"></div>
    
    <div class="top_left_title">
        <img src="../assets/images/pbb logo.png" alt="Logo">
        BAHAY NI KUYA
    </div>

    <div class="login_container">
        <h2 class="login_subtitle">LOGIN WITH YOUR <span style="color: CornflowerBlue;">BAHAY NI KUYA</span> ACCOUNT</h2>

        <div class="login_separator"></div>

        <div class="login_formDiv">
            <?php
                login($conn);
            ?>

            <form method="post" action="" class="login_form">
                <label for="email" class="login_label">Email:</label>
                <input type="email" id="email" name="email" class="login_input" required placeholder="Enter your Email">
                
                <label for="password" class="login_label">Password:</label>
                <input type="password" id="password" name="password" class="login_input" required placeholder="Enter your Password">
                
                <button type="submit" class="login_button">Sign In</button>
                
                <p class="register_prompt">Don't have an account yet? <a href="register.php" class="register_link">Register</a></p>
                <p class="register_prompt">Forgot password? <a href="forgot_password.php" class="register_link">Reset here</a></p>
            </form>
        </div>
    </div>
</body>
</html>