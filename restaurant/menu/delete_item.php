<?php
session_start();
include '../includes/db.php';

// Само за админ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id && is_numeric($id)) {
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);

    // ✅ Пренасочване към страницата за управление на менюто
    header("Location: menu_manage.php?msg=deleted");
    exit();
} else {
    header("Location: menu_manage.php?msg=invalid");
    exit();
}