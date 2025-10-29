<?php
require 'db.php';
require 'Student.php';

$student = new Student($pdo);

// Создаем таблицу если её нет
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    ticket_number VARCHAR(20) NOT NULL,
    book_genre VARCHAR(100) NOT NULL,
    electronic_version BOOLEAN DEFAULT FALSE,
    rental_period VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$pdo->exec($sql);

// Обрабатываем отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $ticket_number = htmlspecialchars($_POST['ticket_number']);
    $book_genre = htmlspecialchars($_POST['book_genre'] ?? '');
    $electronic_version = isset($_POST['electronic_version']) ? 1 : 0;
    $rental_period = $_POST['rental_period'] ?? '';
    
    $student->add($name, $ticket_number, $book_genre, $electronic_version, $rental_period);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Получаем данные
$all = $student->getAll();
$count = $student->getCount();
$electronic_count = $student->getElectronicCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Библиотека - MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Библиотека - База данных</h1>
        
        <!-- Форма -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <input type="text" class="form-control mb-2" name="name" placeholder="Имя" required>
                    <input type="text" class="form-control mb-2" name="ticket_number" placeholder="Номер билета" required>
                    <select class="form-select mb-2" name="book_genre" required>
                        <option value="">Жанр книги</option>
                        <option value="Фантастика">Фантастика</option>
                        <option value="Детектив">Детектив</option>
                        <option value="Роман">Роман</option>
                    </select>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="electronic_version" value="1">
                        <label class="form-check-label">Электронная версия</label>
                    </div>
                    <div class="mb-2">
                        <label>Срок аренды:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rental_period" value="1 неделя" required>
                            <label class="form-check-label">1 неделя</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="rental_period" value="2 недели">
                            <label class="form-check-label">2 недели</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>

        <!-- Статистика -->
        <div class="card mb-4">
            <div class="card-body">
                <p>Всего заявок: <strong><?= $count ?></strong></p>
                <p>Электронных версий: <strong><?= $electronic_count ?></strong></p>
            </div>
        </div>

        <!-- Список заявок -->
        <div class="card">
            <div class="card-body">
                <?php if (count($all) > 0): ?>
                    <?php foreach($all as $row): ?>
                    <div class="border-bottom py-2">
                        <strong><?= htmlspecialchars($row['name']) ?></strong> 
                        (<?= htmlspecialchars($row['ticket_number']) ?>)
                        - <?= htmlspecialchars($row['book_genre']) ?>
                        - Эл: <?= $row['electronic_version'] ? 'Да' : 'Нет' ?>
                        - <?= htmlspecialchars($row['rental_period']) ?>
                        <small class="text-muted"><?= $row['created_at'] ?></small>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Нет заявок</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
