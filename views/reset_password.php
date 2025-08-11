<?php
    session_start();

    // Database configuration
    require_once('../includes/dbconfig.php');
    include('../assets/php/reset_password_controller.php');
    $email = $_SESSION['recovery_email'] ?? '';
    

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
        <h2 class="register_subtitle">RESET YOUR <span style="color: CornflowerBlue;">PASSWORD</span> FOR <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></h2>
        <div class="register_separator"></div>

        <?php
            changePassword($conn); // ../assets/php/reset_password_controller.php
        ?>

        <form method="post" action="">

            <div class="register_field_group">
                <label for="password" class="register_label">Enter Password</label>
                <input type="password" id="password" name="password" class="register_input" required placeholder="Enter your New Password">
            </div>

            <div class="register_field_group">
                <label for="confirm_password" class="register_label">Re-enter Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="register_input" required placeholder="Re-enter your New Password">
            </div>

            <button type="submit" class="register_button">Change Password</button>

            <p class="register_prompt">
                Already have an account? <a href="login.php">Login</a>
            </p>
        </form>
    </div>

</body>
</html>
