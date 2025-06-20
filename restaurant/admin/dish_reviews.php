<?php
session_start();
include '../includes/db.php';

// Само за админи
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Изтриване на отзив
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM dish_reviews WHERE id = ?");
    $stmt->execute([$_GET['delete_id']]);
    $message = "Отзивът беше изтрит.";
}

// Извличане на отзиви за ястия
$stmt = $pdo->query("
    SELECT dr.id, dr.rating, dr.comment, dr.created_at, u.name AS user_name, mi.name AS dish_name
    FROM dish_reviews dr
    JOIN users u ON dr.user_id = u.id
    JOIN menu_items mi ON dr.dish_id = mi.id
    ORDER BY dr.created_at DESC
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Отзиви за ястия</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .review-box {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }
        .stars {
            color: gold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">🍽️ Отзиви за ястия</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (count($reviews) > 0): ?>
        <?php foreach ($reviews as $row): ?>
            <div class="review-box">
                <h5><?= htmlspecialchars($row['user_name']) ?> за <strong><?= htmlspecialchars($row['dish_name']) ?></strong></h5>
                <p><span class="stars"><?= str_repeat('★', $row['rating']) ?><?= str_repeat('☆', 5 - $row['rating']) ?></span> (<?= $row['rating'] ?>/5)</p>
                <p><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                <small class="text-muted"><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></small><br>
                <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Да изтрием този отзив?')">Изтрий</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Няма оставени отзиви.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">⬅️ Назад към панела</a>
</div>

</body>
</html>