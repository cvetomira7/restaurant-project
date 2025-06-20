<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "Моля, влезте в профила си, за да оставите оценка.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = $_POST['comment'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, rating, comment) VALUES (:user_id, :rating, :comment)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
        echo "Благодарим за вашата оценка!";
    } catch (PDOException $e) {
        echo "Грешка: " . $e->getMessage();
    }
}
?>

<form method="POST" action="rate.php">
    <label for="rating">Оценка (1 до 5):</label>
    <select name="rating" required>
        <option value="">-- Избери --</option>
        <option value="1">1 - Лошо</option>
        <option value="2">2</option>
        <option value="3">3 - Окей</option>
        <option value="4">4</option>
        <option value="5">5 - Страхотно</option>
    </select><br>

    <label for="comment">Отзив:</label><br>
    <textarea name="comment" rows="4" cols="50"></textarea><br>

    <input type="submit" value="Изпрати">
</form>