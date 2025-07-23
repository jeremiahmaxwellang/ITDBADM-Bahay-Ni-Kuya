<?php
// Database configuration
require_once('../includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $description = $_POST['description'];
        $photo = null;
    

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
    
    // Insert new property
    //TODO: CALL sp_add_property stored procedure
    $stmt = $conn->prepare("CALL sp_add_property(?, ?, ?, ?, ?)");
    
    // Did not include photo due to SQL error
    // $stmt = $conn->prepare("INSERT INTO properties(property_name, address, price, description, offer_type, photo) VALUES (?, ?, ?, ?, 'For Sale', ?)");
    $stmt->bind_param("ssdss", $name, $address, $price, $description, $photo);


    // Without photo
    // $stmt = $conn->prepare("INSERT INTO properties(property_name, address, price, description, offer_type) VALUES (?, ?, ?, ?, 'For Sale')");
    // $stmt->bind_param("ssds", $name, $address, $price, $description);

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