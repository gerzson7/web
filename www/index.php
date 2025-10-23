<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card mt-4">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Главная страница библиотеки</h1>
                
                <!-- Вывод ошибок -->
                <?php if(isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($_SESSION['errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>
                
                <!-- Шаг 3: Информация о пользователе -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Информация о пользователе</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        require_once 'UserInfo.php';
                        $info = UserInfo::getInfo();
                        foreach ($info as $key => $val) {
                            echo '<p><strong>' . htmlspecialchars($key) . ':</strong> ' . htmlspecialchars($val) . '</p>';
                        }
                        
                        if(isset($_COOKIE['last_submission'])): ?>
                            <p><strong>Последняя отправка формы:</strong> <?= htmlspecialchars($_COOKIE['last_submission']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Данные из сессии -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Данные из сессии</h5>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['name'])): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Имя:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
                                    <p><strong>Номер билета:</strong> <?= htmlspecialchars($_SESSION['id']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Жанр книги:</strong> <?= htmlspecialchars($_SESSION['genre']) ?></p>
                                    <p><strong>Электронная версия:</strong> <?= htmlspecialchars($_SESSION['e-version']) ?></p>
                                    <p><strong>Срок аренды:</strong> <?= htmlspecialchars($_SESSION['duration']) ?></p>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Данных пока нет.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Шаг 2: Данные из API -->
                <?php if(isset($_SESSION['api_data'])): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Данные из API</h5>
                        </div>
                        <div class="card-body">
                            <?php if(isset($_SESSION['api_data']['error'])): ?>
                                <div class="alert alert-warning">
                                    Ошибка API: <?= htmlspecialchars($_SESSION['api_data']['error']) ?>
                                </div>
                            <?php else: ?>
                                <h3>Данные из API:</h3>
                                <pre><?= print_r($_SESSION['api_data'], true) ?></pre>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION['api_data']); ?>
                <?php endif; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="form.html" class="btn btn-primary me-md-2">Заполнить форму</a>
                    <a href="view.php" class="btn btn-outline-primary">Посмотреть все данные</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>