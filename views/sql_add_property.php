<?php
// Database configuration
require_once('../includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $price = $_POST['price'];

    // Validate price: must be numeric and between 1 and 999,999,999
    $price = isset($_POST['price']) ? str_replace([',', ' '], '', $_POST['price']) : '';
    if ($price === '' || !is_numeric($price)) {
        echo "<script>alert('Error: Price must be a numeric value.'); window.location.href='admin.php';</script>";
        exit;
    }
    $price = (float)$price;
    if ($price < 1 || $price > 999999999) {
        echo "<script>alert('Error: Price must be between 1 and 999,000,000.'); window.location.href='admin.php';</script>";
        exit;
    }

    // Normalize and validate property name length (max 200 chars)
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $maxLen = 200;

    // Names with accents/UTF-8 characters are counted correctly
    $len = function_exists('mb_strlen') ? mb_strlen($name, 'UTF-8') : strlen($name);

    if ($name === '') {
        echo "<script>alert('Property name is required.'); window.location.href='admin.php';</script>";
        exit;
    }
    if ($len > $maxLen) {
        echo "<script>alert('Property name must not exceed 200 characters.'); window.location.href='admin.php';</script>";
        exit;
    }

    $description = $_POST['description'];
        $photo = null;
    

    // Photo upload
    // Code referenced: https://www.youtube.com/watch?v=6iERr1ADFz8
    if (!empty($_FILES['image']['name'])) {
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = '../assets/images/'.$file_name;

        // Save file path to photo variable
        $photo = $folder;

        if(move_uploaded_file($tempname, $folder)){
            echo "<h2>File uploaded successfully</h2>";
        }
        else echo "<h2>File upload failed.</h2>";

    }
    
    // Insert new property
    // CALL PROCEDURE: sp_add_property
    $stmt = $conn->prepare("CALL sp_add_property(?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $address, $price, $description, $photo);

    if ($stmt->execute()) {
        $_SESSION['admin_message'] = "Property added successfully";
        $_SESSION['admin_message_type'] = "success";
    } else {
        $_SESSION['admin_message'] = "Error adding property: " . $conn->error;
        $_SESSION['admin_message_type'] = "error";
    }
    
    // Return JSON response for AJAX or redirect
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => $stmt->affected_rows > 0]);
        exit;
    } else {
        header("Location: admin.php");
        exit;
    }
}
?>
