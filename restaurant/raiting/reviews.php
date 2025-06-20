<?php
include '../includes/db.php';

$sql = "SELECT r.rating, r.comment, r.created_at, u.name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.created_at DESC";

$stmt = $pdo->query($sql);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Отзиви и Оценки</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            padding: 20px;
        }
        .review-box {
            background: #fff;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .review-header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stars {
            color: gold;
        }
        .date {
            font-size: 0.9em;
            color: gray;
        }
    </style>
</head>
<body>

<h2>Отзиви от клиенти</h2>

<?php if (count($reviews) > 0): ?>
    <?php foreach ($reviews as $row): ?>
        <div class="review-box">
            <div class="review-header">
                <?= htmlspecialchars($row['name']) ?>
                <span class="stars">
                    <?= str_repeat('★', $row['rating']) ?>
                    <?= str_repeat('☆', 5 - $row['rating']) ?>
                </span>
            </div>
            <div><?= htmlspecialchars($row['comment']) ?></div>
            <div class="date"><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Все още няма оставени отзиви.</p>
<?php endif; ?>

</body>
</html>