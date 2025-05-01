<?php
// Fichier séparé pour le header
if (!isset($page_title)) {
    $page_title = "Coffetto";
}
?>
<header class="header" id="header">
    <div class="header__border"></div>
    <nav class="nav container">
        <a href="index.php" class="nav__logo">
            <img src="assets/img/logo.png" alt="Logo Coffetto">
            Coffetto
        </a>

        <div class="nav__menu">
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="index.php" class="nav__link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active-link' : '' ?>">
                        <i class="ri-home-5-fill"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <!-- Autres éléments de menu -->
                <?php if (is_logged_in()): ?>
                    <li class="nav__item">
                        <a href="logout.php" class="nav__link">
                            <i class="ri-logout-box-r-line"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav__item">
                        <a href="login.php" class="nav__link">
                            <i class="ri-user-fill"></i>
                            <span>Connexion</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>