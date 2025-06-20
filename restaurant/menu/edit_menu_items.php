<?php
session_start();
include '../includes/db.php';

// Проверка за админ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? null;
$message = "";

if (!$id || !is_numeric($id)) {
    header("Location: menu_manage.php");
    exit();
}

// Вземаме текущото ястие
$stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    $message = '<div class="alert alert-danger">Ястието не е намерено.</div>';
}

// Обработка на формата
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $imageName = $item['image']; // текуща снимка

    // Ако има качен файл
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $originalName = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newFileName = uniqid('dish_') . '.' . $ext;

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            if (move_uploaded_file($imageTmp, "menu_images/$newFileName")) {
                $imageName = $newFileName;
            } else {
                $message = '<div class="alert alert-danger">Грешка при качване на снимка.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Невалиден формат на снимката. Разрешени: jpg, jpeg, png, webp.</div>';
        }
    }

    if ($name && $category && $price > 0) {
        $stmt = $pdo->prepare("UPDATE menu_items SET name = ?, category = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $category, $description, $price, $imageName, $id]);

        $message = '<div class="alert alert-success">Ястието беше обновено успешно.</div>';

        // Обновяване на локалната променлива
        $item = [
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'price' => $price,
            'image' => $imageName
        ];
    } else {
        $message = '<div class="alert alert-danger">Моля, попълнете всички задължителни полета.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редакция на ястие</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
        }
        .form-box {
            max-width: 650px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        img.preview {
            max-height: 150px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-box">
        <h3 class="text-center mb-4">✏️ Редакция на ястие</h3>

        <?= $message ?>

        <?php if ($item): ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Име на ястието *</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($item['name']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Категория *</label>
                <select name="category" class="form-select" required>
                    <option value="Салати" <?= $item['category'] === 'Салати' ? 'selected' : '' ?>>Салати</option>
                    <option value="Основни" <?= $item['category'] === 'Основни' ? 'selected' : '' ?>>Основни</option>
                    <option value="Десерти" <?= $item['category'] === 'Десерти' ? 'selected' : '' ?>>Десерти</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Цена (лв.) *</label>
                <input type="number" name="price" step="0.01" min="0.01" class="form-control" required value="<?= number_format($item['price'], 2, '.', '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Смени снимка (по избор)</label>
                <?php if (!empty($item['image']) && file_exists("menu_images/" . $item['image'])): ?>
                    <img src="menu_images/<?= htmlspecialchars($item['image']) ?>" class="preview img-fluid">
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-dark w-100">Запази промените</button>
        </form>
        <?php endif; ?>

        <a href="menu_manage.php" class="btn btn-outline-secondary w-100 mt-3">⬅️ Назад към менюто</a>
    </div>
</div>

</body>
</html>