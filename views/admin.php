<?php
// Database configuration
require_once('../includes/dbconfig.php');

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
    <link rel="stylesheet" href="../assets/css/admin.css">
   
    <script src="../assets/js/delete_property.js"></script>
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
            <button class="tab-btn active" onclick="openTab('properties', event)">
                <i class="fas fa-home"></i> Properties
            </button>
            <button class="tab-btn" onclick="openTab('orders', event)">
                <i class="fas fa-clipboard-list"></i> Orders
            </button>
            <button class="tab-btn" onclick="openTab('add-property', event)">
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
                    // Fetch properties
                    $sql = "SELECT 
                        property_id as id, 
                        property_name as name, 
                        offer_type as status, 
                        price, 
                        photo as image,
                        'Property' as type,  
                        address as location
                        FROM bahaynikuya_db.properties";
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
                    $sql = "SELECT 
                        o.order_id as id, 
                        p.property_name, 
                        u.first_name, 
                        u.last_name,
                        o.order_date, 
                        'Completed' as status,  // Placeholder since no status column exists
                        o.total_amount 
                        FROM bahaynikuya_db.orders o
                        JOIN bahaynikuya_db.properties p ON o.property_id = p.property_id
                        JOIN bahaynikuya_db.users u ON o.email = u.email
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
            <form class="add-property-form" action="admin_add_property.php" method="POST" enctype="multipart/form-data">
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
        function openTab(tabName, event) {
    // Prevent default behavior if event is provided
    if (event) {
        event.preventDefault();
    }
    
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
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }
}

// Initialize the first tab as active on page load
document.addEventListener('DOMContentLoaded', function() {
    openTab('properties');
});
        
        function editProperty(id) {
    // Show loading state
    document.getElementById('editPropertyContent').innerHTML = '<p>Loading...</p>';
    
    // Add modal-open class to body
    document.body.classList.add('modal-open');
    
    // Show modal
    document.getElementById('editPropertyModal').style.display = 'block';
    
    // Load edit form via AJAX
    fetch('edit_property.php?id=' + id)
        .then(response => response.text())
        .then(data => {
            document.getElementById('editPropertyContent').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('editPropertyContent').innerHTML = 
                '<div class="alert alert-error">Error loading form: ' + error + '</div>';
        });
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.classList.remove('modal-open');
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.classList.remove('modal-open');
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
    <!-- Edit Property Modal -->
<div id="editPropertyModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal('editPropertyModal')">&times;</span>
        <h2><i class="fas fa-edit"></i> Edit Property</h2>
        <div id="editPropertyContent">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>
</body>
</html>