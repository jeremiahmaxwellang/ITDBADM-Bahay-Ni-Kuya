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
    <link rel="stylesheet" href="../assets/css/property_listing.css">
</head>
<body>
    <div class="property-bg-gradient"></div>
    <header>
        <h1 class="site_header">Bahay Ni Kuya</h1>
        <p>Finding your dream home with us is easier than surviving Kuya’s weekly eviction!</p>
        <a href="shopping_cart.php" class="cart-button">
        <span>Cart</span>
        <span class="cart-count">0</span>
        </a>
    </header>

    <nav>
        <ul>
            <li><a class="topNavBar" href="#">Home</a></li>
            <li><a class="topNavBar" href="#">Properties</a></li>
            <li><a class="topNavBar" href="#">About Us</a></li>
            <li><a class="topNavBar" href="#">Contact</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="search-bar">
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