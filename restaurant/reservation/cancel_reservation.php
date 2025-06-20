<?php
session_start();
include '../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$reservation_id = $_GET['id'] ?? null;

if ($user_id && $reservation_id) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$reservation_id, $user_id])) {
        $msg = "Резервацията беше успешно отменена!";
    } else {
        $msg = "Грешка при отмяната на резервацията.";
    }
    header("Location: ./reserve.php?message=" . urlencode($msg));
    exit();
} else {
    echo "Невалидна заявка.";
}
?>