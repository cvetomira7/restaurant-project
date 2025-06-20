<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../registration/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Вземане на текущите данни
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Обработка на формата
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Валидиране
    if (!$new_name || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger">Невалидно име или имейл.</div>';
    } else {
        // Обновяване на име и имейл
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$new_name, $new_email, $user_id]);

        // Смяна на парола, ако е въведена
        if ($new_pass || $confirm_pass) {
            if ($new_pass === $confirm_pass && strlen($new_pass) >= 6) {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed, $user_id]);
                $message .= '<div class="alert alert-success mt-2">Паролата беше сменена успешно.</div>';
            } else {
                $message .= '<div class="alert alert-danger mt-2">Паролите не съвпадат или са прекалено къси.</div>';
            }
        } else {
            $message .= '<div class="alert alert-success">Профилът беше обновен успешно.</div>';
        }

        // Обновяване на името в сесията (ако се използва за показване)
        $_SESSION['user_name'] = $new_name;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редакция на профил</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .edit-form {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="edit-form">
        <h2 class="text-center">Редакция на профил</h2>
        <?= $message ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Име</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Имейл</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <hr>
            <h5 class="mb-3">Смяна на парола (по желание)</h5>
            <div class="mb-3">
                <label class="form-label">Нова парола</label>
                <input type="password" name="new_password" class="form-control" placeholder="Минимум 6 символа">
            </div>
            <div class="mb-3">
                <label class="form-label">Потвърди парола</label>
                <input type="password" name="confirm_password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Запази промените</button>
        </form>
        <a href="profile.php" class="btn btn-link d-block text-center mt-3">Назад към профила</a>
    </div>
</div>

</body>
</html>