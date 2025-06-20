<?php
session_start();
include '../includes/db.php';

// Обработка на отзив за ястие
$review_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dish_id'], $_POST['rating'])) {
    if (!isset($_SESSION['user_id'])) {
        $review_message = "Моля, влезте в профила си, за да оставите отзив.";
    } else {
        $user_id = $_SESSION['user_id'];
        $dish_id = (int)$_POST['dish_id'];
        $rating = (int)$_POST['rating'];
        $comment = trim($_POST['comment']);

        if ($rating >= 1 && $rating <= 5) {
            // Проверка дали вече има отзив
            $check = $pdo->prepare("SELECT id FROM dish_reviews WHERE user_id = ? AND dish_id = ?");
            $check->execute([$user_id, $dish_id]);
        
            if ($check->rowCount() > 0) {
                $review_message = "Вече сте оставили отзив за това ястие.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO dish_reviews (user_id, dish_id, rating, comment) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $dish_id, $rating, $comment]);
                $review_message = "Отзивът ви беше запазен. Благодарим!";
            }
        } else {
            $review_message = "Невалидна стойност за рейтинг.";
        }
    }
}

// Групиране на ястия по категория
$category_order = ['Салати', 'Основни', 'Десерти'];
$items_by_category = [];

$stmt = $pdo->query("SELECT * FROM menu_items ORDER BY category, name");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($items as $item) {
    $items_by_category[$item['category']][] = $item;
}

// Вземане на всички рейтинги за всяко ястие
$dish_ratings = [];
$dish_reviews = [];

$rating_stmt = $pdo->query("SELECT dish_id, AVG(rating) AS avg_rating FROM dish_reviews GROUP BY dish_id");
foreach ($rating_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $dish_ratings[$row['dish_id']] = round($row['avg_rating'], 1);
}

$review_stmt = $pdo->query("SELECT dr.*, u.name FROM dish_reviews dr JOIN users u ON dr.user_id = u.id ORDER BY dr.created_at DESC");
foreach ($review_stmt->fetchAll(PDO::FETCH_ASSOC) as $rev) {
    $dish_reviews[$rev['dish_id']][] = $rev;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Меню на ресторанта</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            background-color: #EFE6DD;
            font-family: 'Poppins', sans-serif;
            padding: 20px;
        }
        .menu-section {
            margin-bottom: 40px;
        }
        .menu-card {
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .menu-card:hover {
            transform: scale(1.02);
        }
        .menu-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .card-body {
            background-color: #fff;
        }
        .stars {
            color: gold;
        }
        .review-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-5">Меню на ресторанта</h1>

    <?php if ($review_message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($review_message) ?></div>
    <?php endif; ?>

    <?php foreach ($category_order as $category): ?>
        <?php if (isset($items_by_category[$category])): ?>
            <div class="menu-section">
                <h2 class="mb-4"><?= htmlspecialchars($category) ?></h2>
                <div class="row g-4">
                    <?php foreach ($items_by_category[$category] as $item): ?>
                        <div class="col-md-4">
                            <div class="card menu-card">
                                <?php if (!empty($item['image']) && file_exists('menu_images/' . $item['image'])): ?>
                                    <img src="menu_images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="menu-image">
                                <?php else: ?>
                                    <img src="menu_images/default.jpg" alt="Няма снимка" class="menu-image">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($item['description']) ?></p>
                                    <p class="card-text fw-bold"><?= number_format($item['price'], 2) ?> лв.</p>
                                    
                                    <!-- Среден рейтинг -->
                                    <?php if (isset($dish_ratings[$item['id']])): ?>
                                        <p>Оценка: <span class="stars"><?= str_repeat('★', round($dish_ratings[$item['id']])) ?><?= str_repeat('☆', 5 - round($dish_ratings[$item['id']])) ?></span> (<?= $dish_ratings[$item['id']] ?>/5)</p>
                                    <?php else: ?>
                                        <p>Оценка: Няма</p>
                                    <?php endif; ?>

                                    <!-- Форма за оценка -->
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <form method="POST" class="mb-3">
                                            <input type="hidden" name="dish_id" value="<?= $item['id'] ?>">
                                            <label class="form-label">Оцени това ястие:</label>
                                            <select name="rating" class="form-select mb-2" required>
                                                <option value="">-- Избери --</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                            <textarea name="comment" class="form-control mb-2" placeholder="Коментар (по желание)"></textarea>
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Изпрати</button>
                                        </form>
                                    <?php else: ?>
                                        <p class="text-muted">Влезте в профила си, за да оставите оценка.</p>
                                    <?php endif; ?>

                                    <!-- Отзиви за ястието -->
                                    <?php if (!empty($dish_reviews[$item['id']])): ?>
                                        <h6>Отзиви:</h6>
                                        <?php foreach ($dish_reviews[$item['id']] as $rev): ?>
                                            <div class="review-box">
                                                <strong><?= htmlspecialchars($rev['name']) ?></strong> 
                                                <span class="stars"><?= str_repeat('★', $rev['rating']) ?><?= str_repeat('☆', 5 - $rev['rating']) ?></span>
                                                <small class="text-muted"><?= date('d.m.Y H:i', strtotime($rev['created_at'])) ?></small>
                                                <p><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
