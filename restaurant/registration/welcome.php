<?php
session_start();
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Админ панел</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #EFE6DD;
            color: #000;
        }

        .navbar, .footer {
            background-color: #000;
            color: #fff;
        }

        .btn-primary {
            background-color: #000;
            border-color: #000;
        }

        .btn-primary:hover {
            background-color: #333;
            border-color: #333;
        }

        .card {
            background-color: #fff;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 1rem;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <?php
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        echo "<h2>Добре дошли, администратор!</h2>";
        // тук може да се включи навигация за админ панел
    } else {
        echo "<h2>Добре дошли, потребител!</h2>";
    }
    ?>
</div>

</body>
</html>