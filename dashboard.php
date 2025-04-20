<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT * FROM requests";

if ($status_filter) {
    $sql .= " WHERE status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status_filter);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список обращений</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Список обращений</h4>
                        <a href="logout.php" class="btn btn-danger">Выйти</a>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="status" class="form-select">
                                        <option value="">Все</option>
                                        <option value="Новое" <?= $status_filter == 'Новое' ? 'selected' : '' ?>>Новое</option>
                                        <option value="В работе" <?= $status_filter == 'В работе' ? 'selected' : '' ?>>В работе</option>
                                        <option value="Завершено" <?= $status_filter == 'Завершено' ? 'selected' : '' ?>>Завершено</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Применить</button>
                                </div>
                            </div>
                        </form>

                        <div class="mb-3">
                            <a href="export.php" class="btn btn-info w-100">Экспорт в CSV</a>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ФИО</th>
                                    <th>Телефон</th>
                                    <th>Тема</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['full_name'] ?></td>
                                    <td><?= $row['phone'] ?></td>
                                    <td><?= $row['subject'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                    <td><a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Редактировать</a></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <a href="add.php" class="btn btn-success w-100">Добавить новое обращение</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>