<?php
session_start();
include '../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$message = "";

// Обработка на формата
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_id) {
    $reservation_date = $_POST['reservation_date'];
    $num_guests = $_POST['num_guests'];

    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, num_guests, reservation_date) VALUES (?, ?, ?)");
    if ($stmt->execute([$user_id, $num_guests, $reservation_date])) {
        $message = "<div class='alert alert-success'>Резервацията беше успешно направена!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Грешка при записване на резервация.</div>";
    }
}

// Извличане на резервации
$reservations = [];
if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ? ORDER BY reservation_date DESC");
    $stmt->execute([$user_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Резервации</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
            padding: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .reservation-form {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        .reservations-list {
            margin-top: 50px;
        }
        .reservation-entry {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Направи резервация</h2>
    
    <?= $message ?>

    <form method="POST" class="reservation-form">
        <div class="mb-3">
            <label for="reservation_date" class="form-label">Дата и час:</label>
            <input type="datetime-local" name="reservation_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="num_guests" class="form-label">Брой гости:</label>
            <input type="number" name="num_guests" class="form-control" required min="1">
        </div>
        <button type="submit" class="btn btn-primary w-100">Направи резервация</button>
    </form>

    <div class="reservations-list mt-5">
        <h3 class="text-center">Твоите резервации</h3>
        <?php if (!empty($reservations)): ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-entry">
                    <strong>Дата:</strong> <?= htmlspecialchars($reservation['reservation_date']) ?><br>
                    <strong>Брой гости:</strong> <?= htmlspecialchars($reservation['num_guests']) ?><br>
                    <a href="cancel_reservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-outline-danger mt-2">Отмени</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Нямате направени резервации.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>