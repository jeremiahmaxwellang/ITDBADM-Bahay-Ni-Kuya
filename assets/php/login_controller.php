<?php
    /*
        login_controller.php 
        - backend for views/login.php
    */

    include('authentication.php');

    function login(&$conn) {
        $status = "Fail";
        $fail_count = 0;

        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and validate input
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<p class='error-message'>Invalid email format</p>";
            } 

            else {
                // Check the number of failed login attempts in the event_logs table
                $stmt = $conn->prepare("SELECT COUNT(*) AS fail_count FROM event_logs WHERE user_email = ? AND result = 'Fail'");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->bind_result($fail_count);
                $stmt->fetch();
                $stmt->close();

                // If the user has exceeded 5 failed login attempts, lock the account
                if ($fail_count >= 5) {
                    // Update account_disabled to 'Y' in the users table
                    $stmt = $conn->prepare("UPDATE users SET account_disabled = 'Y' WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $stmt->close();

                    echo "<p class='error-message'>Your account has been locked due to too many failed login attempts. Please contact support.</p>";
                    return;
                }

                // Prepare SQL statement to check user credentials
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

                    // Check if the account is disabled
                    if ($user['account_disabled'] == 'Y') {
                        echo "<p class='error-message'>Your account is disabled. Please contact support.</p>";
                        $stmt->close();
                        $conn->close();
                        return;
                    }

                    // Verify password by comparing the hash of the input vs the actual password hash
                    if( password_verify($password, $user['password_hash']) ){

                        // Reset login attempts on successful login
                        $_SESSION['login_attempts'] = 0;

                        // Set session variables
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['logged_in'] = true;

                        // Log successful authentication to EVENT_LOGS table
                        $status = "Success";
                        logAuthentication($conn, $user['email'], $status);

                        // If user has no prior security questions, prompt if user wants to set it
                        if($user['question_id'] == null) {
                            header("Location: login_success.php");
                        }

                        // user_redirect.php
                        else{
                            redirectUser($user);
                        }
                    } else {
                        // Log failed login attempt
                        $stmt = $conn->prepare("INSERT INTO event_logs (type, user_email, result) VALUES ('I', ?, 'Fail')");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();

                        echo "<p class='error-message'>Invalid email or password</p>";
                        $status = "Fail";
                        logAuthentication($conn, $user['email'], $status);
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