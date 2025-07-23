<?php
    // Database configuration
    require_once('../includes/dbconfig.php');

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

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Sanitize and validate input
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                    $password = $_POST['password'];

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo "<p class='error-message'>Invalid email format</p>";
                    } else {
                        // Prepare SQL statement
                        $stmt = $conn->prepare("SELECT email, first_name, last_name, password_hash, role FROM users WHERE email = ?");
                        $stmt->bind_param("s", $email);

                        // Execute SQL statement
                        if($stmt->execute()){
                            $success = "Sign-in successful!";
                        }

                        else $error = "Error: " . $stmt->error;
                        
                        $result = $stmt->get_result();

                        if ($result->num_rows == 1) {
                            $user = $result->fetch_assoc();
                            
                            // Verify password
                            if (($password == $user['password_hash'])) {
                                // Set session variables
                                $_SESSION['user_email'] = $user['email'];
                                $_SESSION['first_name'] = $user['first_name'];
                                $_SESSION['last_name'] = $user['last_name'];
                                $_SESSION['user_role'] = $user['role'];
                                $_SESSION['logged_in'] = true;

                                // Redirect based on role
                                if ($user['role'] == 'A') {
                                    header("Location: admin.php");
                                    exit();
                                } elseif ($user['role'] == 'S') {
                                    header("Location: admin.php");
                                    exit();
                                } else {
                                    header("Location: property_listing.php");
                                    exit();
                                }
                            } else {
                                echo "<p class='error-message'>Invalid email or password</p>";
                            }
                        } else {
                            echo "<p class='error-message'>Invalid email or password</p>";
                        }
                        
                        $stmt->close();
                        $conn->close();
                    }
                }

            // Display system messages
            if (isset($_GET['logout'])) {
                echo "<p class='success-message'>You have been successfully logged out.</p>";
            }
            
            if (isset($_GET['registered'])) {
                echo "<p class='success-message'>Registration successful! Please login.</p>";
            }
            
            if (isset($_GET['error'])) {
                echo "<p class='error-message'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
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