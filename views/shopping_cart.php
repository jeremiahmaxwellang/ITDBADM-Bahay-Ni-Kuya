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
            padding-bottom: 80px; /* Added padding for floating button */
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        nav {
            background-color: #34495e;
            padding: 10px 0;
        }

        nav ul {
            display: flex;
            justify-content: center;
            list-style: none;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .cart-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .cart-header h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .cart-count {
            color: #7f8c8d;
            font-size: 16px;
        }

        .cart-items {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-image {
            width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
        }

        .cart-item-details {
            flex-grow: 1;
        }

        .cart-item-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .cart-item-location {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .cart-item-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
        }

        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 20px;
        }

        .remove-btn:hover {
            background-color: #c0392b;
        }

        .cart-summary {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            padding: 20px;
            text-align: right;
        }

        .cart-total {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .cart-total span {
            color: #e74c3c;
            font-weight: bold;
        }

        .checkout-btn {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background-color: #27ae60;
        }

        .empty-cart {
            text-align: center;
            padding: 50px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .continue-shopping {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .continue-shopping:hover {
            background-color: #2980b9;
        }

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
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .cart-item-image {
                margin-bottom: 15px;
                width: 100%;
                height: auto;
                max-height: 200px;
            }
            
            .remove-btn {
                margin-left: 0;
                margin-top: 15px;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .floating-checkout-btn {
                padding: 12px 25px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Bahay Ni Kuya</h1>
        <p>Your Property Cart</p>
    </header>

    <nav>
        <ul>
            <li><a href="property_listing.php">Browse Properties</a></li>
            <li><a href="checkout.php">Checkout</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="cart-header">
            <h2>Your Selected Properties</h2>
            <p class="cart-count"><?php echo count($cartItems); ?> property<?php echo count($cartItems) !== 1 ? 's' : ''; ?> in cart</p>
        </div>

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
                <div class="cart-total">
                    Total: <span>₱<?php echo number_format($totalPrice, 2); ?></span>
                </div>
                <button class="checkout-btn" onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
            </div>
        <?php else: ?>
            <div class="empty-cart">
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