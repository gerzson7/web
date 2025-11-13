<?php
require 'vendor/autoload.php';

use App\ElasticExample;

$elastic = new ElasticExample();
$indexName = 'library_students';

// Создаем индекс если не существует
if (!$elastic->indexExists($indexName)) {
    $result = $elastic->createIndex($indexName);
}

// Обрабатываем отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $ticket_number = htmlspecialchars($_POST['ticket_number']);
    $book_genre = htmlspecialchars($_POST['book_genre'] ?? '');
    $electronic_version = isset($_POST['electronic_version']) ? true : false;
    $rental_period = $_POST['rental_period'] ?? '';
    
    $data = [
        'name' => $name,
        'ticket_number' => $ticket_number,
        'book_genre' => $book_genre,
        'electronic_version' => $electronic_version,
        'rental_period' => $rental_period
    ];
    
    $result = $elastic->indexDocument($indexName, $data);
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Обработка поиска
$searchResults = [];
$searchQuery = '';
if (isset($_GET['search']) && !empty($_GET['search_query'])) {
    $searchQuery = htmlspecialchars($_GET['search_query']);
    $searchResults = $elastic->search($indexName, $searchQuery);
}

// Получаем все данные
$allData = $elastic->getAll($indexName);
$stats = $elastic->getStats($indexName);

// Извлекаем hits из результатов
$students = $allData['hits']['hits'] ?? [];
$searchHits = $searchResults['hits']['hits'] ?? [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Библиотека - Elasticsearch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Библиотека - Elasticsearch</h1>
        
        <!-- Форма добавления -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Добавить новую заявку</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="name" placeholder="Имя студента" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control mb-2" name="ticket_number" placeholder="Номер билета" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-select mb-2" name="book_genre" required>
                                <option value="">Выберите жанр книги</option>
                                <option value="Фантастика">Фантастика</option>
                                <option value="Детектив">Детектив</option>
                                <option value="Роман">Роман</option>
                                <option value="Научная литература">Научная литература</option>
                                <option value="Исторический">Исторический</option>
                                <option value="Приключения">Приключения</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Срок аренды:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rental_period" value="1 неделя" required>
                                    <label class="form-check-label">1 неделя</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rental_period" value="2 недели">
                                    <label class="form-check-label">2 недели</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="electronic_version" value="1" id="electronic_version">
                        <label class="form-check-label" for="electronic_version">
                            📱 Электронная версия
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success">Добавить заявку</button>
                </form>
            </div>
        </div>

        <!-- Поиск -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">🔍 Поиск по заявкам</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="d-flex">
                    <input type="text" class="form-control me-2" name="search_query" 
                           placeholder="Введите имя, номер билета, жанр или срок аренды..." 
                           value="<?= $searchQuery ?>">
                    <button type="submit" name="search" class="btn btn-outline-info">Найти</button>
                    <?php if (!empty($searchQuery)): ?>
                        <a href="?" class="btn btn-outline-secondary ms-2">Очистить</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Статистика -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <h3><?= $stats['total_count'] ?? 0 ?></h3>
                        <p class="mb-0">Всего заявок</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <h3><?= $stats['electronic_count'] ?? 0 ?></h3>
                        <p class="mb-0">Электронных версий</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Результаты поиска -->
        <?php if (!empty($searchQuery) && !empty($searchHits)): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">🔍 Результаты поиска для "<?= $searchQuery ?>"</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Найдено: <?= count($searchHits) ?> записей</p>
                <?php foreach($searchHits as $hit): 
                    $source = $hit['_source'];
                ?>
                <div class="card student-card mb-2 <?= $source['electronic_version'] ? 'electronic-true' : 'electronic-false' ?>">
                    <div class="card-body">
                        <h6 class="card-title">
                            <strong><?= htmlspecialchars($source['name']) ?></strong>
                            <small class="text-muted">(<?= htmlspecialchars($source['ticket_number']) ?>)</small>
                        </h6>
                        <p class="card-text mb-1">
                            <span class="badge bg-primary"><?= htmlspecialchars($source['book_genre']) ?></span>
                            <span class="badge bg-<?= $source['electronic_version'] ? 'success' : 'secondary' ?>">
                                <?= $source['electronic_version'] ? 'Электронная' : 'Бумажная' ?>
                            </span>
                            <span class="badge bg-info"> <?= htmlspecialchars($source['rental_period']) ?></span>
                        </p>
                        <small class="text-muted">Добавлено: <?= date('d.m.Y H:i', strtotime($source['created_at'])) ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php elseif (!empty($searchQuery)): ?>
        <div class="alert alert-warning">
            По запросу "<?= $searchQuery ?>" ничего не найдено
        </div>
        <?php endif; ?>

        <!-- Все заявки -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Все заявки (<?= count($students) ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (count($students) > 0): ?>
                    <?php foreach($students as $hit): 
                        $source = $hit['_source'];
                    ?>
                    <div class="card student-card mb-3 <?= $source['electronic_version'] ? 'electronic-true' : 'electronic-false' ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title mb-1">
                                        <strong><?= htmlspecialchars($source['name']) ?></strong>
                                        <small class="text-muted">(№<?= htmlspecialchars($source['ticket_number']) ?>)</small>
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Жанр:</strong> 
                                        <span class="badge bg-primary"><?= htmlspecialchars($source['book_genre']) ?></span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Версия:</strong> 
                                        <span class="badge bg-<?= $source['electronic_version'] ? 'success' : 'secondary' ?>">
                                            <?= $source['electronic_version'] ? 'Электронная' : 'Бумажная' ?>
                                        </span>
                                    </p>
                                    <p class="card-text mb-1">
                                        <strong>Срок аренды:</strong> 
                                        <span class="badge bg-info"> <?= htmlspecialchars($source['rental_period']) ?></span>
                                    </p>
                                </div>
                                <small class="text-muted text-end">
                                    <?= date('d.m.Y H:i', strtotime($source['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">Нет заявок</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Информация о Elasticsearch -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Информация о Elasticsearch</h5>
            </div>
            <div class="card-body">
                <p><strong>Индекс:</strong> <code><?= $indexName ?></code></p>
                <p><strong>Всего документов:</strong> <?= $stats['total_count'] ?? 0 ?></p>
                <p><strong>Статус:</strong> <span class="badge bg-success">Активен</span></p>
            </div>
        </div>
    </div>

    <footer class="mt-5 py-3 bg-light">
        <div class="container text-center">
            <p class="mb-0">Лабораторная работа №6 - Elasticsearch | Вариант 6</p>
        </div>
    </footer>
</body>
</html>
