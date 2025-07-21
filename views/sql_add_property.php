<?php
// Database configuration
require_once('../includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Handle file upload if new image was provided
    if (!empty($_FILES['image']['name'])) {
        $photo = $_FILES['image']['name'];
        $tempPath = $_FILES['image']['name'];
        $uploadPath = "../assets/images" . basename($photo);

        if(move_uploaded_file($tempPath, $uploadPath)){
            
        }

        else{
            $_SESSION['admin_message'] = "Error uploading photo.";
            $_SESSION['admin_message_type'] = "error";
        }
    }
    
    // Insert new property
    $stmt = $conn->prepare("INSERT INTO properties(property_name, address, price, description, offer_type) VALUES (?, ?, ?, ?, 'For Sale')");

    $stmt->bind_param("ssds", $name, $address, $price, $description);
    
    // Did not include photo due to SQL error    
    // $stmt = $conn->prepare("INSERT INTO properties(property_name, address, price, description, offer_type, photo) VALUES (?, ?, ?, ?, 'For Sale', ?)");

    // $stmt->bind_param("ssdss", $name, $address, $price, $description, $imagePath);

    if ($stmt->execute()) {
        $_SESSION['admin_message'] = "Property added successfully";
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