<?php
// ─────────────────────────────────────────────
//  validate.php — AJAX валидация полей
//  Lab 5: Возвращает JSON с результатом
// ─────────────────────────────────────────────

header('Content-Type: application/json; charset=utf-8');

$field = $_POST['field'] ?? '';
$value = trim($_POST['value'] ?? '');

$hints = [
    'firstName' => 'Enter your first name (letters only, min 2 characters).',
    'lastName'  => 'Enter your last name (letters only, min 2 characters).',
    'email'     => 'Enter a valid email address, e.g. name@example.com.',
    'phone'     => 'Enter your phone number, e.g. +373 60 123 456.',
    'category'  => 'Select the product category you are interested in.',
    'size'      => 'Select your clothing or shoe size.',
    'message'   => 'Optional. Max 200 characters.',
];

$valid   = true;
$message = 'Looks good!';
$hint    = $hints[$field] ?? '';

switch ($field) {
    case 'firstName':
    case 'lastName':
        if (strlen($value) < 1) {
            $valid = false; $message = 'This field is required.';
        } elseif (strlen($value) < 2) {
            $valid = false; $message = 'Minimum 2 characters required.';
        } elseif (strlen($value) > 50) {
            $valid = false; $message = 'Maximum 50 characters allowed.';
        } elseif (!preg_match("/^[a-zA-ZÀ-ÿ\s\-']+$/u", $value)) {
            $valid = false; $message = 'Only letters, spaces and hyphens allowed.';
        }
        break;

    case 'email':
        if (strlen($value) < 1) {
            $valid = false; $message = 'This field is required.';
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $valid = false; $message = 'Please enter a valid email address.';
        } elseif (strlen($value) > 100) {
            $valid = false; $message = 'Email is too long.';
        }
        break;

    case 'phone':
        if (strlen($value) < 1) {
            $valid = false; $message = 'This field is required.';
        } elseif (!preg_match('/^[\+\d\s\-\(\)]{7,20}$/', $value)) {
            $valid = false; $message = 'Enter a valid phone number (7-20 digits).';
        }
        break;

    case 'category':
        $allowed = ['clothing', 'shoes', 'accessories'];
        if (!in_array($value, $allowed)) {
            $valid = false; $message = 'Please select a category.';
        }
        break;

    case 'size':
        $allowed = ['XS', 'S', 'M', 'L', 'XL'];
        if (!in_array($value, $allowed)) {
            $valid = false; $message = 'Please select a size.';
        }
        break;

    case 'message':
        if (strlen($value) > 200) {
            $valid = false; $message = 'Maximum 200 characters allowed.';
        }
        break;

    default:
        $valid = false; $message = 'Unknown field.';
}

echo json_encode([
    'valid'   => $valid,
    'message' => $message,
    'hint'    => $hint,
]);
