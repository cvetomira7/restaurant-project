<?php
session_start();

// Само админ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['image']['tmp_name'];
        $original = basename($_FILES['image']['name']);
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            $newName = uniqid('gallery_') . '.' . $ext;
            $target = "gallery_images/$newName";
            if (move_uploaded_file($tmp, $target)) {
                $message = "<div class='alert alert-success'>Снимката беше качена успешно!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Грешка при качване на файла.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>Невалиден формат. Разрешени: jpg, jpeg, png, webp</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Моля, изберете снимка за качване.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="bg">
<head>
  <meta charset="UTF-8">
  <title>Качи снимка в галерията</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #EFE6DD;
      font-family: 'Poppins', sans-serif;
    }
    .upload-box {
      max-width: 500px;
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
  <div class="upload-box">
    <h3 class="text-center mb-4">📤 Качи снимка в галерията</h3>
    
    <?= $message ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Избери снимка:</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-dark w-100">Качи</button>
    </form>

    <a href="gallery.php" class="btn btn-outline-secondary w-100 mt-3">⬅️ Обратно към галерията</a>
  </div>
</div>

</body>
</html>
