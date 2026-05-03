<?php
// ─────────────────────────────────────────────
//  process.php — Обработка формы заказа
//  Сохраняет данные в MySQL базу данных
// ─────────────────────────────────────────────

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

// ── Получение и очистка данных ──
$firstName = trim($_POST['firstName'] ?? '');
$lastName  = trim($_POST['lastName']  ?? '');
$email     = trim($_POST['email']     ?? '');
$phone     = trim($_POST['phone']     ?? '');
$category  = trim($_POST['category']  ?? '');
$size      = trim($_POST['size']      ?? '');
$message   = trim($_POST['message']   ?? '');

// ── Валидация server-side ──
$errors = [];

if (strlen($firstName) < 2) $errors[] = 'Invalid first name.';
if (strlen($lastName)  < 2) $errors[] = 'Invalid last name.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
if (!preg_match('/^[\+\d\s\-\(\)]{7,20}$/', $phone)) $errors[] = 'Invalid phone number.';
if (!in_array($category, ['clothing', 'shoes', 'accessories'])) $errors[] = 'Invalid category.';
if (!in_array($size, ['XS', 'S', 'M', 'L', 'XL'])) $errors[] = 'Invalid size.';

if (!empty($errors)) {
    $_SESSION['errors']   = $errors;
    $_SESSION['old_data'] = $_POST;
    header('Location: ../contact.php');
    exit;
}

// ── Сохранение в БД ──
$stmt = $pdo->prepare('
    INSERT INTO orders (first_name, last_name, email, phone, category, size, message)
    VALUES (?, ?, ?, ?, ?, ?, ?)
');
$stmt->execute([$firstName, $lastName, $email, $phone, $category, $size, $message]);
$orderId = $pdo->lastInsertId();

// ── Передача данных на страницу успеха ──
$_SESSION['order'] = [
    'id'        => $orderId,
    'firstName' => $firstName,
    'lastName'  => $lastName,
    'email'     => $email,
    'phone'     => $phone,
    'category'  => $category,
    'size'      => $size,
    'message'   => $message,
];

header('Location: ../success.php');
exit;
