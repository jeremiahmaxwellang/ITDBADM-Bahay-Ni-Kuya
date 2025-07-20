<?php
// admin.php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'A') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bahay ni Kuya</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Admin-specific styles */
        .admin-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--MainYellow);
        }
        
        .admin-title {
            font-size: 42px;
            color: var(--MainBlue);
            font-family: var(--DefaultHeaderFont);
            letter-spacing: 1px;
        }
        
        .logout-btn {
            background-color: var(--MainRed);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-family: var(--DefaultFont);
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c11a1f;
        }
        
        .admin-tabs {
            display: flex;
            margin-bottom: 25px;
            border-bottom: 1px solid #ddd;
        }
        
        .tab-btn {
            padding: 12px 25px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-family: var(--DefaultFont);
            font-weight: bold;
            color: #555;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            border-bottom: 3px solid var(--MainBlue);
            color: var(--MainBlue);
        }
        
        .tab-btn:hover:not(.active) {
            color: var(--MainRed);
        }
        
        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .tab-content.active {
            display: block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: var(--MainBlue);
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        .action-btn {
            padding: 8px 12px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .edit-btn {
            background-color: var(--MainYellow);
            color: black;
        }
        
        .edit-btn:hover {
            background-color: #e6c900;
        }
        
        .delete-btn {
            background-color: var(--MainRed);
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #c11a1f;
        }
        
        .add-property-form {
            margin-top: 30px;
            padding: 25px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--MainBlue);
        }
        
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: var(--DefaultFont);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .submit-btn {
            background-color: var(--MainBlue);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #0360a8;
        }
        
        .status-available {
            color: #27ae60;
            font-weight: bold;
        }
        
        .status-occupied {
            color: var(--MainRed);
            font-weight: bold;
        }
        
        .status-maintenance {
            color: #f39c12;
            font-weight: bold;
        }
        
        .property-image-preview {
            max-width: 200px;
            max-height: 150px;
            display: block;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- Background gradient overlay -->
    <div class="property-bg-gradient"></div>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Admin Dashboard</h1>
            <button class="logout-btn" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
        
        <?php
        // Display success/error messages if they exist
        if (isset($_SESSION['admin_message'])) {
            $messageType = $_SESSION['admin_message_type'] ?? 'success';
            echo "<div class='alert alert-$messageType'>" . $_SESSION['admin_message'] . "</div>";
            unset($_SESSION['admin_message']);
            unset($_SESSION['admin_message_type']);
        }
        ?>
        
        <div class="admin-tabs">
            <button class="tab-btn active" onclick="openTab('properties')">
                <i class="fas fa-home"></i> Properties
            </button>
            <button class="tab-btn" onclick="openTab('orders')">
                <i class="fas fa-clipboard-list"></i> Orders
            </button>
            <button class="tab-btn" onclick="openTab('add-property')">
                <i class="fas fa-plus-circle"></i> Add Property
            </button>
        </div>
        
        <!-- Properties Tab -->
        <div id="properties" class="tab-content active">
            <h2><i class="fas fa-home"></i> Manage Properties</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "itmosys_db";
                    
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    
                    // Fetch properties
                    $sql = "SELECT * FROM properties";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $statusClass = 'status-' . strtolower($row['status']);
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td><img src='../uploads/{$row['image']}' alt='Property Image' style='width:80px;height:60px;object-fit:cover;'></td>
                                <td>{$row['name']}</td>
                                <td>{$row['type']}</td>
                                <td>₱" . number_format($row['price'], 2) . "</td>
                                <td class='$statusClass'>{$row['status']}</td>
                                <td>
                                    <button class='action-btn edit-btn' onclick='editProperty({$row['id']})'>
                                        <i class='fas fa-edit'></i> Edit
                                    </button>
                                    <button class='action-btn delete-btn' onclick='deleteProperty({$row['id']})'>
                                        <i class='fas fa-trash'></i> Delete
                                    </button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No properties found</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Orders Tab -->
        <div id="orders" class="tab-content">
            <h2><i class="fas fa-clipboard-list"></i> View Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Property</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    
                    // Fetch orders with property and customer details
                    $sql = "SELECT o.id, p.name as property_name, s.name as student_name, 
                            o.order_date, o.status, o.total_amount 
                            FROM orders o
                            JOIN properties p ON o.property_id = p.id
                            JOIN students s ON o.student_id = s.student_id
                            ORDER BY o.order_date DESC";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $statusClass = 'status-' . strtolower($row['status']);
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['property_name']}</td>
                                <td>{$row['student_name']}</td>
                                <td>" . date('M d, Y', strtotime($row['order_date'])) . "</td>
                                <td class='$statusClass'>{$row['status']}</td>
                                <td>₱" . number_format($row['total_amount'], 2) . "</td>
                                <td>
                                    <button class='action-btn edit-btn' onclick='viewOrderDetails({$row['id']})'>
                                        <i class='fas fa-eye'></i> View
                                    </button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No orders found</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Add Property Tab -->
        <div id="add-property" class="tab-content">
            <h2><i class="fas fa-plus-circle"></i> Add New Property</h2>
            <form class="add-property-form" action="add_property.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Property Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="type">Property Type:</label>
                    <select id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="Apartment">Apartment</option>
                        <option value="Dormitory">Dormitory</option>
                        <option value="House">House</option>
                        <option value="Room">Room</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (₱):</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>
                </div>
                
                <div class="form-group">
                    <label for="bedrooms">Bedrooms:</label>
                    <input type="number" id="bedrooms" name="bedrooms" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="bathrooms">Bathrooms:</label>
                    <input type="number" id="bathrooms" name="bathrooms" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="size">Size (sqm):</label>
                    <input type="number" id="size" name="size" step="0.1" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Property Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(this)">
                    <img id="imagePreview" class="property-image-preview" style="display:none;">
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Add Property
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function openTab(tabName) {
            // Hide all tab contents
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Remove active class from all tab buttons
            const tabButtons = document.getElementsByClassName('tab-btn');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            // Show the selected tab content and mark button as active
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
        
        function editProperty(id) {
            // Redirect to edit page with property ID
            window.location.href = 'edit_property.php?id=' + id;
        }
        
        function viewOrderDetails(id) {
            // Redirect to order details page
            window.location.href = 'order_details.php?id=' + id;
        }
        
        function deleteProperty(id) {
            if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
                // Send AJAX request to delete property
                fetch('delete_property.php?id=' + id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Property deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting property: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the property');
                });
            }
        }
        
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>