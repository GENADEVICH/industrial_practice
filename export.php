<?php
include 'db.php';

// Устанавливаем заголовки для скачивания CSV-файла
header('Content-Type: text/csv; charset=windows-1251');
header('Content-Disposition: attachment; filename="requests.csv"');

// Открываем поток вывода
$output = fopen('php://output', 'w');

// Добавляем BOM для корректного отображения в Excel
fwrite($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Заголовки таблицы (перекодируем в Windows-1251)
$headers = ['ID', 'ФИО', 'Телефон', 'Тема', 'Статус', 'Дата создания'];
fputcsv($output, array_map('convert_encoding', $headers), ',');

// Получаем данные из базы данных
$sql = "SELECT * FROM requests";
$result = $conn->query($sql);

// Перекодируем каждую строку и записываем в файл
while ($row = $result->fetch_assoc()) {
    // Преобразуем дату в удобный формат
    $row['created_at'] = date("d.m.Y H:i:s", strtotime($row['created_at']));

    $rowData = [
        $row['id'],
        $row['full_name'],
        $row['phone'],
        $row['subject'],
        $row['status'],
        $row['created_at']
    ];
    fputcsv($output, array_map('convert_encoding', $rowData), ',');
}

fclose($output);

// Функция для перекодирования текста из UTF-8 в Windows-1251
function convert_encoding($text) {
    if (empty($text)) {
        return '-'; // Заменяем пустые значения на дефис
    }
    return mb_convert_encoding($text, 'Windows-1251', 'UTF-8');
}
?>