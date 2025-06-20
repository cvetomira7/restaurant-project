<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: registration/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, email, role, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Потребителят не е намерен.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Моят профил</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .profile-card {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .role {
            font-size: 0.95em;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="profile-card text-center">
        <h2>👤 Моят профил</h2>
        <hr>
        <p><strong>Име:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Имейл:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Регистриран на:</strong> <?= date('d.m.Y', strtotime($user['created_at'])) ?></p>
        <p class="role">
            <strong>Роля:</strong>
            <?= $user['role'] === 'admin' ? 'Администратор 🛡️' : 'Потребител 👤' ?>
        </p>

        <?php if ($user['role'] === 'admin'): ?>
            <a href="../admin/dashboard.php" class="btn btn-warning mt-3">🔧 Админ панел</a>
        <?php endif; ?>

        <a href="registration/logout.php" class="btn btn-outline-danger mt-3">Изход</a>

        <a href="edit_profile.php" class="btn btn-outline-secondary mt-2">✏️ Редакция на профил</a>
    </div>
</div>

</body>
</html>