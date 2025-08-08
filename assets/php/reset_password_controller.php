<?php
/*
    register_controller.php
    - backend of views/register.php
 */

    include('validate_password.php');

    // Change password
    function changePassword(&$conn, $email) {

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect and sanitize user input
            $error = "";

            // Hash Passwords
            $password = $_POST['password'];
            $hash = password_hash($password, PASSWORD_DEFAULT); 

            $confirm_password = $_POST['confirm_password'];

            // Validate Password
            if( passwordIsValid($password, $confirm_password, $error) && !isReused($conn, $email, $password, $error)){

                // STORED PROCEDURE: CALL sp_add_user
                $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");

                // Bind the strings to the ?, ?
                $stmt->bind_param("ss", $hash, $email);

                if($stmt->execute()){
                    $success = "Password changed successfully!";

                    // Store password in OLD_PASSWORDS TABLE to prevent future reuse
                    $pass_stmt = $conn->prepare("CALL sp_record_password(?, ?)");
                    $pass_stmt->bind_param("ss", $email, $hash);
                    $pass_stmt->execute();

                }

                else $error = "Error: " . $stmt->error;

                $stmt->close();
            }

            $conn->close();

        } 

        // If Successful, user proceeds to login
        if(isset($success)){
            header("Location: logout.php"); // terminate session to reauthenticate
            exit();
        }

        elseif(isset($error)){
            echo "<p class='error-message'>{$error}</p>";
        }
    }

?>