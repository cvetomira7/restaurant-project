<?php
session_start();
include '../includes/db.php';

// Само админ има достъп
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Изтриване на съобщение
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$delete_id]);
    $message = "Съобщението беше изтрито.";
}

// Извличане на съобщения
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Контактни съобщения</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .message-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">📬 Получени съобщения</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (count($messages) > 0): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="message-box">
                <h5><?= htmlspecialchars($msg['name']) ?> <small class="text-muted">(<a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a>)</small></h5>
                <small class="text-muted"><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></small>
                <p class="mt-2"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                <a href="?delete_id=<?= $msg['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Да изтрием това съобщение?')">Изтрий</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Няма изпратени съобщения.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">⬅️ Назад към панела</a>
</div>

</body>
</html>