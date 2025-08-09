<?php
// Database configuration
require_once('../includes/dbconfig.php');
include('../assets/php/login_controller.php');

session_start();

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

// Process the login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted login credentials (email and password)
    $user_email = $_POST['email'];
    $password = $_POST['password'];

    // Assuming the login validation is handled in login_controller.php
    $login_success = validate_user_login($user_email, $password);

    if ($login_success) {
        // Set session variables upon successful login
        $_SESSION['user_email'] = $user_email;
        $_SESSION['user_role'] = $login_success['role'];  // assuming this returns role information
        $_SESSION['logged_in'] = true;  // Flag indicating the user just logged in
        $_SESSION['show_overlay'] = true; // Ensure overlay is set after login

        // Redirect to the appropriate page after successful login
        if ($_SESSION['user_role'] === 'A') {
            header("Location: admin.php");
            exit();
        } elseif ($_SESSION['user_role'] === 'S') {
            header("Location: admin.php");
            exit();
        } else {
            // Redirect to property listing page
            header("Location: property_listing.php");
            exit();
        }
    } else {
        $error_message = "Invalid email or password.";
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

            <form method="post" action="" class="login_form" id="loginForm">
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

    <script>
        // Wait for the form submission to trigger the console log
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Prevent form submission to ensure we check the session variable first

            <?php if (isset($_SESSION['show_overlay'])): ?>
                // Print the value of session variable 'show_overlay' in the console
                console.log("show_overlay: <?= $_SESSION['show_overlay'] ?>");
            <?php else: ?>
                console.log("show_overlay is not set.");
            <?php endif; ?>

            // Continue with the form submission after the log
            this.submit();
        });
    </script>

</body>
</html>