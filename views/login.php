<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin.php");
        exit();
    } elseif ($_SESSION['user_role'] === 'staff') {
        header("Location: staff_dashboard.php");
        exit();
    } else {
        header("Location: property_listing.php");
        exit();
    }
}
?>

<html>
<head>
    <title>Login Page - Bahay ni Kuya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-family: 'Lato', sans-serif;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
            font-family: 'Lato', sans-serif;
        }
    </style>
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
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "bahaynikuya_db";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetching form data
                $user_id = mysqli_real_escape_string($conn, $_POST['student_id']);
                $pass = mysqli_real_escape_string($conn, $_POST['password']);

                // Check in users table first (for admin/staff)
                $sql = "SELECT * FROM users WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    
                    // Verify password (in a real app, use password_verify() with hashed passwords)
                    if ($user['password'] === $pass) {
                        $_SESSION['user_id'] = $user['username'];
                        $_SESSION['user_role'] = $user['role'];
                        
                        // Redirect based on role
                        if ($user['role'] == 'admin') {
                            header("Location: admin.php");
                            exit();
                        } elseif ($user['role'] == 'staff') {
                            header("Location: staff_dashboard.php");
                            exit();
                        } else {
                            // For students, check the students table
                            $sql = "SELECT * FROM students WHERE student_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                $_SESSION['student_id'] = $user_id;
                                header("Location: property_listing.php");
                                exit();
                            } else {
                                echo "<p class='error-message'>Invalid username or password.</p>";
                            }
                        }
                    } else {
                        echo "<p class='error-message'>Invalid username or password.</p>";
                    }
                } else {
                    // If not found in users table, check students table
                    $sql = "SELECT * FROM students WHERE student_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $student = $result->fetch_assoc();
                        
                        // Verify password (in a real app, use password_verify() with hashed passwords)
                        if ($student['password'] === $pass) {
                            $_SESSION['student_id'] = $student['student_id'];
                            $_SESSION['user_role'] = 'student';
                            header("Location: property_listing.php");
                            exit();
                        } else {
                            echo "<p class='error-message'>Invalid username or password.</p>";
                        }
                    } else {
                        echo "<p class='error-message'>Invalid username or password.</p>";
                    }
                }
                
                $stmt->close();
                $conn->close();
            }

            // Display logout message if redirected from logout
            if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
                echo "<p class='success-message'>You have been successfully logged out.</p>";
            }
            
            // Display registration success message
            if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
                echo "<p class='success-message'>Registration successful! Please login.</p>";
            }
            ?>

            <form method="post" action="" class="login_form">
                <label for="student_id" class="login_label">User ID:</label>
                <input type="text" id="student_id" name="student_id" class="login_input" required placeholder="Enter your User ID">
                
                <label for="password" class="login_label">Password:</label>
                <input type="password" id="password" name="password" class="login_input" required placeholder="Enter your Password">
                
                <button type="submit" class="login_button">Sign In</button>
                
                <p class="register_prompt">Don't have an account yet? <a href="register.php" class="register_link">Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>