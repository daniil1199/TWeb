<?php
session_start();

// Если нет данных заказа — редирект на форму
if (empty($_SESSION['order'])) {
    header('Location: contact.php');
    exit;
}

$order = $_SESSION['order'];
unset($_SESSION['order']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed – Vestes</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .result-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 64px;
        }
        .result-box { max-width: 520px; width: 100%; }
        .result-icon { font-size: 28px; text-align: center; margin-bottom: 24px; }
        .result-box h2 {
            font-size: 13px; letter-spacing: 4px;
            font-weight: 300; margin-bottom: 8px; text-align: center;
        }
        .result-box .sub {
            font-size: 11px; color: #888; letter-spacing: 2px;
            text-align: center; margin-bottom: 40px;
        }
        .summary {
            background: #f8f8f8; padding: 32px;
            border-left: 2px solid #000; margin-bottom: 36px;
        }
        .summary h3 { font-size: 10px; letter-spacing: 4px; margin-bottom: 20px; }
        .summary-row {
            display: flex; justify-content: space-between;
            font-size: 12px; letter-spacing: 1px;
            margin-bottom: 10px; color: #555;
        }
        .summary-row span:last-child { color: #000; font-weight: 500; }
        .order-id {
            text-align: center; font-size: 10px;
            letter-spacing: 3px; color: #aaa; margin-bottom: 32px;
        }
        .btn-home {
            display: block; text-align: center; padding: 14px;
            border: 1px solid #000; font-size: 10px; letter-spacing: 3px;
            text-decoration: none; color: #000;
            transition: background 0.3s, color 0.3s;
        }
        .btn-home:hover { background: #000; color: #fff; }
    </style>
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

<section class="result-section">
    <div class="result-box">
        <div class="result-icon">&#10022;</div>
        <h2>ORDER RECEIVED</h2>
        <p class="sub">
            Thank you, <?= htmlspecialchars($order['firstName']) ?>.
            We will contact you at <?= htmlspecialchars($order['email']) ?>.
        </p>
        <p class="order-id">ORDER #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></p>

        <div class="summary">
            <h3>ORDER SUMMARY</h3>
            <div class="summary-row">
                <span>NAME</span>
                <span><?= htmlspecialchars($order['firstName'] . ' ' . $order['lastName']) ?></span>
            </div>
            <div class="summary-row">
                <span>EMAIL</span>
                <span><?= htmlspecialchars($order['email']) ?></span>
            </div>
            <div class="summary-row">
                <span>PHONE</span>
                <span><?= htmlspecialchars($order['phone']) ?></span>
            </div>
            <div class="summary-row">
                <span>CATEGORY</span>
                <span><?= strtoupper(htmlspecialchars($order['category'])) ?></span>
            </div>
            <div class="summary-row">
                <span>SIZE</span>
                <span><?= htmlspecialchars($order['size']) ?></span>
            </div>
            <?php if (!empty($order['message'])): ?>
            <div class="summary-row">
                <span>NOTES</span>
                <span><?= htmlspecialchars($order['message']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <a href="index.php" class="btn-home">BACK TO HOME</a>
    </div>
</section>

<footer>
    © 2026 VESTES
    <div class="footer-contact">
        contact@vestes.com · Instagram @vestes
    </div>
</footer>

</body>
</html>
