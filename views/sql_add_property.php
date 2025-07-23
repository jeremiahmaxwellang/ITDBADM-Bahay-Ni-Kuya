<?php
// Database configuration
require_once('../includes/dbconfig.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Upload photo
    // Code referenced: https://www.youtube.com/watch?v=JaRq73y5MJk
    if(isset($_POST['submit'])){
        $file = $_FILES['photo'];

        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];

        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        // File Extension
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        // Allowed file extensions
        $allowed = array('jpg', 'jpeg', 'png');

        if(in_array($fileActualExt, $allowed)){

            if($fileError == 0){ // if no errors
                $fileNameNew = uniqid('', true).".".$fileActualExt;

                // Set photo column
                $photo = 'uploads/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $photo);
            }

            else{
                echo "Error uploading file.";
            }
        }
        else{
            echo "You can only upload files of type: jpg, jpeg, or png";
        }
    
    }

    // Handle file upload if new image was uploaded
    // if (!empty($_FILES['image']['name'])) {
    //     $photo = $_FILES['image']['name'];

    //     $tempPath = $_FILES['image']['name'];
    //     $uploadPath = "../assets/images" . basename($photo);

    //     if(move_uploaded_file($tempPath, $uploadPath)){
            
    //     }

    //     else{
    //         $_SESSION['admin_message'] = "Error uploading photo.";
    //         $_SESSION['admin_message_type'] = "error";
    //     }
    // }
    
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