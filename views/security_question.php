<?php
    session_start();

    // Database configuration
    require_once('../includes/dbconfig.php');
    include('../assets/php/security_question_controller.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Security Question</title>
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
        <h2 class="register_subtitle">ACCOUNT RECOVERY: SELECT YOUR <span style="color: CornflowerBlue;">SECURITY QUESTION</span> </h2>
        <div class="register_separator"></div>

         <!-- ../assets/php/security_question_controller.php -->
        <?php submitQuestions($conn); ?>
       
        <form method="post" action="">
            <div class="register_name_row">
                <div class="register_name_column">
                    <label for="security_question" class="register_label">Choose your security question</label>
                    <select id="security_question" name="security_question" class="register_input" required>
                        <option value="" disabled selected>Select a security question</option>
                        
                        <!-- Fetch questions from the DB -->
                        <?php fetchQuestions($conn) ?>

                    </select>
                </div>

            <div class="register_field_group">
                <label for="answer" class="register_label">Answer</label>
                <input type="text" id="answer" name="answer" class="register_input" required placeholder="Enter your answer">
            </div>

            <button type="submit" class="register_button">Confirm</button>

        </form>
    </div>

</body>
</html>
