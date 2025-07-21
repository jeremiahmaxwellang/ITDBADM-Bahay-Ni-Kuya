<?php
// Database configuration
require_once('../includes/dbconfig.php');

// Get the property ID
$property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;
$property = null;

if ($property_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM properties WHERE property_id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $property = $result->fetch_assoc();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/view_details.css">
    <title>View Property Details</title>
    <script>
        // JavaScript function to show a popup if the property is sold
        function checkPropertyStatus() {
            var status = "<?php echo $property ? $property['offer_type'] : ''; ?>";
            if (status === 'Sold') {
                alert("Sorry, this property has already been sold.");
                return false; // Prevent form submission
            }
            return true; // Proceed with form submission
        }
    </script>
</head>
<body>
    <!-- Background gradient -->
    <div class="property-bg-gradient"></div>

    <!-- Header -->
    <header>
        <h1 class="site_header">Bahay Ni Kuya</h1>
        <p>Finding your dream home with us is easier than surviving Kuya’s weekly eviction!</p>
        <a href="shopping_cart.php" class="cart-button">
            <span>Cart 🛒</span>
            <span class="cart-count">0</span>
        </a>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a class="topNavBar" href="shopping_cart.php">Shopping Cart</a></li>
            <li><a class="topNavBar" href="checkout.php">Checkout Page</a></li>
            <li><a class="topNavBar" href="#">About Us</a></li>
            <li><a class="topNavBar" href="#">Contact</a></li>
            <li><a class="topNavBar" onclick="window.location.href='logout.php'">Sign Out</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <?php if ($property): ?>
            <!-- Back Button -->
            <a href="property_listing.php" class="backButton">Back</a>

            <!-- Big Blue Rectangle -->
            <div class="blueContainer">

                <!-- Left section: Image and info -->
                <div class="leftContainer">
                    <h1 class="propertyName">
                        <?php echo htmlspecialchars($property['property_name']); ?>
                    </h1>
                    <img src="../assets/images/<?php echo htmlspecialchars($property['photo']); ?>" alt="Property Image" style="width: 100%; border-radius: 0 0 10px 10px;">
                    <p class="propertyAddress"><?php echo htmlspecialchars($property['address']); ?></p>
                    <p class="propertyPrice">
                        ₱<?php echo number_format($property['price'], 0); ?>
                    </p>
                </div>

                <!-- Right section: Description -->
                <div class="rightContainer">
                    <h2 class="descriptionHeader">DESCRIPTION</h2>
                    <p class="descriptionBody"><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
                    <p class="propertyStatus">Status:
                        <?php
                        if (isset($property['offer_type'])) {
                            echo $property['offer_type'] === 'For Sale' ? 'For Sale' : 'Sold';
                        }
                        ?>
                    </p>

                    <form method="POST" action="add_to_cart.php" style="margin-top: 20px;" onsubmit="return checkPropertyStatus()">
                        <input type="hidden" name="property_id" value="<?php echo $property['property_id']; ?>">
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 12px 24px; border-radius: 50px; font-size: 18px; font-weight: bold; cursor: pointer;">
                            ADD TO CART
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <h2>Property not found.</h2>
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