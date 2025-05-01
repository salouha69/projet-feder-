<?php
session_start();
require_once 'config.php'; 

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Incorrect email or password";
        }
    } catch(PDOException $e) {
        $error = "Connection error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffetto</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/styles.css" />
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
                        <a href="order2.php" class="nav__link">
                            <i class="ri-compass-3-fill"></i> <span>Shop</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="index.php#testimonial" class="nav__link">
                            <i class="ri-message-3-fill"></i> <span>Testimonial</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="login.php" class="nav__link active-link">
                            <i class="ri-user-fill"></i> <span>Login</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="login-main">
        <div class="login-container">
            <h1>Login</h1>
            <?php if (isset($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="button">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
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
                        <h3 class="footer__ zamówienia na newsletter</h3>
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