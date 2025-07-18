<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itmosys_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $propertyId = (int)$_POST['remove_item'];
    if (isset($_SESSION['cart'][$propertyId])) {
        unset($_SESSION['cart'][$propertyId]);
    }
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart items details from database
$cartItems = [];
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    $cartIds = implode(',', array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT * FROM properties WHERE id IN ($cartIds)");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
            $totalPrice += $row['price'];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bahay Ni Kuya - Your Cart</title>
    <link rel="stylesheet" href="../assets/css/shopping_cart.css">
    <style>
        /* Floating Checkout Button - Always Visible */
        .floating-checkout {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
        }

        .floating-checkout-btn {
            background-color: <?php echo !empty($cartItems) ? '#2ecc71' : '#95a5a6'; ?>;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            cursor: <?php echo !empty($cartItems) ? 'pointer' : 'not-allowed'; ?>;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .floating-checkout-btn:hover {
            background-color: <?php echo !empty($cartItems) ? '#27ae60' : '#95a5a6'; ?>;
            transform: <?php echo !empty($cartItems) ? 'translateY(-3px)' : 'none'; ?>;
            box-shadow: <?php echo !empty($cartItems) ? '0 6px 20px rgba(0,0,0,0.3)' : '0 4px 15px rgba(0,0,0,0.2)'; ?>;
        }

        .cart-icon {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <header class="BahayniKuya-header">
        <h1>Bahay Ni Kuya</h1>
        <p>Your Property Cart</p>
    </header>

    <nav>
        <ul>
            <li><a class="navButton" href="property_listing.php">Browse Properties</a></li>
            <li><a class="navButton" href="checkout.php">Checkout</a></li>
            <li><a class="navButton" href="#">Contact Us</a></li>
        </ul>
    </nav>

    <div class="container">

        <?php if (!empty($cartItems)): ?>
            <div class="cart-items">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>" class="cart-item-image">
                        <div class="cart-item-details">
                            <h3 class="cart-item-title"><?php echo $item['title']; ?></h3>
                            <p class="cart-item-location"><?php echo $item['location']; ?></p>
                            <p class="cart-item-price">₱<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                        <form action="cart.php" method="post">
                            <input type="hidden" name="remove_item" value="<?php echo $item['id']; ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="cart-header">
                    <h2>Your Selected Properties</h2>
                    <p class="cart-count"><?php echo count($cartItems); ?> house<?php echo count($cartItems) !== 1 ? 's' : ''; ?> in cart</p>
                </div>

                <div class="cart-total">
                    Total: <span>₱<?php echo number_format($totalPrice, 2); ?></span>
                </div>
                <button class="checkout-btn" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <div class="cart-header">
                    <h2>Your Selected Properties</h2>
                    <p class="cart-count"><?php echo count($cartItems); ?> house<?php echo count($cartItems) !== 1 ? 's' : ''; ?> in cart</p>
                </div>

                <h3>Your cart is empty</h3>
                <p>Browse our properties to find your dream home</p>
                <a href="property_listing.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="floating-checkout">
    <button class="floating-checkout-btn" 
            onclick="<?php echo !empty($cartItems) ? 'window.location.href=\'checkout.php\'' : 'alert(\'Your cart is empty. Please add properties before checkout.\')'; ?>">
        <span class="cart-icon">🛒</span>
        <?php echo !empty($cartItems) ? 'Checkout (₱' . number_format($totalPrice, 2) . ')' : 'Cart Empty'; ?>
    </button>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Bahay Ni Kuya is a premier real estate service helping clients find their dream homes in the Philippines.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="property_listing.php">Properties</a>
                <a href="cart.php">Your Cart</a>
                <a href="#">Services</a>
                <a href="#">Contact</a>
            </div>
            
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p>123 Real Estate Ave, Makati</p>
                <p>Phone: (02) 8123 4567</p>
                <p>Email: info@bahaynikuya.ph</p>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; 2023 Bahay Ni Kuya. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>