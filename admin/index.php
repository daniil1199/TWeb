<?php
session_start();

if (empty($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// ── Обновление статуса заказа ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (in_array($_POST['status'], $allowed)) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$_POST['status'], $_POST['order_id']]);
    }
    header('Location: index.php');
    exit;
}

// ── Фильтр по статусу ──
$filterStatus = $_GET['status'] ?? 'all';
$allowed = ['all', 'pending', 'confirmed', 'completed', 'cancelled'];
if (!in_array($filterStatus, $allowed)) $filterStatus = 'all';

if ($filterStatus === 'all') {
    $orders = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();
} else {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE status = ? ORDER BY created_at DESC');
    $stmt->execute([$filterStatus]);
    $orders = $stmt->fetchAll();
}

// ── Статистика ──
$stats = $pdo->query('
    SELECT
        COUNT(*) as total,
        SUM(status = "pending") as pending,
        SUM(status = "confirmed") as confirmed,
        SUM(status = "completed") as completed,
        SUM(status = "cancelled") as cancelled
    FROM orders
')->fetch();

$statusColors = [
    'pending'   => '#f0a500',
    'confirmed' => '#1565c0',
    'completed' => '#2e7d32',
    'cancelled' => '#cc0000',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel – Vestes</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        body { background: #f5f5f5; }

        .admin-header {
            padding: 20px 40px;
            background: #000;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header .logo { color: #fff; font-size: 18px; letter-spacing: 5px; }
        .admin-header .admin-info { font-size: 11px; letter-spacing: 2px; color: #aaa; }
        .admin-header a {
            color: #fff; font-size: 10px; letter-spacing: 2px;
            border: 1px solid #444; padding: 6px 16px;
            text-decoration: none; transition: border-color 0.3s;
        }
        .admin-header a:hover { border-color: #fff; }

        .admin-body { padding: 40px; }

        /* Stats */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card {
            background: #fff; padding: 24px; text-align: center;
            border-top: 3px solid #000;
        }
        .stat-card .stat-num { font-size: 32px; font-weight: 300; margin-bottom: 8px; }
        .stat-card .stat-label { font-size: 10px; letter-spacing: 3px; color: #888; }
        .stat-card.pending   { border-color: #f0a500; }
        .stat-card.confirmed { border-color: #1565c0; }
        .stat-card.completed { border-color: #2e7d32; }
        .stat-card.cancelled { border-color: #cc0000; }

        /* Filter */
        .filter-bar {
            display: flex; gap: 12px; margin-bottom: 24px; align-items: center;
        }
        .filter-bar span { font-size: 10px; letter-spacing: 3px; color: #888; }
        .filter-btn {
            padding: 8px 20px; font-size: 10px; letter-spacing: 2px;
            border: 1px solid #d0d0d0; background: #fff;
            text-decoration: none; color: #000;
            transition: all 0.2s;
        }
        .filter-btn:hover, .filter-btn.active {
            background: #000; color: #fff; border-color: #000;
        }

        /* Table */
        .orders-table { width: 100%; border-collapse: collapse; background: #fff; }
        .orders-table th {
            font-size: 10px; letter-spacing: 3px; font-weight: 400;
            padding: 16px 20px; text-align: left;
            border-bottom: 2px solid #000; color: #888;
        }
        .orders-table td {
            padding: 16px 20px; font-size: 12px; letter-spacing: 1px;
            border-bottom: 1px solid #f0f0f0; vertical-align: middle;
        }
        .orders-table tr:hover td { background: #fafafa; }

        .order-id { font-weight: 500; color: #000; }
        .order-name { font-weight: 500; }
        .order-email { color: #888; font-size: 11px; }

        /* Status badge */
        .status-badge {
            display: inline-block; padding: 4px 12px;
            font-size: 9px; letter-spacing: 2px;
            border-radius: 2px; font-weight: 500;
        }

        /* Status select */
        .status-form { display: flex; gap: 8px; align-items: center; }
        .status-select {
            border: 1px solid #d0d0d0; padding: 6px 10px;
            font-size: 10px; letter-spacing: 1px;
            font-family: inherit; background: #fff;
            cursor: pointer; outline: none;
        }
        .btn-update {
            padding: 6px 14px; background: #000; color: #fff;
            border: none; font-size: 9px; letter-spacing: 2px;
            cursor: pointer; transition: background 0.2s;
        }
        .btn-update:hover { background: #333; }

        .empty-msg {
            text-align: center; padding: 60px;
            font-size: 11px; letter-spacing: 3px; color: #aaa;
        }
    </style>
</head>
<body>

<div class="admin-header">
    <div class="logo">VESTES</div>
    <div class="admin-info">Logged in as <?= htmlspecialchars($_SESSION['admin']) ?></div>
    <a href="logout.php">LOGOUT</a>
</div>

<div class="admin-body">

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card pending">
            <div class="stat-num"><?= $stats['pending'] ?></div>
            <div class="stat-label">PENDING</div>
        </div>
        <div class="stat-card confirmed">
            <div class="stat-num"><?= $stats['confirmed'] ?></div>
            <div class="stat-label">CONFIRMED</div>
        </div>
        <div class="stat-card completed">
            <div class="stat-num"><?= $stats['completed'] ?></div>
            <div class="stat-label">COMPLETED</div>
        </div>
        <div class="stat-card cancelled">
            <div class="stat-num"><?= $stats['cancelled'] ?></div>
            <div class="stat-label">CANCELLED</div>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-bar">
        <span>FILTER:</span>
        <a href="?status=all"       class="filter-btn <?= $filterStatus === 'all'       ? 'active' : '' ?>">ALL (<?= $stats['total'] ?>)</a>
        <a href="?status=pending"   class="filter-btn <?= $filterStatus === 'pending'   ? 'active' : '' ?>">PENDING</a>
        <a href="?status=confirmed" class="filter-btn <?= $filterStatus === 'confirmed' ? 'active' : '' ?>">CONFIRMED</a>
        <a href="?status=completed" class="filter-btn <?= $filterStatus === 'completed' ? 'active' : '' ?>">COMPLETED</a>
        <a href="?status=cancelled" class="filter-btn <?= $filterStatus === 'cancelled' ? 'active' : '' ?>">CANCELLED</a>
    </div>

    <!-- Orders Table -->
    <?php if (empty($orders)): ?>
        <div class="empty-msg">NO ORDERS FOUND</div>
    <?php else: ?>
    <table class="orders-table">
        <thead>
            <tr>
                <th>#</th>
                <th>CLIENT</th>
                <th>PHONE</th>
                <th>CATEGORY</th>
                <th>SIZE</th>
                <th>DATE</th>
                <th>STATUS</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td class="order-id">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td>
                    <div class="order-name"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></div>
                    <div class="order-email"><?= htmlspecialchars($order['email']) ?></div>
                </td>
                <td><?= htmlspecialchars($order['phone']) ?></td>
                <td><?= strtoupper(htmlspecialchars($order['category'])) ?></td>
                <td><?= htmlspecialchars($order['size']) ?></td>
                <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                <td>
                    <span class="status-badge" style="background:<?= $statusColors[$order['status']] ?>22; color:<?= $statusColors[$order['status']] ?>">
                        <?= strtoupper($order['status']) ?>
                    </span>
                </td>
                <td>
                    <form class="status-form" method="POST" action="index.php">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status" class="status-select">
                            <option value="pending"   <?= $order['status'] === 'pending'   ? 'selected' : '' ?>>Pending</option>
                            <option value="confirmed" <?= $order['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                            <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn-update">UPDATE</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>

</body>
</html>
