<?php
session_start();
include '../includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Пренасочване според ролята
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                header("Location: ../index.php");
                exit;
            }
        } else {
            $message = "Невалидна парола!";
        }
    } else {
        $message = "Потребител с този имейл не съществува!";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EFE6DD;
            color: #000;
        }
        .container {
            max-width: 500px;
            margin-top: 80px;
        }
        .card {
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #000;
            border: none;
        }
        .btn-primary:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h3 class="mb-4 text-center">Вход в системата</h3>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Имейл:</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Парола:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Вход</button>

            <div class="text-center mt-3">
            <a href="forgot_password.php" class="text-decoration-none text-muted">Забравена парола?</a>
        </div>
        </form>
    </div>
</div>

</body>
</html>