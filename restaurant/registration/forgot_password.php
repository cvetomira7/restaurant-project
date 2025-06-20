<?php
session_start();
require_once '../includes/db.php';

$message = $_SESSION['message'] ?? "";
$reset_link = $_SESSION['reset_link'] ?? "";
unset($_SESSION['message'], $_SESSION['reset_link']);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Забравена парола</title>
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
        .btn-dark {
            background-color: #000;
            border: none;
        }
        .btn-dark:hover {
            background-color: #333;
        }
        .alert {
            font-size: 0.95rem;
        }
        a {
            word-break: break-word;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="mb-4 text-center">🔐 Забравена парола</h3>

        <?php if ($message): ?>
            <div class="alert <?= strpos($message, 'изпратен') !== false ? 'alert-success' : 'alert-danger' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($reset_link): ?>
            <div class="alert alert-info">
                <strong>Тестов линк за нулиране:</strong><br>
                <a href="<?= htmlspecialchars($reset_link) ?>" target="_blank">🔗 <?= htmlspecialchars($reset_link) ?></a>
            </div>
        <?php endif; ?>

        <form action="send_reset_password.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Имейл адрес</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">📩 Изпрати линк за възстановяване</button>
        </form>
    </div>
</div>

</body>
</html>