<?php
include 'db.php';

// Данные нового пользователя
$username = "admin";
$password = "admin123";

// Хешируем пароль
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Добавляем пользователя в базу данных
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo '<div class="alert alert-success">Пользователь успешно создан!</div>';
} else {
    echo '<div class="alert alert-danger">Ошибка при создании пользователя: ' . $conn->error . '</div>';
}

// Закрываем соединение
$stmt->close();
$conn->close();
?>