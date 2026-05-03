<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shoes – Vestes</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>

<header>
    <div class="logo">VESTES</div>
    <nav>
        <ul class="menu">
            <li><a href="index.php">HOME</a></li>
            <li><a href="clothing.php">CLOTHING</a></li>
            <li><a href="shoes.php">SHOES</a></li>
            <li><a href="accessories.php">ACCESSORIES</a></li>
            <li><a href="contact.php">CONTACT</a></li>
        </ul>
    </nav>
</header>

<section class="section">
    <div class="products">

        <div class="product" style="background-image:url('images/shoes-1.jpg')">
            <div class="product-info">
                <h3>LEATHER BOOTS</h3>
                <p>$980</p>
            </div>
        </div>

        <div class="product" style="background-image:url('images/shoes-2.jpg')">
            <div class="product-info">
                <h3>MINIMAL SNEAKERS</h3>
                <p>$720</p>
            </div>
        </div>

        <div class="product" style="background-image:url('images/shoes-3.jpg')">
            <div class="product-info">
                <h3>SPORT RUNNERS</h3>
                <p>$640</p>
            </div>
        </div>

    </div>
</section>

<footer>
    © 2026 VESTES
    <div class="footer-contact">
        contact@vestes.com · Instagram @vestes
    </div>
</footer>

<script src="JS/effects.js"></script>

</body>
</html>
