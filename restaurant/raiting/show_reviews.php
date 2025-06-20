<?php
include '../includes/db.php';

// Вземаме всички ревюта
$sql_reviews = "SELECT r.rating, r.comment, r.created_at, u.name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                ORDER BY r.created_at DESC";
$stmt_reviews = $pdo->query($sql_reviews);
$reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);

// Вземаме среден рейтинг
$sql_avg = "SELECT AVG(rating) AS avg_rating FROM reviews";
$stmt_avg = $pdo->query($sql_avg);
$avg_rating = round($stmt_avg->fetchColumn(), 1);

// Функция за визуални звезди
function displayStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return $stars;
}
?>

<h2>Среден рейтинг: <?= $avg_rating ?>/5</h2>
<p style="font-size: 24px; color: gold;"><?= displayStars(round($avg_rating)) ?></p>

<hr>

<h3>Отзиви от клиенти</h3>

<?php if (count($reviews) > 0): ?>
    <?php foreach ($reviews as $row): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <strong><?= htmlspecialchars($row['name']) ?></strong> 
            даде <strong><?= $row['rating'] ?>/5</strong> звезди 
            <?= displayStars($row['rating']) ?>
            <br>
            <em><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></em>
            <p><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Все още няма оставени отзиви.</p>
<?php endif; ?>