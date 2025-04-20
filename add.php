<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $status = 'Новое';
    $user_id = $_SESSION['user_id']; // Динамически получаем ID пользователя

    // Проверка на пустые поля
    if (empty($full_name) || empty($phone) || empty($subject) || empty($message)) {
        echo '<div class="alert alert-danger">Все поля обязательны для заполнения!</div>';
        exit;
    }

    // Подготовка SQL-запроса
    $sql = "INSERT INTO requests (full_name, phone, subject, message, status, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssi", $full_name, $phone, $subject, $message, $status, $user_id);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Обращение успешно добавлено!</div>';
        } else {
            echo '<div class="alert alert-danger">Ошибка при добавлении обращения: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    } else {
        echo '<div class="alert alert-danger">Ошибка подготовки запроса: ' . $conn->error . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить обращение</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Добавить новое обращение</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">ФИО</label>
                                <input type="text" class="form-control" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Тема</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Сообщение</label>
                                <textarea class="form-control" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Добавить</button>
                        </form>
                        <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Назад к списку обращений</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>