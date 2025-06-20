<?php
session_start();
include '../includes/db.php';

// Достъп само за админ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Обработка на изтриване
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // Не позволявай админ да изтрие себе си
    if ($delete_id !== $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        $message = "Потребителят беше изтрит.";
    } else {
        $message = "Не можете да изтриете собствения си акаунт.";
    }
}

// Вземане на всички потребители
$stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Потребители</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    <h2 class="mb-4">👥 Всички потребители</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Име</th>
                <th>Имейл</th>
                <th>Роля</th>
                <th>Регистрация</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['role'] === 'admin' ? 'Администратор' : 'Потребител' ?></td>
                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                            <a href="?delete_id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Сигурни ли сте, че искате да изтриете този потребител?')">Изтрий</a>
                        <?php else: ?>
                            <span class="text-muted">(вие)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">⬅️ Назад към панела</a>
</div>

</body>
</html>