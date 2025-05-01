<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch products for selection
try {
    $products = $db->query("SELECT * FROM products")->fetchAll();
} catch(PDOException $e) {
    $products = [];
    $products_error = "Unable to load products: " . $e->getMessage();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = sanitize($_POST['product_id']);
    $size = sanitize($_POST['size']);
    $milk = sanitize($_POST['milk']);
    $sweetness = sanitize($_POST['sweetness']);
    $extras = isset($_POST['extras']) ? implode(', ', array_map('sanitize', $_POST['extras'])) : 'None';
    $total_price = sanitize($_POST['total_price']);
    $user_id = $_SESSION['user_id'];
    
    try {
        $stmt = $db->prepare("INSERT INTO orders (user_id, product_id, size, milk_type, sweetness, extras, total_price) 
                             VALUES (:user_id, :product_id, :size, :milk, :sweetness, :extras, :total_price)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':product_id' => $product_id,
            ':size' => $size,
            ':milk' => $milk,
            ':sweetness' => $sweetness,
            ':extras' => $extras,
            ':total_price' => $total_price
        ]);
        
        $order_id = $db->lastInsertId();
        $success_message = "Your order #$order_id has been placed successfully!";
    } catch(PDOException $e) {
        $error_message = "Error saving order: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Coffee - Coffetto</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/order.css" />
</head>
<body>
    <header class="header" id="header">
        <div class="header__border"></div>
        <nav class="nav container">
            <a href="index.php" class="nav__logo">
                <img src="assets/img/logo.png" alt="logo" /> Coffetto
            </a>
            <div class="nav__menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="index.php" class="nav__link">
                            <i class="ri-home-5-fill"></i> <span>Home</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#about" class="nav__link">
                            <i class="ri-award-fill"></i> <span>About</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="order2.php" class="nav__link active-link">
                            <i class="ri-compass-3-fill"></i> <span>Shop</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#testimonial" class="nav__link">
                            <i class="ri-message-3-fill"></i> <span>Testimonial</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="logout.php" class="nav__link">
                                <i class="ri-logout-box-fill"></i> <span>Logout</span>
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="nav__link">
                                <i class="ri-user-fill"></i> <span>Login</span>
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="order-main">
        <form id="coffeeForm" method="POST">
            <h1>Order Your Coffee</h1>

            <?php if (isset($success_message)): ?>
                <div class="order-confirmation" id="orderConfirmation" style="display: block;">
                    <h2>Thank you for your order! 🎉</h2>
                    <img src="assets/img/coffee-cup.png" alt="Coffee Image" class="coffee-img" />
                    <p id="finalPrice"><?= htmlspecialchars($success_message) ?></p>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <?php if (isset($products_error)): ?>
                <div class="error-message"><?= htmlspecialchars($products_error) ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="product">Coffee Type:</label>
                <select id="product" name="product_id" required>
                    <option value="">Select coffee</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                            <?= htmlspecialchars($product['name']) ?> ($<?= number_format($product['price'], 2) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="size">Size:</label>
                <select id="size" name="size" required>
                    <option value="">Select size</option>
                    <option value="Small" data-price="2">Small ($2)</option>
                    <option value="Medium" data-price="3">Medium ($3)</option>
                    <option value="Large" data-price="4">Large ($4)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="milk">Milk Type:</label>
                <select id="milk" name="milk" required>
                    <option value="">Select milk</option>
                    <option value="No Milk" data-price="0">No Milk ($0)</option>
                    <option value="Almond Milk" data-price="1">Almond Milk ($1)</option>
                    <option value="Oat Milk" data-price="1">Oat Milk ($1)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="sweetness">Sweetness:</label>
                <select id="sweetness" name="sweetness" required>
                    <option value="">Select sweetness</option>
                    <option value="No Sugar" data-price="0">No Sugar ($0)</option>
                    <option value="Normal" data-price="0.5">Normal ($0.5)</option>
                    <option value="Extra Sweet" data-price="1">Extra Sweet ($1)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Extras:</label>
                <div class="extras">
                    <div>
                        <input type="checkbox" id="vanilla" name="extras[]" value="Vanilla Shot" data-price="1" />
                        <label for="vanilla">Vanilla Shot ($1)</label>
                    </div>
                    <div>
                        <input type="checkbox" id="caramel" name="extras[]" value="Caramel Shot" data-price="1.5" />
                        <label for="caramel">Caramel Shot ($1.5)</label>
                    </div>
                </div>
            </div>

            <div class="total-price" id="totalPrice">Total: $0.00</div>
            <input type="hidden" id="formTotalPrice" name="total_price" value="0.00" />

            <button type="submit" id="orderButton" disabled>Order Now</button>
        </form>
    </main>

    <footer class="footer">
        <div class="footer__bg">
            <img src="assets/img/coffee-beans-bg.png" alt="footer image" class="footer__bg-img" />
            <div class="footer__container container grid">
                <div class="footer__data grid">
                    <div>
                        <a href="index.php" class="footer__logo">
                            <img src="assets/img/logo.png" alt="logo" /> Coffetto
                        </a>
                        <h3 class="footer__title">Sign up for our newsletter</h3>
                    </div>
                    <form action="newsletter.php" method="POST" class="footer__form grid" id="newsletterForm">
                        <input type="email" name="email" placeholder="Enter e-mail address" class="footer__input" required />
                        <button class="button footer__button" type="submit">
                            Subscribe <i class="ri-arrow-right-s-line"></i>
                        </button>
                        <p class="footer__description">
                            We care about your data. Read our
                            <a href="#" class="footer__privacy">Privacy Policy</a>
                        </p>
                        <div id="newsletterMessage"></div>
                    </form>
                </div>
                <div class="footer__content grid">
                    <div class="footer__social">
                        <a href="https://www.facebook.com/" target="_blank" class="footer__social-link">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="https://www.instagram.com/" target="_blank" class="footer__social-link">
                            <i class="ri-instagram-fill"></i>
                        </a>
                        <a href="https://twitter.com/" target="_blank" class="footer__social-link">
                            <i class="ri-twitter-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <a href="#" class="scrollup" id="scroll-up">
        <i class="ri-arrow-up-line"></i>
    </a>

    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/order.js"></script>
    <script>
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('newsletter.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('newsletterMessage');
                messageDiv.innerHTML = data.message;
                messageDiv.style.color = data.success ? 'green' : 'red';
                if (data.success) this.reset();
            })
            .catch(error => {
                document.getElementById('newsletterMessage').innerHTML = 'An error occurred';
                document.getElementById('newsletterMessage').style.color = 'red';
            });
        });
    </script>
</body>
</html>