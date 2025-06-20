<?php
session_start();
include '../includes/db.php';

// Проверка дали е админ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Извличане на ястията
$stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Управление на менюто</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">🍽️ Управление на ястията</h2>

    <a href="add_menu_item.php" class="btn btn-dark mb-3">➕ Добави ново ястие</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Име</th>
                <th>Категория</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td><?= number_format($item['price'], 2) ?> лв.</td>
                    <td>
                        <a href="edit_menu_items.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">✏️</a>
                        <a href="delete_item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Сигурни ли сте, че искате да изтриете това ястие?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../admin/dashboard.php" class="btn btn-outline-secondary mt-4">⬅️ Назад към админ панела</a>
</div>

</body>
</html>