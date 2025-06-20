<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php"); // Смени пътя, ако страницата за вход е различна
exit;
?>