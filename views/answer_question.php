<?php
    session_start();

    // Database configuration
    require_once('../includes/dbconfig.php');
    include('../assets/php/security_question_controller.php');

    $email = $_GET['email'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Recovery</title>
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
        <h2 class="register_subtitle">ACCOUNT RECOVERY:</h2>
        <h2 class="register_subtitle">Answer the <span style="color: CornflowerBlue;">SECURITY QUESTION</span> </h2>
        <div class="register_separator"></div>
       
        <form method="post" action="">

            <p class="register_label">
            <?php 
            //  ../assets/php/security_question_controller.php
                $data = fetchUserQuestion($conn, $email);

                if($data) {
                    $question = $data['question'];
                    $answer = $data['answer'];

                    echo "$question"; 
                }
                else {
                    echo '<em>No security question has been set for this email</em>';
                }
                
                verifyAnswer($answer); 
            ?>
            </p>
    
            <div class="register_field_group">
                <label for="answer" class="register_label">Answer</label>
                <input type="text" id="answer" name="answer" class="register_input" required placeholder="Enter your answer">
           
                <button type="submit" class="register_button">Confirm</button>
            </div>



        </form>

</body>
</html>
