<?php
session_start();
require_once 'config.php';

// Fetch products
try {
    $products = $db->query("SELECT * FROM products LIMIT 3")->fetchAll();
} catch(PDOException $e) {
    $products = [];
    $products_error = "Unable to load products: " . $e->getMessage();
}

// Fetch testimonials
try {
    $testimonials = $db->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3")->fetchAll();
} catch(PDOException $e) {
    $testimonials = [];
    $testimonials_error = "Unable to load testimonials: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffetto - Coffee Shop</title>
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
                        <a href="index.php" class="nav__link active-link">
                            <i class="ri-home-5-fill"></i> <span>Home</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#about" class="nav__link">
                            <i class="ri-award-fill"></i> <span>About</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="order2.php" class="nav__link">
                            <i class="ri-compass-3-fill"></i> <span>Shop</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="#testimonial" class="nav__link">
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

    <main class="main">
        <section class="home section" id="home">
            <div class="home__container container grid">
                <div class="home__data">
                    <h3 class="home__subtitle">EXCEPTIONAL QUALITY</h3>
                    <h1 class="home__title">
                        It's time for a <br /> good coffee
                        <img src="assets/img/home-coffee-title.png" alt="home image" />
                    </h1>
                    <p class="home__description">
                        Each select coffee bean reflects our commitment to Tunisian coffee
                        growers, who bring the best select coffee to your table.
                    </p>
                    <a href="order2.php" class="button">
                        Get Started <i class="ri-arrow-right-s-line"></i>
                    </a>
                </div>
                <img src="assets/img/home-coffee.png" alt="home image" class="home__img" />
            </div>
        </section>

        <section class="products">
            <div class="products__bg section">
                <div class="products__container container grid">
                    <div class="products__data">
                        <a href="#about" class="products__button">
                            Scroll Down <i class="ri-arrow-down-s-line"></i>
                        </a>
                        <p class="products__description">
                            We strive to form deep partnerships with farmers from Borj
                            Sedria to create perspective together and form healthy working
                            relationships built on trust and respect.
                        </p>
                        <?php if (isset($products_error)): ?>
                            <div class="error-message"><?= $products_error ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="products__content">
                        <?php foreach ($products as $product): ?>
                            <article class="products__card">
                                <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="products__img">
                                <h3 class="products__name"><?= htmlspecialchars($product['name']) ?></h3>
                                <span class="products__price">$<?= number_format($product['price'], 2) ?></span>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="about" id="about">
            <div class="about__bg section">
                <div class="about__container container grid">
                    <div class="about__data">
                        <h2 class="section__title">Our History</h2>
                        <p class="about__description">
                            We make and grow the best coffee in Ariana Soghra, with
                            professional workers who harvest, collect and select the coffee
                            with quality work, thus providing exquisite coffee to enjoy
                            together as a family.
                        </p>
                    </div>
                    <img src="assets/img/about-coffee.png" alt="about image" class="about__img" />
                </div>
            </div>
        </section>

        <section class="steps" id="steps">
            <div class="steps__bg section">
                <h2 class="section__title">Steps of manufacturing our products</h2>
                <div class="steps__container container grid">
                    <img src="assets/img/coffee-beans-bg.png" alt="steps image" class="steps__bg-img" />
                    <div class="steps__content">
                        <img src="assets/img/steps-curve-line.svg" alt="steps image" class="steps__border" />
                        <div class="steps__card">
                            <div class="steps__circle">
                                <div class="steps__subcircle">01</div>
                                <img src="assets/img/steps-green-coffee.png" alt="steps image" class="steps__img" />
                            </div>
                            <p class="steps__description">
                                Harvest occurs annually when the coffee beans reach maturity
                                and are collected for processing.
                            </p>
                        </div>
                        <div class="steps__card steps__card-move">
                            <div class="steps__circle">
                                <div class="steps__subcircle">02</div>
                                <img src="assets/img/steps-coffee-beans.png" alt="steps image" class="steps__img" />
                            </div>
                            <p class="steps__description">
                                The beans are dried using a wet or dry technique, depending on
                                the taste we want to obtain.
                            </p>
                        </div>
                        <div class="steps__card">
                            <div class="steps__circle">
                                <div class="steps__subcircle">03</div>
                                <img src="assets/img/steps-ground-coffee.png" alt="steps image" class="steps__img" />
                            </div>
                            <p class="steps__description">
                                The coffee is roasted and acquires its flavor by processing
                                the grain in ovens.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonial" id="testimonial">
            <div class="testimonial__bg section">
                <div class="testimonial__container container grid">
                    <div class="testimonial__data">
                        <?php if (isset($testimonials_error)): ?>
                            <div class="error-message"><?= $testimonials_error ?></div>
                        <?php endif; ?>
                        <?php foreach ($testimonials as $index => $testimonial): ?>
                            <div class="testimonial__item <?= $index === 0 ? 'active' : '' ?>">
                                <h2 class="section__title"><?= htmlspecialchars($testimonial['content']) ?></h2>
                                <span class="testimonial__name"><?= htmlspecialchars($testimonial['author']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <img src="assets/img/testimonial-coffee.png" alt="testimonial image" class="testimonial__img" />
                </div>
            </div>
        </section>
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