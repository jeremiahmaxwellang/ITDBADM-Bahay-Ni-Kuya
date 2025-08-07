<?php
/*
    register_controller.php
    - backend of views/register.php
 */

    include('validate_password.php');

    // Called in <div class="register_container">
    function register(&$conn) {

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect and sanitize user input
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $error = "";

            // Hash Passwords
            $password = $_POST['password'];
            $hash = password_hash($password, PASSWORD_DEFAULT); 

            $confirm_password = $_POST['confirm_password'];

        
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error = "Invalid email format";
            }

            // Validate Password
            elseif( passwordIsValid($password, $confirm_password, $error) ){

                // STORED PROCEDURE: CALL sp_add_user
                // TODO: Users must pick a security question and answer
                $stmt = $conn->prepare("CALL sp_add_user(?, ?, ?, ?)");

                // Bind four strings to the ?, ?, ?, ?
                $stmt->bind_param("ssss", $email, $first_name, $last_name, $hash);

                if($stmt->execute()){
                    $success = "Account created successfully!";
                }

                else $error = "Error: " . $stmt->error;

                $stmt->close();
            }

            $conn->close();

        } 

        // Redirect to Login page if successful
        if(isset($success)){
            header("Location: login.php");
            exit();
        }

        elseif(isset($error)){
            echo "<p class='error-message'>{$error}</p>";
        }
    }

?>