<?php
session_start();
include 'includes/db.php';

// Вземаме последните 3 отзива
$stmt = $pdo->query("
    SELECT r.rating, r.comment, r.created_at, u.name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
    LIMIT 3
");
$latest_reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8" />
    <title>Ресторант „Buona Vita“</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
    <link href="assets/style.css" rel="stylesheet" />
</head>
<body>

<!-- Навигация -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
  <a class="navbar-brand" href="#">🍝 Buona Vita</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Меню</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="reservation/reserve.php">Резервация</a></li>
            <li><a class="dropdown-item" href="menu/menu.php">Меню</a></li>
            <li><a class="dropdown-item" href="contacts/contacts.php">Контакти</a></li>
            <li><a class="dropdown-item" href="profiles/profile.php">Профил</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="gallery.php">Галерия</a></li>
            <li><a class="dropdown-item" href="raiting/reviews.php">Отзиви</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li><a class="dropdown-item" href="admin/dashboard.php">Админ панел</a></li>
            <?php endif; ?>
          </ul>
        </li>
        <?php if (isset($_SESSION['user_name'])): ?>
          <li class="nav-item"><a class="nav-link" href="registration/logout.php">Изход </a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="registration/login.php">Вход</a></li>
          <li class="nav-item"><a class="nav-link" href="registration/register_form.php">Регистрация</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Херо слайдер с по 2 снимки -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">

    <!-- Слайд 1 -->
    <div class="carousel-item active">
      <div class="row g-0">
        <div class="col-md-6">
          <img src="images/hero9.jpg" class="d-block w-100 h-100 carousel-img" alt="Снимка 1">
        </div>
        <div class="col-md-6">
          <img src="images/hero10.jpg" class="d-block w-100 h-100 carousel-img" alt="Снимка 2">
        </div>
      </div>
    </div>

    <!-- Слайд 2 -->
    <div class="carousel-item">
      <div class="row g-0">
        <div class="col-md-6">
          <img src="images/hero11.jpg" class="d-block w-100 h-100 carousel-img" alt="Снимка 3">
        </div>
        <div class="col-md-6">
          <img src="images/hero8.jpg" class="d-block w-100 h-100 carousel-img" alt="Снимка 4">
        </div>
      </div>
    </div>

  </div>

  <!-- Контроли -->
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
    <span class="visually-hidden">Предишен</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
    <span class="visually-hidden">Следващ</span>
  </button>
</div>
<!-- Надпис под карусела -->
<div class="text-center my-5">
  <h1 class="display-5 fw-bold">Добре дошли в <span style="color:#A52A2A;">Buona Vita</span></h1>
  <p class="fs-4 fst-italic text-muted">„Животът е съвършен, когато е подправен с вкус.“</p>
  <p class="fs-5 text-muted">
    Насладете се на истинска италианска кухня – с ухание на дом, вкус на Тоскана и нотка любов.
  </p>
  <div class="mt-3">
    <a href="reservation/reserve.php" class="btn btn-dark me-2">Резервирай сега</a>
    <a href="menu/menu.php" class="btn btn-outline-dark">Разгледай менюто</a>
</div>

<!-- Нашите предложения -->
<div class="container mt-5 mb-5 pt-4">
  <h2 class="text-center mb-4">🍽️ Нашите предложения</h2>
  <div class="row g-4">

    <!-- Салати -->
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <img src="images/salads.jpg" class="card-img-top" alt="Свежи салати">
        <div class="card-body">
          <h5 class="card-title">🥗 Свежи салати</h5>
        </div>
      </div>
    </div>

    <!-- Пици -->
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <img src="images/pizza.jpg" class="card-img-top" alt="Вкусни пици">
        <div class="card-body">
          <h5 class="card-title">🍕 Ароматни пици</h5>
        </div>
      </div>
    </div>

    <!-- Десерти -->
    <div class="col-md-4">
      <div class="card text-center shadow-sm">
        <img src="images/desserts1.jpg" class="card-img-top" alt="Изискани десерти">
        <div class="card-body">
          <h5 class="card-title">🍰 Вкусни десерти</h5>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- Отзиви -->
<div class="container my-5">
  <h3 class="text-center mb-4">📝 Остави отзив за ресторанта</h3>

  <?php if (isset($_SESSION['user_id'])): ?>
    <form method="POST" action="raiting/rate.php" class="review-form mb-5">
      <div class="mb-3">
        <label class="form-label">Оценка (1 до 5):</label>
        <select name="rating" class="form-select" required>
          <option value="">-- Избери --</option>
          <option value="1">1 - Лошо</option>
          <option value="2">2</option>
          <option value="3">3 - Добре</option>
          <option value="4">4</option>
          <option value="5">5 - Отлично</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Отзив:</label>
        <textarea name="comment" rows="3" class="form-control" placeholder="Споделете впечатление..."></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Изпрати</button>
    </form>
  <?php else: ?>
    <p class="text-center">🗣️ <a href="registration/login.php">Влезте в профила си</a>, за да оставите отзив.</p>
  <?php endif; ?>

  <!-- Последни отзиви -->
  <h4 class="text-center mt-5">💬 Последни отзиви</h4>
  <?php if ($latest_reviews): ?>
    <?php foreach ($latest_reviews as $review): ?>
      <div class="review-box mb-3">
        <strong><?= htmlspecialchars($review['name']) ?></strong>
        даде 
        <span style="color: gold;">
          <?= str_repeat("★", $review['rating']) ?><?= str_repeat("☆", 5 - $review['rating']) ?>
        </span> (<?= $review['rating'] ?>/5)
        <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
        <small class="text-muted"><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></small>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-center">Все още няма отзиви.</p>
  <?php endif; ?>
</div>

<!-- Футър -->
<footer class="footer bg-dark text-white mt-5">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-6 mb-3">
        <h5>📍 Адрес</h5>
        <p class="mb-1">ул. Примерна 123, София</p>
        <p class="mb-1">📞 +359 888 123 456</p>
        <p class="mb-0">✉️ info@restaurant.bg</p>
      </div>
      <div class="col-md-6 text-md-end text-center">
        <p class="mt-4 mt-md-0">&copy; 2025 Ресторант Buona Vita. Всички права запазени.</p>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
