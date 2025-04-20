<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $sql = "UPDATE requests SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Статус успешно обновлен!</div>';
    } else {
        echo '<div class="alert alert-danger">Ошибка при обновлении статуса.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование обращения</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Редактирование обращения</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="status" class="form-label">Статус</label>
                                <select name="status" class="form-select">
                                    <option value="Новое" <?= $request['status'] == 'Новое' ? 'selected' : '' ?>>Новое</option>
                                    <option value="В работе" <?= $request['status'] == 'В работе' ? 'selected' : '' ?>>В работе</option>
                                    <option value="Завершено" <?= $request['status'] == 'Завершено' ? 'selected' : '' ?>>Завершено</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Сохранить</button>
                        </form>
                        <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Назад к списку обращений</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>