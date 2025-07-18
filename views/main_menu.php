<?php
session_start();
include "../includes/dbconfig.php";

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch the student's name from the database
$student_id = $_SESSION['student_id'];
$student_name = "Housemates"; // Default name in case query fails

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Verify database is selected (this is done automatically when providing dbname in connection)
    if (!$conn->select_db($dbname)) {
        throw new Exception("Database selection failed: " . $conn->error);
    }

    $sql = "SELECT CONCAT(student_firstname, ' ', student_lastname) AS fullname FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_name = $row['fullname'];
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Handle errors gracefully
    error_log($e->getMessage());
    // Continue with default student name
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PROPERTY SYSTEM</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-image: url('../assets/images/pbb house.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    <div class="enrollment_container">
        <!-- Welcome header with the student's name -->
        <h2 class="title-header">Welcome, <?php echo htmlspecialchars($student_name); ?>!</h2>

        <!-- Line below the header -->
        <div class="separator"></div>

        <div class="enrollment-btn">
            <!-- Updated buttons for property system -->
            <button class="main-button" onclick="window.location.href='property_listing.php'">Property Listing Page</button>
            <button class="main-button" onclick="window.location.href='shopping_cart.php'">Shopping Cart</button>
            <button class="main-button" onclick="window.location.href='checkout.php'">Checkout Page</button>
        </div>
    </div>

    <!-- Logout button -->
    <i class="fas fa-sign-out-alt logout_icon" onclick="window.location.href='LogoutPage.php'" title="Logout" 
       style="font-size: 30px; position: absolute; bottom: 20px; right: 20px; color: #FFFFFF; cursor: pointer;"></i>

</body>
</html>