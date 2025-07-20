<?php
// update_property.php
    require_once('../includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    // Get all other fields
    
    // Handle file upload if new image was provided
    if (!empty($_FILES['image']['name'])) {
        // Your image upload logic here
    }
    
    // Update database
    $stmt = $conn->prepare("UPDATE properties SET property_name=?, type=?, ... WHERE property_id=?");
    $stmt->bind_param("ss...i", $name, $type, ..., $id);
    
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