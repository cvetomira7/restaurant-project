<?php
session_start();
include '../includes/db.php';

// Само админ може да добавя
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);

    // Проверка за задължителни полета
    if ($name && $category && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, category, description, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $description, $price]);
        $message = '<div class="alert alert-success">Ястието беше добавено успешно.</div>';
    } else {
        $message = '<div class="alert alert-danger">Моля, попълнете всички задължителни полета.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Добави ястие</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .form-box {
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
    <div class="form-box">
        <h3 class="text-center mb-4">➕ Добавяне на ново ястие</h3>

        <?= $message ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Име на ястието *</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Категория *</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Избери --</option>
                    <option value="Салати">Салати</option>
                    <option value="Основни">Основни</option>
                    <option value="Десерти">Десерти</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Цена (лв.) *</label>
                <input type="number" name="price" step="0.01" min="0.01" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-dark w-100">Запиши ястието</button>
        </form>

        <a href="menu_manage.php" class="btn btn-outline-secondary w-100 mt-3">⬅️ Назад към менюто</a>
    </div>
</div>

</body>
</html>