<?php
session_start();

// Если уже залогинен — редирект на панель
if (!empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/db.php';

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login – Vestes</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .login-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .login-box {
            width: 100%;
            max-width: 360px;
        }
        .login-title {
            font-size: 11px;
            letter-spacing: 5px;
            font-weight: 400;
            margin-bottom: 48px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 28px;
        }
        .form-group label {
            display: block;
            font-size: 10px;
            letter-spacing: 3px;
            margin-bottom: 8px;
            color: #888;
        }
        .form-group input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #d0d0d0;
            padding: 10px 0;
            font-size: 14px;
            background: transparent;
            outline: none;
            font-family: inherit;
            color: #000;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus { border-bottom-color: #000; }
        .btn-login {
            width: 100%;
            padding: 16px;
            background: #000;
            color: #fff;
            border: none;
            font-size: 11px;
            letter-spacing: 4px;
            cursor: pointer;
            margin-top: 16px;
            transition: background 0.3s ease;
        }
        .btn-login:hover { background: #333; }
        .error-msg {
            font-size: 11px;
            letter-spacing: 1px;
            color: #cc0000;
            text-align: center;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

<section class="login-section">
    <div class="login-box">
        <h2 class="login-title">ADMIN LOGIN</h2>

        <?php if ($error): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">USERNAME</label>
                <input type="text" id="username" name="username"
                       placeholder="admin" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••">
            </div>
            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>
</section>

</body>
</html>
