<?php
session_start();
include '../includes/db.php';

// Само админ има достъп
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Изтриване на резервация
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$delete_id]);
    $message = "Резервацията беше изтрита.";
}

// Вземане на всички резервации с име на потребителя
$stmt = $pdo->query("
    SELECT r.id, r.reservation_date, r.num_guests, r.created_at, u.name 
    FROM reservations r 
    JOIN users u ON r.user_id = u.id 
    ORDER BY r.reservation_date DESC
");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Резервации</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .res-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">📅 Всички резервации</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (count($reservations) > 0): ?>
        <?php foreach ($reservations as $res): ?>
            <div class="res-card">
                <h5><?= htmlspecialchars($res['name']) ?></h5>
                <p><strong>Дата и час:</strong> <?= htmlspecialchars($res['reservation_date']) ?></p>
                <p><strong>Брой гости:</strong> <?= $res['num_guests'] ?></p>
                <p class="text-muted">Заявка на: <?= date('d.m.Y H:i', strtotime($res['created_at'])) ?></p>
                <a href="?delete_id=<?= $res['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Да изтрием тази резервация?')">Изтрий</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Няма направени резервации.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">⬅️ Назад към панела</a>
</div>

</body>
</html>