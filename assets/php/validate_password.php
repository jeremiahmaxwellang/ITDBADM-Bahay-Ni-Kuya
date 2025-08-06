<?php
/*
    INPUT VALIDATION OF PASSWORDS
    Included in: views/register.php
*/
    $minLength = 8;

    // Regex for password complexity
    $uppercase_regex = '/\p{Lu}/u'; // Uppercase letters
    $lowercase_regex = '/\p{Ll}/u'; // Lowercase letters
    $digit_regex = '/\d/'; // Base 10 digits
    $specialchar_regex  = '/[-!"#$%&()*<>\/:;?@\[\]^_`{|}~+<>]/'; // Special characters 

    // Checks validity of password
    function passwordIsValid($password, $confirm_password, &$error) { // reference &$error to change the msg
        global $uppercase_regex, $lowercase_regex, $digit_regex, $specialchar_regex, $minLength;

        // If either [Password] or [Confirm Password] is empty
        if (empty($password) || empty($confirm_password)) {
            $error = "Password fields cannot be empty.";
            return false;
        }

        // If password is shorter than 8 characters
        if (strlen(($password)) < $minLength) {
            $error = "Password must be at least $minLength characters long";
            return false;
        }

        // =========== PASSWORD COMPLEXITY =============

        $error = "Password must contain at least one ";
        $invalid = 0;

        // If password lacks uppercase letters
        if (!preg_match($uppercase_regex, $password)) {
            $error = $error . "uppercase letter";
            $invalid++;
        }

        // If password lacks lowercase letters
        if (!preg_match($lowercase_regex, $password)) {
            // $error = "Password must contain at least one lowercase letter.";
            if($invalid > 0){
                $error = $error . ", ";
            }
            $error = $error . "lowercase letter";
            $invalid++;
        }

        // If password lacks digits from 0-9
        if (!preg_match($digit_regex, $password)) {

            if($invalid > 0){
                $error = $error . ", ";
            }

            $error = $error . "digit from 0 to 9";
            $invalid++;
        }
        
        // If password lacks special characters
        if (!preg_match($specialchar_regex, $password)) {

            if($invalid > 0){
                $error = $error . ", ";
            }

            $error = $error . "special character.";

            $invalid++;
        }

        if($invalid > 0) {
            return false;
        }

        // If either [Password] and [Confirm Password] do not match
        if($password != $confirm_password) {
            $error = "Passwords do not match";
            return false;
        }

        // Password is completely VALID
        return true;

    }
?>