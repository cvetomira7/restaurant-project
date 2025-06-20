<?php
session_start();
require_once '../includes/db.php';

$message = "";

// Проверка за валиден токен
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT id, reset_expires FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $expires_at = $user['reset_expires'];

        if (strtotime($expires_at) < time()) {
            $message = "Линкът е изтекъл.";
        }
    } else {
        $message = "Невалиден линк.";
    }
} else {
    $message = "Липсва токен.";
}

// Обработка на новата парола
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['token'])) {
    $new_password = $_POST['new_password'];
    $token = $_POST['token'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);

        $_SESSION['message'] = "Паролата беше успешно променена. Можете да влезете.";
        header("Location: login.php");
        exit;
    } else {
        $message = "Невалиден токен.";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Нулиране на парола</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EFE6DD;
        }
        .container {
            max-width: 500px;
            margin-top: 80px;
        }
        .card {
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .btn-primary {
            background-color: #000;
            border: none;
        }
        .btn-primary:hover {
            background-color: #333;
        }
        .alert {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h3 class="mb-4 text-center">🔒 Нулиране на парола</h3>

        <?php if ($message): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
        <?php elseif (isset($token)): ?>
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="mb-3">
                    <label for="new_password" class="form-label">Нова парола</label>
                    <input type="password" name="new_password" class="form-control" placeholder="Въведете нова парола" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">💾 Запази</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>