<?php 
/*
    security_question_controller.php
    - backend for views/security_question.php
*/

    // Fetch premade security questions from the DB
    function fetchQuestions(&$conn) {
        $query = "SELECT * FROM security_questions";
        $result = $conn->query($query);

        if($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $question_id = (int) $row['question_id'];
                $question = htmlspecialchars($row['question'], ENT_QUOTES, 'UTF-8');
                echo "<option value=\"$question_id\">$question</option>";
            }

        }

        else {
            echo '<option disabled>No questions available</option>';
        }
    }

    // Updates users to have security questions
    function submitQuestions(&$conn) {

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect user input
            $question_id = isset($_POST['security_question']) ? (int) $_POST['security_question'] : 0;
            $answer = trim($_POST['answer']);
            $error = "";

            // If security question is selected
            if($question_id > 0 && !empty($answer)){
                // UPDATE Statement
                $stmt = $conn->prepare("UPDATE users SET question_id = ?, question_answer = ? WHERE email = ?");

                // Bind the inputs to the ?, ?, ?
                $stmt->bind_param("iss", $question_id, $answer, $_SESSION['user_email']);

                if($stmt->execute()){
                    $stmt->close();
                    $conn->close();
                    $success = "Account recovery all set!";

                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Error: " . $stmt->error;
                    $stmt->close();
                }

            } else {
                $error = "Please select a question and provide an answer.";
            }

            if(!empty($error)) {
                echo "<p class='error-message'>{$error}</p>";
            }

        }
    }
?>