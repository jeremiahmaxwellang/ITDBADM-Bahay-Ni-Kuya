<?php
// admin.php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
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
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        
        .admin-title {
            font-size: 28px;
            color: #0476D0;
            font-family: 'Bebas Neue', sans-serif;
        }
        
        .logout-btn {
            background-color: #ff3333;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Lato', sans-serif;
        }
        
        .logout-btn:hover {
            background-color: #cc0000;
        }
        
        .admin-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-family: 'Lato', sans-serif;
            font-weight: bold;
            color: #555;
        }
        
        .tab-btn.active {
            border-bottom: 3px solid #0476D0;
            color: #0476D0;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-btn {
            padding: 5px 10px;
            margin: 0 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .edit-btn {
            background-color: #FFDF00;
            color: black;
        }
        
        .delete-btn {
            background-color: #ff3333;
            color: white;
        }
        
        .add-property-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .submit-btn {
            background-color: #0476D0;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Admin Dashboard</h1>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
        </div>
        
        <div class="admin-tabs">
            <button class="tab-btn active" onclick="openTab('properties')">Properties</button>
            <button class="tab-btn" onclick="openTab('orders')">Orders</button>
            <button class="tab-btn" onclick="openTab('add-property')">Add Property</button>
        </div>
        
        <!-- Properties Tab -->
        <div id="properties" class="tab-content active">
            <h2>Manage Properties</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
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
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['type']}</td>
                                <td>₱" . number_format($row['price'], 2) . "</td>
                                <td>{$row['status']}</td>
                                <td>
                                    <button class='action-btn edit-btn' onclick='editProperty({$row['id']})'>Edit</button>
                                    <button class='action-btn delete-btn' onclick='deleteProperty({$row['id']})'>Delete</button>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No properties found</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Orders Tab -->
        <div id="orders" class="tab-content">
            <h2>View Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Property</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
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
                            JOIN students s ON o.student_id = s.student_id";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['property_name']}</td>
                                <td>{$row['student_name']}</td>
                                <td>{$row['order_date']}</td>
                                <td>{$row['status']}</td>
                                <td>₱" . number_format($row['total_amount'], 2) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No orders found</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Add Property Tab -->
        <div id="add-property" class="tab-content">
            <h2>Add New Property</h2>
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
                    <input type="number" id="price" name="price" step="0.01" required>
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
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Property Image:</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                
                <button type="submit" class="submit-btn">Add Property</button>
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
        
        function deleteProperty(id) {
            if (confirm('Are you sure you want to delete this property?')) {
                // Send AJAX request to delete property
                fetch('delete_property.php?id=' + id, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Property deleted successfully');
                        location.reload();
                    } else {
                        alert('Error deleting property: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the property');
                });
            }
        }
    </script>
</body>
</html>