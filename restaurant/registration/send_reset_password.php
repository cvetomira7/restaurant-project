<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Проверка дали имейлът съществува
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Записваме токена и срока в базата
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);

        $reset_link = "http://localhost/restaurant/registration/reset_password.php?token=$token";

        $_SESSION['message'] = "Линкът за нулиране на паролата беше изпратен на имейла ви.";
        $_SESSION['reset_link'] = $reset_link; // за тестване и показване на линка
    } else {
        $_SESSION['message'] = "Потребител с този имейл не съществува.";
    }

    header("Location: forgot_password.php");
    exit;
} else {
    header("Location: forgot_password.php");
    exit;
}
