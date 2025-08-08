<?php
    /*
        login_controller.php 
        - backend for views/login.php
    */

    include('authentication.php');

    function login(&$conn) {
     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize and validate input
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error-message'>Invalid email format</p>";
        } 

        else {
            // Prepare SQL statement
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);

            // Execute SQL statement
            if($stmt->execute()){
                $success = "Sign-in successful!";
            }

            else $error = "Error: " . $stmt->error;
            
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $stored_hash = $user['password_hash'];
                
                // Verify password by comparing the hash of the input vs the actual password hash
                if( password_verify($password, $user['password_hash']) ){
                    // Set session variables
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['logged_in'] = true;

                    // If user has no prior security questions, prompt if user wants to set it
                    if($user['question_id'] == null) {
                        header("Location: login_success.php");
                    }

                    // user_redirect.php
                    else{
                        redirectUser($user);
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
   }
?>