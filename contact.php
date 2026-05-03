<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact – Vestes</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .form-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 64px;
        }
        .form-container {
            width: 100%;
            max-width: 560px;
        }
        .form-title {
            font-size: 11px;
            letter-spacing: 5px;
            font-weight: 400;
            margin-bottom: 48px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 28px;
            position: relative;
        }
        .form-group label {
            display: block;
            font-size: 10px;
            letter-spacing: 3px;
            margin-bottom: 8px;
            color: #888;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: none;
            border-bottom: 1px solid #d0d0d0;
            padding: 10px 0;
            font-size: 14px;
            letter-spacing: 1px;
            background: transparent;
            outline: none;
            font-family: inherit;
            color: #000;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus { border-bottom-color: #000; }
        .form-group textarea { resize: none; height: 80px; }
        .form-group select { appearance: none; cursor: pointer; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }

        .form-group input.field-valid,
        .form-group select.field-valid,
        .form-group textarea.field-valid { border-bottom-color: #2e7d32; }
        .form-group input.field-error,
        .form-group select.field-error,
        .form-group textarea.field-error { border-bottom-color: #cc0000; }

        .field-feedback {
            font-size: 10px;
            letter-spacing: 1px;
            margin-top: 6px;
            min-height: 16px;
        }
        .field-feedback.valid   { color: #2e7d32; }
        .field-feedback.error   { color: #cc0000; }
        .field-feedback.hint    { color: #888; }
        .field-feedback.loading { color: #aaa; }

        .spinner {
            display: inline-block;
            width: 10px; height: 10px;
            border: 1px solid #ccc;
            border-top-color: #555;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-right: 4px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #000;
            color: #fff;
            border: none;
            font-size: 11px;
            letter-spacing: 4px;
            cursor: pointer;
            margin-top: 16px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .btn-submit:hover { background: #333; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .char-counter {
            text-align: right;
            font-size: 10px;
            color: #aaa;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        /* Server-side errors */
        .server-errors {
            background: #fff5f5;
            border-left: 2px solid #cc0000;
            padding: 16px 20px;
            margin-bottom: 32px;
            font-size: 11px;
            letter-spacing: 1px;
            color: #cc0000;
        }
        .server-errors ul { list-style: none; }
        .server-errors ul li { margin-bottom: 6px; }
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

<section class="form-section">
    <div class="form-container">
        <h2 class="form-title">PLACE AN ORDER</h2>

        <?php if (!empty($_SESSION['errors'])): ?>
        <div class="server-errors">
            <ul>
                <?php foreach ($_SESSION['errors'] as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form id="order-form" method="POST" action="includes/process.php" novalidate>

            <?php $old = $_SESSION['old_data'] ?? []; unset($_SESSION['old_data']); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">FIRST NAME</label>
                    <input type="text" id="firstName" name="firstName"
                           placeholder="John"
                           value="<?= htmlspecialchars($old['firstName'] ?? '') ?>">
                    <div class="field-feedback hint" id="firstName-fb">Enter your first name.</div>
                </div>
                <div class="form-group">
                    <label for="lastName">LAST NAME</label>
                    <input type="text" id="lastName" name="lastName"
                           placeholder="Doe"
                           value="<?= htmlspecialchars($old['lastName'] ?? '') ?>">
                    <div class="field-feedback hint" id="lastName-fb">Enter your last name.</div>
                </div>
            </div>

            <div class="form-group">
                <label for="email">EMAIL ADDRESS</label>
                <input type="email" id="email" name="email"
                       placeholder="john@example.com"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <div class="field-feedback hint" id="email-fb">Enter a valid email address.</div>
            </div>

            <div class="form-group">
                <label for="phone">PHONE NUMBER</label>
                <input type="tel" id="phone" name="phone"
                       placeholder="+373 xx xxx xxxx"
                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                <div class="field-feedback hint" id="phone-fb">Enter your phone number.</div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">CATEGORY</label>
                    <select id="category" name="category">
                        <option value="">— SELECT —</option>
                        <option value="clothing"     <?= ($old['category'] ?? '') === 'clothing'     ? 'selected' : '' ?>>Clothing</option>
                        <option value="shoes"        <?= ($old['category'] ?? '') === 'shoes'        ? 'selected' : '' ?>>Shoes</option>
                        <option value="accessories"  <?= ($old['category'] ?? '') === 'accessories'  ? 'selected' : '' ?>>Accessories</option>
                    </select>
                    <div class="field-feedback hint" id="category-fb">Select a product category.</div>
                </div>
                <div class="form-group">
                    <label for="size">SIZE</label>
                    <select id="size" name="size">
                        <option value="">— SELECT —</option>
                        <option value="XS" <?= ($old['size'] ?? '') === 'XS' ? 'selected' : '' ?>>XS</option>
                        <option value="S"  <?= ($old['size'] ?? '') === 'S'  ? 'selected' : '' ?>>S</option>
                        <option value="M"  <?= ($old['size'] ?? '') === 'M'  ? 'selected' : '' ?>>M</option>
                        <option value="L"  <?= ($old['size'] ?? '') === 'L'  ? 'selected' : '' ?>>L</option>
                        <option value="XL" <?= ($old['size'] ?? '') === 'XL' ? 'selected' : '' ?>>XL</option>
                    </select>
                    <div class="field-feedback hint" id="size-fb">Select your size.</div>
                </div>
            </div>

            <div class="form-group">
                <label for="message">ADDITIONAL NOTES</label>
                <textarea id="message" name="message"
                          placeholder="Special requests, color preferences..."><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                <div class="char-counter"><span id="char-count">0</span> / 200</div>
                <div class="field-feedback hint" id="message-fb">Optional. Max 200 characters.</div>
            </div>

            <button type="submit" class="btn-submit">SUBMIT ORDER</button>

        </form>
    </div>
</section>

<footer>
    © 2026 VESTES
    <div class="footer-contact">
        contact@vestes.com · Instagram @vestes
    </div>
</footer>

<script>
    // ── AJAX validation — Lab 5 ──
    const VALIDATE_URL = 'includes/validate.php';

    const fieldValid = {
        firstName: false, lastName: false,
        email: false, phone: false,
        category: false, size: false,
        message: true
    };

    function showFeedback(fieldId, state, text) {
        const fb    = document.getElementById(fieldId + '-fb');
        const input = document.getElementById(fieldId);
        if (!fb || !input) return;

        input.classList.remove('field-valid', 'field-error');
        fb.classList.remove('valid', 'error', 'hint', 'loading');

        if (state === 'loading') {
            fb.classList.add('loading');
            fb.innerHTML = '<span class="spinner"></span> Checking...';
        } else if (state === 'valid') {
            input.classList.add('field-valid');
            fb.classList.add('valid');
            fb.innerHTML = '&#10003; ' + text;
            fieldValid[fieldId] = true;
        } else if (state === 'error') {
            input.classList.add('field-error');
            fb.classList.add('error');
            fb.innerHTML = '&#10007; ' + text;
            fieldValid[fieldId] = false;
        } else {
            fb.classList.add('hint');
            fb.textContent = text;
        }
    }

    function validateFieldAjax(fieldId, value) {
        showFeedback(fieldId, 'loading', '');
        const body = 'field=' + encodeURIComponent(fieldId) + '&value=' + encodeURIComponent(value);
        fetch(VALIDATE_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        })
        .then(r => r.json())
        .then(data => {
            if (data.valid) {
                showFeedback(fieldId, 'valid', data.message);
            } else {
                const msg = data.message + (data.hint ? ' <span style="color:#aaa;font-size:9px;">— ' + data.hint + '</span>' : '');
                showFeedback(fieldId, 'error', msg);
            }
        })
        .catch(() => showFeedback(fieldId, 'hint', 'Could not reach server.'));
    }

    // Text fields
    ['firstName', 'lastName', 'email', 'phone'].forEach(function(id) {
        const el = document.getElementById(id);
        el.addEventListener('blur', function() {
            if (this.value.trim()) validateFieldAjax(id, this.value);
        });
        el.addEventListener('input', function() {
            if (el.classList.contains('field-error') && this.value.trim()) {
                clearTimeout(el._t);
                el._t = setTimeout(() => validateFieldAjax(id, el.value), 600);
            }
        });
    });

    // Select fields
    ['category', 'size'].forEach(function(id) {
        document.getElementById(id).addEventListener('change', function() {
            validateFieldAjax(id, this.value);
        });
    });

    // Textarea
    const textarea  = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    textarea.addEventListener('input', function() {
        if (this.value.length > 200) this.value = this.value.substring(0, 200);
        const len = this.value.length;
        charCount.textContent = len;
        charCount.style.color = len >= 180 ? '#cc0000' : '#aaa';
    });
    textarea.addEventListener('blur', function() {
        validateFieldAjax('message', this.value);
    });

    // Submit
    document.getElementById('order-form').addEventListener('submit', function(e) {
        const required = ['firstName', 'lastName', 'email', 'phone', 'category', 'size'];
        const allValid = required.every(id => fieldValid[id]);
        if (!allValid) {
            e.preventDefault();
            required.forEach(id => {
                if (!fieldValid[id]) {
                    const el = document.getElementById(id);
                    validateFieldAjax(id, el.value);
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    });
</script>

</body>
</html>
