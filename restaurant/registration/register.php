<?php
session_start();
include '../includes/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Почистване на входните данни
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $phone    = trim($_POST['phone']);
    $role     = 'user'; // По подразбиране

    // Валидация на телефонния номер (български формат: +359xxxxxxxxx или 0xxxxxxxxx)
    if (!preg_match('/^(\+359|0)[0-9]{9}$/', $phone)) {
        $message = "Моля, въведете валиден телефонен номер (напр. +359888123456 или 0888123456).";
    } else {
        // Проверка дали имейл или телефон вече съществуват
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Имейлът или телефонният номер вече са регистрирани!";
        } else {
            // Хеширане на паролата
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Вмъкване на новия потребител
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone, $role);

            if ($stmt->execute()) {
                $message = "Регистрацията беше успешна! Можете да влезете.";
            } else {
                $message = "Възникна грешка при регистрацията: " . $conn->error;
            }
        }

        $stmt->close();
    }

    $_SESSION['message'] = $message;
    header("Location: register_form.php");
    exit;
}

$conn->close();
?>
