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

        // =========== PASSWORD COMPLEXITY =============

        $invalid = 0;

        // If password is shorter than 8 characters
        if (strlen(($password)) < $minLength) {
            $invalid++;
        }

        // If password lacks uppercase letters
        if (!preg_match($uppercase_regex, $password)) {
            $invalid++;
        }

        // If password lacks lowercase letters
        if (!preg_match($lowercase_regex, $password)) {
            $invalid++;
        }

        // If password lacks digits from 0-9
        if (!preg_match($digit_regex, $password)) {
            $invalid++;
        }
        
        // If password lacks special characters
        if (!preg_match($specialchar_regex, $password)) {
            $invalid++;
        }

        if($invalid > 0) {
            $error = "Password must be at least $minLength characters long and contain:\n- at least one letter\n- at least one digit\n- at least one uppercase letter\n- and at least one symbol.";
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