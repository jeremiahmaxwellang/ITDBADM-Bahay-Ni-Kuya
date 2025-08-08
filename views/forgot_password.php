<?php
    // Database configuration
    require_once('../includes/dbconfig.php');

    // Include the forgot password controller
    require_once('../assets/php/forgot_controller.php'); 

    session_start();

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve email from POST request
        $email = $_POST['email'];

        // Retrieve user's data from the database
        $user = get_user_by_email($email);

        if ($user) {
            // Redirect to security_question.php with the email as a query parameter
            header("Location: security_question.php?email=" . urlencode($email));
            exit; // Ensure no further code is executed
        } else {
            // Handle the case where the email is not found (Optional)
            echo "<script>alert('Email not found. Please check and try again.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
        <h2 class="login_subtitle">FORGOT PASSWORD? <span style="color: CornflowerBlue;">BAHAY NI KUYA</span> ACCOUNT</h2>

        <div class="login_separator"></div>

        <div class="login_formDiv">
            <form method="post" action="" class="login_form">
                <label for="email" class="login_label">Email:</label>
                <input type="email" id="email" name="email" class="login_input" required placeholder="Enter your Email">
                
                <button type="submit" class="login_button">Reset Password</button>
                
                <p class="register_prompt">Don't have an account yet? <a href="register.php" class="register_link">Register</a></p>
                <p class="register_prompt">Already have an account? <a href="login.php" class="register_link">Sign In</a></p>
            </form>
        </div>
    </div>
</body>
</html>