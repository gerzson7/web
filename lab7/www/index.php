<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Онлайн-библиотека</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .library-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }
        .book-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .stats-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="library-header text-center">
        <div class="container">
            <div class="book-icon">📚</div>
            <h1>Онлайн-библиотека</h1>
            <p class="lead">Оформление заявки на получение книги</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">📖 Заявка на книгу</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        require 'Student.php';
                        $student = new Student();
                        $stats = $student->getStats();
                        
                        if ($_POST) {
                            require 'send.php';
                        }
                        ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">ФИО читателя:</label>
                                <input type="text" class="form-control" id="name" name="name" value="Иванов Иван Иванович" required>
                            </div>

                            <div class="mb-3">
                                <label for="ticket_number" class="form-label">Номер читательского билета:</label>
                                <input type="text" class="form-control" id="ticket_number" name="ticket_number" value="ЛБ-2024-001" required>
                            </div>

                            <div class="mb-3">
                                <label for="genre" class="form-label">Жанр книги:</label>
                                <select class="form-select" id="genre" name="genre" required>
                                    <option value="Фантастика">Фантастика</option>
                                    <option value="Детектив">Детектив</option>
                                    <option value="Роман">Роман</option>
                                    <option value="Научная литература">Научная литература</option>
                                    <option value="Историческая проза">Историческая проза</option>
                                    <option value="Поэзия">Поэзия</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Формат книги:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="electronic" name="electronic" checked>
                                    <label class="form-check-label" for="electronic">
                                        📱 Электронная версия
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Срок аренды:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rental_period" id="week" value="1 неделя" checked>
                                    <label class="form-check-label" for="week">
                                        1 неделя
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rental_period" id="two_weeks" value="2 недели">
                                    <label class="form-check-label" for="two_weeks">
                                        2 недели
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rental_period" id="month" value="1 месяц">
                                    <label class="form-check-label" for="month">
                                        1 месяц
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                📨 Отправить заявку
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card">
                    <h5>📊 Статистика</h5>
                    <p><strong>Всего заявок:</strong> <?= $stats['total_requests'] ?></p>
                    
                    <h6>По жанрам:</h6>
                    <?php foreach($stats['by_genre'] as $genre => $count): ?>
                        <p class="mb-1"><?= $genre ?>: <?= $count ?></p>
                    <?php endforeach; ?>
                    
                    <h6 class="mt-3">По срокам:</h6>
                    <?php foreach($stats['by_period'] as $period => $count): ?>
                        <p class="mb-1"><?= $period ?>: <?= $count ?></p>
                    <?php endforeach; ?>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5>🚀 Запуск обработчика</h5>
                        <p>Для обработки заявок запустите воркер:</p>
                        <code class="d-block p-2 bg-light rounded">docker exec -it lab7_php php worker.php</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">Лабораторная работа №7 - Асинхронная обработка через файловую очередь</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
