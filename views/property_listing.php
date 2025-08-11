<?php
    session_start();

    // Check if user is logged in and is a customer
    include('../assets/php/authorization.php');
    customerAccess();

    // Database configuration
    require_once('../includes/dbconfig.php');
    include('../assets/php/property_listing_controller.php');

    // Get search input
    $search_location = isset($_GET['search_location']) ? trim($_GET['search_location']) : '';
    $price_filter = isset($_GET['price_filter']) ? $_GET['price_filter'] : '';

    // Count items in cart
    $cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

    // Check if 'properties' table exists
    $tableExists = false;
    $checkTable = $conn->query("SHOW TABLES LIKE 'properties'");
    if ($checkTable && $checkTable->num_rows > 0) {
        $tableExists = true;
    }

    $properties = [];
    if ($tableExists) {
        // Determine min and max price based on filter
        $min_price = 0;
        $max_price = 999999999; // effectively no upper limit

        if ($price_filter === '1') {
            $max_price = 5000000;
        } elseif ($price_filter === '2') {
            $min_price = 5000000;
            $max_price = 10000000;
        } elseif ($price_filter === '3') {
            $min_price = 10000000;
        }

        // Use stored procedure to search
        $stmt = $conn->prepare("CALL sp_search_properties(?, ?, ?)");
        $stmt->bind_param("sdd", $search_location, $min_price, $max_price);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $properties[] = $row;
            }
            $result->close();
        }

        $conn->next_result(); // Required to flush results after calling stored procedure
    } else {
        $properties = false; // Indicate table missing
    }
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
        <span class="cart-count"><?php echo $cartCount; ?></span>
        </a>
    </header>

    <nav>
        <ul>
            <li><a class="topNavBar" href="shopping_cart.php">Shopping Cart</a></li>
            <li><a class="topNavBar" href="checkout.php">Checkout Page</a></li>
            <li><a class="topNavBar" href="profile.php">View Profile</a></li>
            <li><a class="topNavBar" href="logout.php">Sign Out</a></li>
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
    
    <!-- Overlay for last login information -->
    <?php if ($showOverlay): ?>
        <div id="loginOverlay" class="overlay show-overlay">
            <div class="overlay-content">
                <h2>Last Login Information</h2>
                <p><?= htmlspecialchars($last_login) ?></p>
                <button onclick="closeOverlay()">Close</button>
            </div>
        </div>
    <?php endif; ?>

    <script>
        // Function to close the overlay
        function closeOverlay() {
            document.getElementById("loginOverlay").style.display = "none";
        }

        // Optional: add a click outside overlay functionality for better UX
        window.addEventListener('click', function (e) {
            var overlay = document.getElementById("loginOverlay");
            if (e.target === overlay) {
                closeOverlay();  // Close overlay if clicked outside the content box
            }
        });

        // Show overlay based on PHP session variable
        <?php if ($showOverlay): ?>
            document.getElementById("loginOverlay").style.display = "flex";
        <?php endif; ?>
    </script>

    <!-- Page Footer -->
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

    <script>
        // Print the value of session variable 'show_overlay' in the console
        <?php if (isset($_SESSION['show_overlay'])): ?>
            console.log("show_overlay: <?= $_SESSION['show_overlay'] ?>");
        <?php else: ?>
            console.log("show_overlay is not set.");
        <?php endif; ?>
    </script>
</body>
</html>