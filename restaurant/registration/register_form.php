<?php
session_start();
$message = ""; // В случай че е забравен
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EFE6DD;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #000;
            border-color: #000;
        }
        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Регистрация на потребител</h2>

    <?php if (!empty($message)): ?>
        <div class="alert <?= str_contains($message, 'успешна') || str_contains($message, 'успешна') ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="mb-3">
            <label for="name" class="form-label">Име</label>
            <input type="text" class="form-control" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Имейл</label>
            <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Парола</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Телефон</label>
            <input type="text" class="form-control" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Регистрирай се</button>
    </form>
</div>
</body>
</html>