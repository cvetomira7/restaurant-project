<?php session_start(); ?>
<!DOCTYPE html>
<html lang="bg">
<head>
  <meta charset="UTF-8">
  <title>Галерия</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #EFE6DD;
      font-family: 'Poppins', sans-serif;
    }
    .gallery-title {
      text-align: center;
      margin: 40px 0 20px;
    }
    .gallery-img {
      width: 100%;
      height: 240px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
      transition: transform 0.3s;
    }
    .gallery-img:hover {
      transform: scale(1.03);
    }
    .gallery-item {
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<div class="container">
  <h1 class="gallery-title">📷 Галерия на ресторанта</h1>
  <div class="row">
    <?php
    // Папка с изображения
    $images = glob("gallery_images/*.{jpg,jpeg,png,webp}", GLOB_BRACE);

    if ($images):
      foreach ($images as $img):
    ?>
      <div class="col-md-4 col-sm-6 gallery-item">
        <img src="<?= htmlspecialchars($img) ?>" alt="Галерия" class="gallery-img">
      </div>
    <?php
      endforeach;
    else:
      echo '<p class="text-center">Все още няма добавени снимки в галерията.</p>';
    endif;
    ?>
  </div>
</div>

<footer class="footer bg-dark text-white mt-5 text-center py-3">
  &copy; 2025 Ресторант „Твоето Име“. Всички права запазени.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>