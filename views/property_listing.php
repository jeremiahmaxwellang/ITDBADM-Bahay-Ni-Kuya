<?php
// Database configuration
require_once('../includes/dbconfig.php');

// Get search input
$search_location = isset($_GET['search_location']) ? trim($_GET['search_location']) : '';
$price_filter = isset($_GET['price_filter']) ? $_GET['price_filter'] : '';

// Check if 'properties' table exists
$tableExists = false;
$checkTable = $conn->query("SHOW TABLES LIKE 'properties'");
if ($checkTable && $checkTable->num_rows > 0) {
    $tableExists = true;
}

$properties = [];
if ($tableExists) {
    // Build dynamic SQL query with filters
    $query = "SELECT * FROM properties WHERE 1";
    $params = [];
    $types = "";

    // Location filter
    if ($search_location !== '') {
        $query .= " AND address LIKE ?";
        $params[] = '%' . $search_location . '%';
        $types .= "s";
    }

    // Price filter
    if ($price_filter !== '') {
        if ($price_filter === '1') {
            $query .= " AND price < ?";
            $params[] = 5000000;
            $types .= "i";
        } elseif ($price_filter === '2') {
            $query .= " AND price BETWEEN ? AND ?";
            $params[] = 5000000;
            $params[] = 10000000;
            $types .= "ii";
        } elseif ($price_filter === '3') {
            $query .= " AND price > ?";
            $params[] = 10000000;
            $types .= "i";
        }
    }

    $query .= " ORDER BY property_id DESC";
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

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
        <span>Cart 🛒</span>
        <span class="cart-count">0</span>
        </a>
    </header>

    <nav>
        <ul>
            <li><a class="topNavBar" href="shopping_cart.php">Shopping Cart</a></li>
            <li><a class="topNavBar" href="checkout.php">Checkout Page</a></li>
            <li><a class="topNavBar" onclick="window.location.href='logout.php'">Sign Out</a></li>
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
                <div class="searchbar-section">
                        <form action="#" method="GET">
                    <input type="text" name="search_location" placeholder="Search by location." value="<?php echo htmlspecialchars($search_location); ?>">
                    <select name="price_filter">
                        <option value="">Any Price</option>
                        <option value="1" <?php if ($_GET['price_filter'] === '1') echo 'selected'; ?>>Under ₱5M</option>
                        <option value="2" <?php if ($_GET['price_filter'] === '2') echo 'selected'; ?>>₱5M - ₱10M</option>
                        <option value="3" <?php if ($_GET['price_filter'] === '3') echo 'selected'; ?>>Over ₱10M</option>
                    </select>
                    <button type="submit">Search</button>
                </form>
                </div>
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
                        <img src="<?php echo htmlspecialchars($property['photo']); ?>" alt="<?php echo htmlspecialchars($property['property_name']); ?>" class="property-image">
                        <div class="property-details">
                            <h3 class="property-title"><?php echo htmlspecialchars($property['property_name']); ?></h3>
                            <p class="property-location"><?php echo htmlspecialchars($property['address']); ?></p>
                            <p class="property-price">₱<?php echo number_format($property['price'], 2); ?></p>
                            <div class="property-features">
                                <!-- You may want to add dynamic features here if available -->
                                <span><?php echo htmlspecialchars($property['offer_type']); ?></span>
                            </div>
                            <a href="view_details.php?property_id=<?php echo $property['property_id']; ?>" class="view-details">View Details</a>
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