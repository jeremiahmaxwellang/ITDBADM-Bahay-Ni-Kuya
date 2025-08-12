<?php
// Database configuration
require_once('../includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $photo = null;

    // Validate price: must be numeric and between 1 and 999,999,999
    $price = isset($_POST['price']) ? str_replace([',', ' '], '', $_POST['price']) : '';
    if ($price === '' || !is_numeric($price)) {
        echo "<script>alert('Error: Price must be a numeric value.'); window.location.href='admin.php';</script>";
        exit;
    }
    $price = (float)$price;
    if ($price < 100000 || $price > 999999999) {
        echo "<script>alert('Error: Price must be between 100000 and 999,000,000.'); window.location.href='admin.php';</script>";
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

    // Normalize and validate description length (max 1,500 chars)
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $maxDesc = 1500;

    // Names with accents/UTF-8 characters are counted correctly
    $descLen = function_exists('mb_strlen') ? mb_strlen($description, 'UTF-8') : strlen($description);

    if ($description === '') {
        echo "<script>alert('Description is required.'); window.location.href='admin.php';</script>";
        exit;
    }
    if ($descLen > $maxDesc) {
        echo "<script>alert('Description must not exceed 1,500 characters.'); window.location.href='admin.php';</script>";
        exit;
    }

    // Normalize and validate address length (max 75 chars)
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $maxAddress = 75;

    // Names with accents/UTF-8 characters are counted correctly
    $addressLen = function_exists('mb_strlen') ? mb_strlen($address, 'UTF-8') : strlen($address);

    if ($address === '') {
        echo "<script>alert('Address is required.'); window.location.href='admin.php';</script>";
        exit;
    }
    if ($addressLen > $maxAddress) {
        echo "<script>alert('Address must not exceed 75 characters.'); window.location.href='admin.php';</script>";
        exit;
    }
    

    // Photo upload
    // Code referenced: https://www.youtube.com/watch?v=6iERr1ADFz8
    if (!empty($_FILES['image']['name'])) {
        $file_name = $_FILES['image']['name'];
        $tempname = $_FILES['image']['tmp_name'];
        $folder = '../assets/images/'.$file_name;

        // Set photo column
        $photo = $folder;

        if(move_uploaded_file($tempname, $folder)){
            echo "<h2>File uploaded successfully</h2>";
        }
        else echo "<h2>File upload failed.</h2>";

    }

    // STORED PROCEDURE: sp_update_property
    $stmt = $conn->prepare("CALL sp_update_property(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $id, $name, $address, $price, $description, $photo);

    
    if ($stmt->execute()) {
        $_SESSION['admin_message'] = "Property updated successfully";
        $_SESSION['admin_message_type'] = "success";
    } else {
        $_SESSION['admin_message'] = "Error updating property: " . $conn->error;
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