<!-- 
 
TODO: Add logic to determine if user credentials belong to an admin, staff or customer
Only 1 login page needed for all 3 roles
 
-->

<html>
<head>
    <title>Login Page</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body style="background-image: url('../assets/images/pbb house.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="login-bg-gradient"></div>
    
    <div style="
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 64px;
        font-family: 'Bebas Neue', serif;
        color: CornflowerBlue;
        font-weight: regular;">
        <img src="../assets/images/pbb logo.png" alt="Logo" style="height: 40px; margin-right: 15px; margin-top: 5px">
        BAHAY NI KUYA
    </div>

    <div class="login_container">
        <h2 class="login_subtitle">LOGIN WITH YOUR <span style="color: CornflowerBlue;">BAHAY NI KUYA</span> ACCOUNT</h2>

        <div class="login_separator"></div>

        <div class="login_formDiv">
            <?php
            session_start(); //starts the session

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Database connection
                $servername = "localhost";
                $username = "root"; // Change if necessary
                $password = ""; // Change if necessary
                $dbname = "itmosys_db"; // TODO: CHANGE TO NEW DB NAME

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetching form data
                $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
                $pass = mysqli_real_escape_string($conn, $_POST['password']);

                // Store the student number in a session variable
                $_SESSION['student_id'] = $student_id;

                // Query to check if USER exists
                $sql = "SELECT * FROM students WHERE student_id = '$student_id' AND password = '$pass'";
                $result = $conn->query($sql);

                // TODO: Query to check if USER is an admin, staff, or student


                // TODO: Redirect user to the proper pages
                if ($result->num_rows > 0) {
                    // Success: Redirect to TBA
                    echo "<p>Login successful! Redirecting...</p>";
                    header("refresh:2;url=EnrollmentMenu.php");
                } else {
                    // Failure: Show error message
                    echo "<p style='color:red;'>Invalid username or password.</p>";
                }

                // Close connection
                $conn->close();
            }
            ?>

<!-- TODO: Replace this with the necessary code -->
            <form method="post" action="" class="login_form">
                <label for="student_id" class="login_label">User ID:</label>
                <input type="text" id="student_id" name="student_id" class="login_input" required placeholder="Enter your User ID">
                <label for="password" class="login_label">Password:</label>
                <input type="password" id="password" name="password" class="login_input" required placeholder="Enter your Password">
                <button type="submit" class="login_button">Sign In</button>
                <p class="register_prompt">Don't have an account yet? <a href="register.php" class="register_link">Register</a> </p>
            </form>
        </div>
    </div>

</body>
</html>
