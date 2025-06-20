<?php
session_start();
include '../includes/db.php';

$contact_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $message]);
            $contact_message = '<div class="alert alert-success">Съобщението ви беше изпратено успешно!</div>';
        } catch (PDOException $e) {
            $contact_message = '<div class="alert alert-danger">Грешка при запис: ' . $e->getMessage() . '</div>';
        }
    } else {
        $contact_message = '<div class="alert alert-danger">Моля, попълнете всички полета коректно.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Контакти</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EFE6DD;
            padding-bottom: 50px;
        }
        .contact-info h4 {
            margin-top: 20px;
        }
        .map-container {
            height: 300px;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h1 class="text-center mb-4">Контакти</h1>

    <div class="row">
        <!-- Информация за контакт -->
        <div class="col-md-6 contact-info">
            <h4>Адрес</h4>
            <p>ул. Примерна 123, София</p>

            <h4>Телефон</h4>
            <p>+359 888 123 456</p>

            <h4>Имейл</h4>
            <p>info@restaurant.bg</p>

            <h4>Работно време</h4>
            <p>Пон - Нед: 11:00 - 23:00</p>

        </div>

        <!-- Форма за контакт -->
        <div class="col-md-6">
            <h4>Свържете се с нас</h4>
            <?= $contact_message ?>
            <form method="POST" class="card p-4 shadow-sm bg-white mt-3">
                <div class="mb-3">
                    <label for="name" class="form-label">Име</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Имейл</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Съобщение</label>
                    <textarea class="form-control" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Изпрати</button>
            </form>
        </div>
    </div>
</div>

<!-- Google Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
    function initMap() {
        const location = { lat: 42.6977, lng: 23.3219 }; // София
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location
        });
        new google.maps.Marker({
            position: location,
            map: map,
            title: "Ресторант „Твоето Име“"
        });
    }
    window.onload = initMap;
</script>

</body>
</html>