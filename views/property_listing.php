<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change to your MySQL username
$password = ""; // Change to your MySQL password
$dbname = "itmosys_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'properties' table exists
$tableExists = false;
$checkTable = $conn->query("SHOW TABLES LIKE 'properties'");
if ($checkTable && $checkTable->num_rows > 0) {
    $tableExists = true;
}

$properties = [];
if ($tableExists) {
    $result = $conn->query("SELECT * FROM properties ORDER BY created_at DESC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }
    }
} else {
    $properties = false; // Indicate table missing
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Properties - Find Your Dream Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
        }

        nav {
            background-color: #34495e;
            padding: 10px 0;
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
            align-items: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .cart-button {
            position: absolute;
            right: 20px;
            top: 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cart-button:hover {
            background-color: #c0392b;
        }

        .cart-count {
            background-color: white;
            color: #e74c3c;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .search-bar {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-bar form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-bar input, .search-bar select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex-grow: 1;
            min-width: 200px;
        }

        .search-bar button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .properties-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .properties-header h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .properties-count {
            color: #7f8c8d;
            font-size: 16px;
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .property-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .property-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .property-details {
            padding: 15px;
        }

        .property-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .property-location {
            color: #7f8c8d;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .property-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
        }

        .property-features {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .view-details {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .view-details:hover {
            background-color: #1a252f;
        }

        .no-properties {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px 0;
            margin-top: 40px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            text-align: left;
            padding: 0 20px;
        }

        .footer-section h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .footer-section p, .footer-section a {
            color: #ecf0f1;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }

        .copyright {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #4a6278;
        }

        @media (max-width: 768px) {
            .properties-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .properties-grid {
                grid-template-columns: 1fr;
            }
            
            .search-bar form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bahay Ni Kuya</h1>
        <p>Gusto ko sumabog, magsabi ng mga masasamang words</p>
        <a href="shopping_cart.php" class="cart-button">
        <span>Cart</span>
        <span class="cart-count">0</span>
        </a>
    </header>

    <nav>
        <ul>
            <li><a href="shopping_cart.php">Shopping Cart</a></li>
            <li><a href="checkout.php">Checkout Page</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="search-bar">
            <form action="#" method="GET">
                <input type="text" placeholder="Search by location...">
                <select>
                    <option value="">Any Price</option>
                    <option value="1">Under ₱5M</option>
                    <option value="2">₱5M - ₱10M</option>
                    <option value="3">Over ₱10M</option>
                </select>
                <select>
                    <option value="">Any Type</option>
                    <option value="house">House</option>
                    <option value="condo">Condominium</option>
                    <option value="land">Land</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="properties-header">
            <h2>Featured Properties</h2>
            <p class="properties-count">
                <?php
                if ($properties === false) {
                    echo "Table 'properties' does not exist";
                } else {
                    echo count($properties) . " properties available";
                }
                ?>
            </p>
        </div>

        <?php if ($properties === false): ?>
            <div class="no-properties">
                <h3>Error: Table 'properties' does not exist in the database.</h3>
                <p>Please contact the administrator to set up the required database table.</p>
            </div>
        <?php elseif (count($properties) > 0): ?>
            <div class="properties-grid">
                <?php foreach ($properties as $property): ?>
                    <div class="property-card">
                        <img src="<?php echo $property['image_path']; ?>" alt="<?php echo $property['title']; ?>" class="property-image">
                        <div class="property-details">
                            <h3 class="property-title"><?php echo $property['title']; ?></h3>
                            <p class="property-location"><?php echo $property['location']; ?></p>
                            <p class="property-price">₱<?php echo number_format($property['price'], 2); ?></p>
                            <div class="property-features">
                                <span>3 Bedrooms</span>
                                <span>2 Bathrooms</span>
                                <span>150 sqm</span>
                            </div>
                            <a href="#" class="view-details">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-properties">
                <h3>No properties available at the moment</h3>
                <p>Please check back later or contact us for more information.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Prime Properties is the leading real estate agency in the Philippines, helping clients find their dream homes since 2010.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="#">Home</a>
                <a href="#">Properties</a>
                <a href="#">Services</a>
                <a href="#">Contact</a>
            </div>
            
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p>123 Real Estate Ave, Makati</p>
                <p>Phone: (02) 8123 4567</p>
                <p>Email: info@primeproperties.ph</p>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; 2023 Prime Properties. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>