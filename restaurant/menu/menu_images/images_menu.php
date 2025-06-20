<?php
// images_menu.php - качване и показване на снимки за менюто

session_start();
include '../../includes/db.php';

// Път до папката с изображения (вътре в 'menu/')
$upload_dir = './';

$message = "";

// Качване на файл
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file_name = basename($_FILES['image']['name']);
    $target_file = $upload_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES['image']['tmp_name']);

    if ($check === false) {
        $message = "Файлът не е изображение.";
    } elseif (file_exists($target_file)) {
        $message = "Файл с това име вече съществува.";
    } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) { // 5MB лимит
        $message = "Файлът е твърде голям.";
    } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $message = "Само JPG, JPEG, PNG и GIF файлове са разрешени.";
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $message = "Файлът " . htmlspecialchars($file_name) . " беше качен успешно.";
            // Тук можеш да добавиш запис в базата, ако искаш да свържеш снимката с менюто
        } else {
            $message = "Възникна грешка при качването.";
        }
    }
}

// Зареждаме всички качени снимки от папката
$images = array_diff(scandir($upload_dir), array('.', '..'));

?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8" />
    <title>Качване на снимки за менюто</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EFE6DD;
            padding: 20px;
        }
        .image-thumb {
            max-width: 150px;
            max-height: 150px;
            object-fit: cover;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
        }
        .upload-form {
            max-width: 400px;
            margin-bottom: 30px;
        }
        .message {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">

    <h2 class="mb-4">Качване на снимки за менюто</h2>

    <?php if ($message): ?>
        <div class="alert alert-info message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="image" class="form-label">Избери снимка (jpg, jpeg, png, gif):</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark">Качи снимка</button>
    </form>

    <h3>Качени снимки</h3>
    <div class="d-flex flex-wrap">
        <?php foreach ($images as $img): ?>
            <img src="<?= htmlspecialchars($upload_dir . $img) ?>" alt="Меню снимка" class="image-thumb" />
        <?php endforeach; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>