<?php
session_start();
include '../includes/db.php';

// Проверка за достъп само за админи
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Взимаме броя на потребители, съобщения, резервации, отзиви
$user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$message_count = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$reservation_count = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$review_count = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
$dish_review_count = $pdo->query("SELECT COUNT(*) FROM dish_reviews")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Админ панел</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        .admin-header {
            background-color: #000;
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="admin-header text-center">
        <h2>🛡️ Админ панел</h2>
        <p>Добре дошъл, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>👥 Потребители</h5>
                <p class="display-6"><?= $user_count ?></p>
                <a href="users.php" class="btn btn-sm btn-dark">Преглед</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>📸 Галерия</h5>
                <p class="display-6">Добави снимка</p>
                <a href="../gallery_upload.php" class="btn btn-sm btn-dark">Качи снимка</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>📬 Съобщения</h5>
                <p class="display-6"><?= $message_count ?></p>
                <a href="messages.php" class="btn btn-sm btn-dark">Преглед</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>📅 Резервации</h5>
                <p class="display-6"><?= $reservation_count ?></p>
                <a href="reservations.php" class="btn btn-sm btn-dark">Преглед</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>⭐ Отзиви (ресторант)</h5>
                <p class="display-6"><?= $review_count ?></p>
                <a href="reviews.php" class="btn btn-sm btn-dark">Преглед</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>🍽️ Отзиви (ястия)</h5>
                <p class="display-6"><?= $dish_review_count ?></p>
                <a href="dish_reviews.php" class="btn btn-sm btn-dark">Преглед</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 text-center bg-white">
                <h5>🍽️ Меню на ресторанта</h5>
                <p class="display-6">Редакция</p>
                <a href="../menu/menu_manage.php" class="btn btn-sm btn-dark">Преглед и редакция</a>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="../index.php" class="btn btn-outline-secondary">⬅️ Обратно към сайта</a>
    </div>
</div>

</body>
</html>